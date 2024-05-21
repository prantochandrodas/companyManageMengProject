<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expence extends Model
{
    use HasFactory;

    protected $fillable = ['name'];
    public function cs()
    {
        return $this->hasMany(ExpenseHead::class, 'expense_category_id');
    }
}
