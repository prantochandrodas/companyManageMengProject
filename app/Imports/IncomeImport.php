<?php

namespace App\Imports;

use App\Models\FundCategory;
use App\Models\Income;
use App\Models\IncomeCategory;
use App\Models\IncomeHead;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class IncomeImport implements ToModel, WithHeadingRow
{
    public static $totalAmount = 0;
    public static $importedData = [];
    
    public function model(array $row)
    {
        
    // Log the row to check the structure
        Log::info('Processing row:', $row);
        // Get the IDs for income category, income head, and fund category based on their names
        $incomeCategoryId = IncomeCategory::where('name', $row['income_category_id'])->value('id');
        $incomeHeadId = IncomeHead::where('name', $row['income_head_id'])->value('id');
        $fundCategoryId = FundCategory::where('name', $row['fund_category_id'])->value('id');
      
        if ($fundCategoryId) {
            self::$totalAmount += $row['amount'];
        }

        // Store the processed row in the static property
        self::$importedData[] = [
            'income_category_id' => $incomeCategoryId,
            'income_head_id' => $incomeHeadId,
            'fund_category_id' => $fundCategoryId,
            'amount' => $row['amount'],
            'name' => $row['name'],
            'company_name' => $row['company_name'],
            'description' => $row['description'],
            'email' => $row['email'],
            'phone_number' => $row['phone_number'],
        ];
        // dd($getIncomeAmount);

        return new Income([
            'income_category_id' => $incomeCategoryId,
            'income_head_id' => $incomeHeadId,
            'fund_category_id' => $fundCategoryId,
            'amount' => $row['amount'],
            'name' => $row['name'],
            'company_name' => $row['company_name'],
            'description' => $row['description'],
            'email' => $row['email'],
            'phone_number' => $row['phone_number'],
        ]);

    }
}
