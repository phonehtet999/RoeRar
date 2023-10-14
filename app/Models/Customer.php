<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'phone_number',
        'address',
    ];

    public static function boot()
    {
        parent::boot();
        self::created(function ($model) {
            $model->cart()->create([
                'total_amount' => 0,
                'status' => 'pending',
            ]);
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function sales()
    {
        return $this->hasMany(Sale::class, 'customer_id', 'id');
    }

    public function cart()
    {
        return $this->hasMany(Cart::class, 'customer_id', 'id');
    }
}
