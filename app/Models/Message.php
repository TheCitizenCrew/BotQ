<?php
namespace App\Models;

use \Illuminate\Database\Eloquent\Model;
use \Esensi\Model\Contracts\ValidatingModelInterface;
use \Esensi\Model\Traits\ValidatingModelTrait;
use \Esensi\Model\Traits\SoftDeletingModelTrait;

class Message extends Model implements ValidatingModelInterface 
{
    const LABEL_LENGTH = 255 ;
    
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
        'label' => [ 'required','min:1','max:45' ]
    ];
    
    /**
     * Permit mass assignement with those fields.
     * Avoid Illuminate\Database\Eloquent\MassAssignmentException.
     * @var array
     */
    protected $fillable = ['label', 'description'];
    
    /**
     * Get Message's Channel
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function channel()
    {
        return $this->belongsTo('\App\Models\Channel');
    }
}
