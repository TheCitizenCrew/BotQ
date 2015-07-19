<?php
namespace App\Models;

use \Illuminate\Database\Eloquent\Model;
use \Esensi\Model\Contracts\ValidatingModelInterface;
use \Esensi\Model\Traits\ValidatingModelTrait;
use \Esensi\Model\Traits\SoftDeletingModelTrait;
use Illuminate\Support\Facades\Validator;

class Channel extends Model implements ValidatingModelInterface
{

    const LABEL_LENGTH = 45;
    // const toto = 'max:' . self::LABEL_LENGTH ;
    
    use DatePresenter ;
    
    // https://github.com/esensi/model#validating-model-trait
    use ValidatingModelTrait ;
    
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
        ]
    ];
    // 'slug' => [ 'max:16', 'alpha_dash', 'unique' ],
    // 'published' => [ 'boolean' ],
    // ... more attribute rules
    

    /**
     * Permit mass assignement with those fields.
     * Avoid Illuminate\Database\Eloquent\MassAssignmentException.
     *
     * @var array
     */
    protected $fillable = [
        'label',
        'description'
    ];

    /**
     * Get Channel's Messages
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function messages()
    {
        return $this->hasMany('\App\Models\Message');
    }
    
    public function resetMessagesStatus($maxPriority=0)
    {
        \DB::transaction(function() use ($maxPriority)
        {
            // TODO: look at $this->messages()->update($attributes);
            foreach( $this->messages as $m )
            {
                if( $m->priority > $maxPriority )
                    continue ;
                $m->status_got = $m->status_done = $m->status_aborted = $m->status_comment = null ;
                $m->save();
            }
        });
    }

    /**
     * Delete messages with priority >= $minPriority
     */
    public function deletePriorized($minPriority)
    {
        \DB::transaction(function() use ($minPriority)
        {
            foreach( $this->messages as $m )
            {
                if( $m->priority >= $minPriority )
                    $m->delete();
            }
        });
    }

}
