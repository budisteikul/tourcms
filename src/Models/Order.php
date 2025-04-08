<?php

namespace budisteikul\tourcms\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders';
    protected $keyType = 'string';
    protected $dateFormat = 'Y-m-d H:i:s.u';
}
