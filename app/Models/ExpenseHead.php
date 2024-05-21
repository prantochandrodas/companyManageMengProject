<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpenseHead extends Model
{
    use HasFactory;
    protected $fillable=['name', 'expense_category_id'];

    public function category()
    {
        return $this->belongsTo(Expence::class,'expense_category_id','id');
    }

}
