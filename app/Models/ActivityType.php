<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityType extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'activity_type';
    protected $primaryKey = 'id';

    public function activity()
    {
        return $this->hasMany(Activity::class, 'event_id');
    }
}
