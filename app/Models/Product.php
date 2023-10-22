<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Haruncpi\LaravelIdGenerator\IdGenerator;

class Product extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'code',
        'name',
        'category_id',
        'brand_id',
        'color',
        'unit_selling_price',
        'unit_buying_price',
        'quantity',
        'minimum_required_quantity',
        'status',
        'image',
    ];

    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->code = IdGenerator::generate([
                'table' => 'products',
                'length' => 11,
                'prefix' => 'PRD-',
                'field' => 'code'
            ]);
        });
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id', 'id');
    }

    public function carts()
    {
        return $this->belongsToMany(Cart::class, 'product_carts');
    }

    public function saleDetails()
    {
        return $this->hasMany(SaleDetail::class, 'product_id', 'id');
    }

    public function purchases()
    {
        return $this->hasMany(Purchase::class, 'product_id', 'id');
    }

    public function saleReturns()
    {
        return $this->hasMany(SaleReturn::class, 'product_id', 'id');
    }

    public function promotions()
    {
        return $this->hasMany(Promotion::class, 'product_id', 'id');
    }
}
