<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fund extends Model
{
    use HasFactory;

    protected $fillable=['Description', 'category_id' , 'amount'];

    public function category()
    {
        return $this->belongsTo(FundCategory::class,'category_id','id');
    }

    public function expenses()
    {
        return $this->hasMany(OfficeExpense::class,'fund_category ');
    }

    public function fundAddjusment()
    {
        return $this->hasMany(NewFund::class,'category_id');
    }

    
}
