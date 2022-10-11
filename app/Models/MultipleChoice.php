<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MultipleChoice extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'multiple_choices';
    protected $primaryKey = 'id';

    public function question()
    {
        return $this->belongsTo(Question::class, 'question_id');
    }
}
