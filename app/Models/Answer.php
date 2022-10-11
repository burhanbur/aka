<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'answers';
    protected $primaryKey = 'id';

    public function responden()
    {
        return $this->belongsTo(Responden::class, 'responden_id');
    }

    public function question()
    {
        return $this->belongsTo(Question::class, 'question_id');
    }
}
