<?php

namespace budisteikul\tourcms\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Investment extends Model
{
    protected $table = 'investments';
    protected $keyType = 'string';
    protected $dateFormat = 'Y-m-d H:i:s.u';
}

