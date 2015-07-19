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
        'priority' => [
            'integer'
        ],
        'play_loop' => [
            'boolean'
        ],
        'play_duration' => [
            'integer',
            'min:1'
        ],
        'content_type' => [
            'required',
            'min:4'
        ],
        'content' => [
            'required'
        ],
        'status_got' => [
            'date'
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
        'play_duration',
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
        // return $query->where('status_got', '=', '')->orWhere('status_got', '=', null);
        $q = Message::newQueryWithoutScopes()->where('status_got', '=', '')->orWhere('status_got', '=', null);
        return $query->addNestedWhereQuery( $q->getQuery() );
    }

    /**
     * Set message's status to "got", "done" or "aborted".
     * It's possible to reset all status with $status="reset".
     * 
     * @param number $channelId
     * @param number $messageId
     * @param string $status
     * @throws InvalidArgumentException
     * @return \App\Models\Message
     */
    public static function setMessageStatus($channelId, $messageId, $status, $comment=null)
    {
        /**
         * for debug : reset status :
         * update messages set status_got=null, status_done=null, status_aborted=null where channel_id=1
         */

        $m = Message::find($messageId);
        if ($m->channel_id != $channelId) {
            throw new InvalidArgumentException('Channel does not match ' . $channelId . ' ' . $messageId);
        }
        // $m->status_got = \Carbon\Carbon::now('Europe/Paris');
        // $m->status_got = \Carbon\Carbon::now();

        switch ($status) {
            case 'got':
                $m->status_got = new \Carbon\Carbon();
                break;
            case 'done':
                $m->status_done = new \Carbon\Carbon();
                break;
            case 'aborted':
                $m->status_aborted = new \Carbon\Carbon();
                break;
            case 'reset':
                $m->status_got = $m->status_done = $m->status_aborted = null ;
                break;
            default:
                throw new InvalidArgumentException('Invalid message status "' . $status . '"');
        }
        $m->status_comment = empty($comment)?'':$comment ;
        $m->save();
        return $m;
    }
}
