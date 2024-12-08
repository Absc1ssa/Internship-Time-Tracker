<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Office extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'location',
        'photo'
    ];

    // Relationship with interns
    public function interns()
    {
        return $this->hasMany(Intern::class);
    }

    // Optional admin who manages the office
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id')->where('role', 'admin');
    }
}
