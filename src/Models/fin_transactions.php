<?php

namespace budisteikul\tourcms\Models;

use Illuminate\Database\Eloquent\Model;

class fin_transactions extends Model
{
    
	
	protected $table = 'fin_transactions';
	
	
	public function categories()
    {
        return $this->belongsTo(fin_categories::class,'category_id');
    }
	
}
