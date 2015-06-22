<?php
namespace App\Models;

use \Illuminate\Database\Eloquent\Model;
use \Esensi\Model\Contracts\ValidatingModelInterface;
use \Esensi\Model\Traits\ValidatingModelTrait;
use \Esensi\Model\Traits\SoftDeletingModelTrait;

class Message extends Model implements ValidatingModelInterface
{

    const LABEL_LENGTH = 45;
    
    use DatePresenter ;
    
    // https://github.com/esensi/model#validating-model-trait
    use ValidatingModelTrait;
    
    // https://github.com/esensi/model#soft-deleting-model-trait
    use SoftDeletingModelTrait ;

    /**
     * These are the default rules that the model will validate against.
     * Developers will probably want to specify generic validation rules
     * that would apply in any save operation vs. form or route
     * specific validation rules. For simple models, these rules can
     * apply to all save operations.
     *
     * @var array
     */
    protected $rules = [
        'label' => [
            'required',
            'min:1',
            'max:45'
        ],
        'content_type' => [
            'required',
            'min:4'
        ],
        'content' => [
            'required'
        ]
    ];

    /**
     * Permit mass assignement with those fields.
     * Avoid Illuminate\Database\Eloquent\MassAssignmentException.
     *
     * @var array
     */
    protected $fillable = [
        'channel_id',
        'label',
        'priority',
        'priority_action',
        'play_loop',
        'play_at_time',
        'content_type',
        'content',
        'status_got'
    ];

    /**
     * Get Message's Channel
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function channel()
    {
        return $this->belongsTo('\App\Models\Channel');
    }

    public function scopeForChannel($query, $channelId)
    {
        return $query->where('channel_id', '=', $channelId);
    }

    public function scopeNotDone($query)
    {
        return $query->where('status_got', '=', '');
    }

    public static function getMessagesSet($channelId)
    {
        return Message::forChannel($channelId)->notDone()
            ->orderBy('priority', 'desc')
            ->orderBy('id', 'asc')
            ->
        // ->groupBy('priority')
        limit(2)
            ->get();
    }

    public static function setMessageStatusGot($channelId, $messageId)
    {
        $m = Message::find($messageId);
        if ($m->channel_id != $channelId)
            throw new Exception('Channel does not match ' . $channelId . ' ' . $messageId);
            // $m->status_got = \Carbon\Carbon::now('Europe/Paris');
            // $m->status_got = \Carbon\Carbon::now();
        $m->status_got = new \Carbon\Carbon();
        $m->save();
        return $m;
    }
}
