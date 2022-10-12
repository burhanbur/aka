<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Major extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'majors';
    protected $primaryKey = 'id';

    public function institution()
    {
        return $this->belongsTo(Institution::class, 'institution_id');
    }

    public function student()
    {
        return $this->hasMany(Student::class, 'institution_id');
    }
}
