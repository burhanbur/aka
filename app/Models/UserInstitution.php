<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserInstitution extends MultiplePrimaryKey
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'user_institution';
    protected $primaryKey = ['user_id', 'institution_id'];
    protected $fillable = [
        'user_id',
        'institution_id',
    ];
    
    public $incrementing = false;

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function institution()
    {
        return $this->belongsTo(Institution::class, 'institution_id');
    }
}