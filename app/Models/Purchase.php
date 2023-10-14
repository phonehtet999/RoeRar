<?php

namespace App\Models;

use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'invoice_number',
        'supplier_id',
        'staff_id',
        'product_id',
        'unit_selling_price',
        'unit_buying_price',
        'payment_type',
        'quantity',
        'status',
        'description',
    ];

    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->invoice_number = IdGenerator::generate([
                'table' => 'purchases',
                'length' => 11,
                'prefix' => 'INV-',
                'field' => 'invoice_number'
            ]);
        });
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id', 'id');
    }

    public function staff()
    {
        return $this->belongsTo(Staff::class, 'staff_id', 'id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function payment()
    {
        return $this->hasOne(Payment::class, 'reference_id', 'id');
    }
}
