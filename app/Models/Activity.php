<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'activities';
    protected $primaryKey = 'id';
    protected $fillable = [
        'activity_type', 
        'name', 
    ];

    public function activityType()
    {
        return $this->belongsTo(ActivityType::class, 'activity_type');
    }

    public function survey()
    {
        return $this->hasMany(Survey::class, 'activity_id');
    }
}
