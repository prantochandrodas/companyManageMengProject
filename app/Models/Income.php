<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Income extends Model
{
    use HasFactory;

    protected $fillable=['income_category_id','income_head_id','amount','company_name','description'];

    public function incomeCategory(){
        return $this->belongsTo(IncomeCategory::class,'income_category_id');
    }

    public function incomeHead(){
        return $this->belongsTo(IncomeHead::class,'income_head_id');
    }
}
