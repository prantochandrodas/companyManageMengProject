<!-- resources/views/officeExpense/print.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Expense Details</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 10px; border: 1px solid #ddd; }
    </style>
</head>
<body>
    <h1>Expense Details</h1>
    <table>
        <tr>
            <th>ID</th>
            <td>{{ $officeExpense->id }}</td>
        </tr>
        <tr>
            <th>Expense Category</th>
            <td>{{ $officeExpense->expenseCategory->name }}</td>
        </tr>
        <tr>
            <th>Expense Head</th>
            <td>{{ $officeExpense->expenseHeadCategory->name }}</td>
        </tr>
        <tr>
            <th>Fund Category</th>
            <td>{{ $officeExpense->fundCategory->name}}</td>
        </tr>
        <tr>
            <th>Description</th>
            <td>{{ $officeExpense->description }}</td>
        </tr>
        <tr>
            <th>Amount</th>
            <td>{{ $officeExpense->amount }}</td>
        </tr>
        <tr>
            <th>Date</th>
            <td>{{ $officeExpense->created_at->format('Y-m-d') }}</td>
        </tr>
    </table>
</body>
</html>
