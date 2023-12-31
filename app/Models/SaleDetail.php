<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleDetail extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'sale_id',
        'product_id',
        'quantity',
        'unit_price',
        'total_amount',
        'promotion_id',
        'total_promoted_qty',
        'total_promoted_amount',
    ];

    public function sale()
    {
        return $this->belongsTo(Sale::class, 'sale_id', 'id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function saleReturns()
    {
        return $this->hasMany(SaleReturn::class, 'sale_detail_id', 'id');
    }

    public function promotion()
    {
        return $this->belongsTo(Promotion::class, 'promotion_id', 'id');
    }
}
