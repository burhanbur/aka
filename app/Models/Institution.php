<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Institution extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'institutions';
    protected $primaryKey = 'id';
    protected $fillable = [
        'code',
        'name',
        'telephone',
        'email',
        'website',
        'address',
        'postal_code',
    ];

    public function user() {
        return $this->belongsToMany(Institution::class, 'user_institution', 'institution_id', 'user_id');
    }

    public function major()
    {
        return $this->hasMany(Major::class, 'institution_id');
    }

    public function student()
    {
        return $this->hasMany(Student::class, 'institution_id');
    }
}
