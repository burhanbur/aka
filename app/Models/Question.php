<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'questions';
    protected $primaryKey = 'id';

    public function survey()
    {
        return $this->belongsTo(Survey::class, 'survey_id');
    }

    public function multipleChoice()
    {
        return $this->hasMany(MultipleChoice::class, 'question_id');
    }

    public function answer()
    {
        return $this->hasMany(Answer::class, 'question_id');
    }
}
