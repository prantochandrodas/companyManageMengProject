<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FundCategory extends Model
{
    use HasFactory;
    protected $fillable = ['name','addedFundAmount','openingAmount','expensedAmount','incomeAmount','total'];

    public function cs()
    {
        return $this->belongsTo(ExpenseHead::class, 'fund_id');
    }
}
