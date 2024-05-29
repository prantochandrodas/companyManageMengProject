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
            <td>{{ $id }}</td>
        </tr>
        <tr>
            <th>Expense Category</th>
            <td>{{ $expenseCategory }}</td>
        </tr>
        <tr>
            <th>Expense Head</th>
            <td>{{ $expenseHeadCategory }}</td>
        </tr>
        <tr>
            <th>Fund Category</th>
            <td>{{ $fundCategory}}</td>
        </tr>
        <tr>
            <th>Description</th>
            <td>{{ $description }}</td>
        </tr>
        <tr>
            <th>Amount</th>
            <td>{{ $amount }}</td>
        </tr>
        <tr>
            <th>Date</th>
            <td>{{ $date }}</td>
        </tr>
    </table>
</body>
</html>
