<!-- create.blade.php (resources/views/expense_categories/create.blade.php) -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Expense Category</title>
</head>
<body style="font-family: Arial, sans-serif;">
@extends('welcome')
@section('content')
<div style="max-width: 600px; margin: 0 auto;">
    <div style="background-color: #f0f0f0; padding: 20px;">
        <h2 style="text-align: center;">Create Expense</h2>
    </div>
    
    <div style="background-color: #fff; padding: 20px; border: 1px solid #ccc;">
        <form method="POST" action="{{ route('expensehead.store') }}">
            @csrf
            <div style="margin-bottom: 20px;">
                <label for="expense_category_id" style="display: block; margin-bottom: 5px;">Category Name:</label>
                <select id="expense_category_id" name="expense_category_id" class="form-control" style="width: 100%; padding: 8px;">
                    @foreach ($expenses as $expense)
                        <option value="{{ $expense->id }}">{{ $expense->name }}</option>
                    @endforeach
                </select>
            </div>
            <div style="margin-bottom: 20px;">
                <label for="name" style="display: block; margin-bottom: 5px;">Expense Head:</label>
                <input type="text" id="name" name="name" class="form-control" style="width: 100%; padding: 8px;" required>
            </div>
            <button type="submit" style="background-color: #4CAF50; color: white; padding: 10px 20px; border: none; cursor: pointer; border-radius: 5px;">Create</button>
        </form>
    </div>
</div>
@endsection
</body>
</html>
