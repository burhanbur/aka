<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Survey extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'surveys';
    protected $primaryKey = 'id';
    protected $fillable = [
        'activity_id', 
        'title', 
        'description', 
        'is_active', 
        'due_date', 
    ];

    public function activity()
    {
        return $this->belongsTo(Activity::class, 'activity_id');
    }

    public function question()
    {
        return $this->hasMany(Question::class, 'survey_id');
    }

    public function responden()
    {
        return $this->hasMany(Responden::class, 'survey_id');
    }
}
