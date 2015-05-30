<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use LaravelBook\Ardent\Ardent;

class Message extends Ardent
{
    
    use DatePresenter ;

    /**
     * Get it's Channel
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function channel()
    {
        return $this->belongsTo('\App\Models\Channel');
    }
}
