<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Channel extends Model
{
    
    use DatePresenter ;

    /**
     * Get Channel's Messages
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function messages()
    {
        return $this->hasMany('\App\Models\Message');
    }
}
