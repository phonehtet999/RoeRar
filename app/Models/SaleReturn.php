<?php

namespace App\Models;

use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleReturn extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'code',
        'sale_detail_id',
        'product_id',
        'returned_quantity',
        'total_returned_amount',
        'description',
        'exchange_prd_id',
    ];

    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->code = IdGenerator::generate([
                'table' => 'sale_returns',
                'length' => 11,
                'prefix' => 'RTN-',
                'field' => 'code'
            ]);
        });
    }

    public function saleDetail()
    {
        return $this->belongsTo(SaleDetail::class, 'sale_detail_id', 'id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function exchangedProduct()
    {
        return $this->belongsTo(Product::class, 'exchange_prd_id', 'id');
    }
}
