<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Income extends Model
{
    use HasFactory;

    protected $fillable=['income_category_id','income_head_id','fund_category_id','amount','name','company_name','description','email','phone_number'];

    public function incomeCategory(){
        return $this->belongsTo(IncomeCategory::class,'income_category_id');
    }

    public function IncomeHead(){
        return $this->belongsTo(IncomeHead::class,'income_head_id');
    }

    public function fundCategory(){
        return $this->belongsTo(FundCategory::class,'fund_category_id');
    }
}
