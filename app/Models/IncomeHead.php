<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IncomeHead extends Model
{
    use HasFactory;
    protected $fillable=['name','income_category_id'];

    public function incomeCategory(){
        return $this->belongsTo(IncomeCategory::class,'income_category_id');
    }
}
