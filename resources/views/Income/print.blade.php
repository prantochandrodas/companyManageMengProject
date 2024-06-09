<!-- resources/views/officeExpense/print.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Income Details</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 10px; border: 1px solid #ddd; }
    </style>
</head>
<body>
    <h1>Income Details</h1>
    <table>
        <tr>
            <th>Income Category</th>
            <td>{{ $income->incomeCategory->name }}</td>
        </tr>
        <tr>
            <th>Income Head</th>
            <td>{{ $income->incomeHead->name }}</td>
        </tr>
        <tr>
            <th>Fund Category</th>
            <td>{{ $income->fundCategory->name}}</td>
        </tr>
        <tr>
            <th>Company Name</th>
            <td>{{ $income->company_name}}</td>
        </tr>
        <tr>
            <th>Email</th>
            <td>{{ $income->email}}</td>
        </tr>
        <tr>
            <th>Phone Number</th>
            <td>{{ $income->phone_number}}</td>
        </tr>
        <tr>
            <th>Description</th>
            <td>{!! $income->description !!}</td>
        </tr>
        <tr>
            <th>Amount</th>
            <td>{{ $income->amount }}</td>
        </tr>
        <tr>
            <th>Date</th>
            <td>{{ $income->created_at }}</td>
        </tr>
    </table>
</body>
</html>
