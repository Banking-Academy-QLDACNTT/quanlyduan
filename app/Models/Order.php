<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';

    protected $fillable = [
        'orderId',
        'orderName',
        'customerId',
        'orderDate',
        'orderStatusId',
        'note',
        'totalOrderValue',
        'updateAt',
        'employeeId',
    ];

    public $timestamps = false;

    // Quan hệ với orderDetails
    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class, 'orderId', 'orderId'); // 'orderId' trong orderDetails liên kết với 'orderId' trong orders
    }
}