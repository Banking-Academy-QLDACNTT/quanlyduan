<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    // Khóa chính composite
    protected $primaryKey = ['productId', 'colorId', 'sizeId', 'materialId'];

    public $incrementing = false;  // Để Laravel không tự động tăng giá trị khóa chính

    // Các trường cần thiết
    protected $fillable = ['productId', 'quantity', 'colorId', 'sizeId', 'materialId'];

    // Quan hệ với order
    public function order()
    {
        return $this->belongsTo(Order::class, 'orderId', 'orderId'); // Liên kết với bảng orders
    }

    public $timestamps = false;
}
