<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use LaravelBook\Ardent\Ardent;

class Channel extends Ardent
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
