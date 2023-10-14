<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'position',
        'phone_number',
        'salary',
        'address',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function sales()
    {
        return $this->hasMany(Sale::class, 'staff_id', 'id');
    }

    public function purchases()
    {
        return $this->hasMany(Purchase::class, 'staff_id', 'id');
    }
}
