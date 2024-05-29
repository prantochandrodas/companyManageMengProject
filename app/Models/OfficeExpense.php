<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OfficeExpense extends Model
{
    use HasFactory;
    protected $fillable = ['expense_category', 'expense_head_category', 'fund_category', 'description','amount','expense_master_id'];
   
    public function expenseCategory()
    {
        return $this->belongsTo(Expence::class, 'expense_category', 'id');
    }
    
    public function expenseHeadCategory()
    {
        return $this->belongsTo(ExpenseHead::class, 'expense_head_category', 'id');
    }

    
    public function fundCategory()
    {
        return $this->belongsTo(FundCategory::class, 'fund_category', 'id');
    }

    public function expenseMaster(){
        return $this->belongsTo(ExpenseMaster::class, 'expense_master_id', 'id');
    }

}
