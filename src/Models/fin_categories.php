<?php

namespace budisteikul\tourcms\Models;

use Illuminate\Database\Eloquent\Model;

class fin_categories extends Model
{
	protected $table = 'fin_categories';
	
	public function transactions()
    {
        return $this->hasMany('budisteikul\tourcms\Models\fin_transactions','category_id','id');
    }

    public function child()
    {
        return $this->hasMany(fin_categories::class,'parent_id','id');
    }

    public function parent()
    {
        return $this->belongsTo(fin_categories::class,'parent_id','id');
    }

}
