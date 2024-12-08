<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'date',
        'am_clock_in',
        'am_clock_out',
        'pm_clock_in',
        'pm_clock_out',
        'am_clock_in_image',
        'am_clock_out_image',
        'pm_clock_in_image',
        'pm_clock_out_image',
        // 'photo',
    ];
    
    protected $casts = [
        'date' => 'date',
        'am_clock_in' => 'datetime',
        'am_clock_out' => 'datetime',
        'pm_clock_in' => 'datetime',
        'pm_clock_out' => 'datetime',
    ];

    /**
     * Define the relationship with the User model.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id'); // Ensure this references the correct foreign key
    }

    public function attendance()
    {
        return $this->hasMany(Attendance::class, 'intern_id'); // Or use the correct foreign key
    }
}
