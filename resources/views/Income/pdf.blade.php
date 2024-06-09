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
            <td>{{ $income_category_id }}</td>
        </tr>
        <tr>
            <th>Income Head</th>
            <td>{{ $income_head_id }}</td>
        </tr>
        <tr>
            <th>Fund Category</th>
            <td>{{ $fund_category_id}}</td>
        </tr>
        <tr>
            <th>Company Name</th>
            <td>{{ $company_name}}</td>
        </tr>
        <tr>
            <th>Email</th>
            <td>{{ $email}}</td>
        </tr>
        <tr>
            <th>Phone Number</th>
            <td>{{ $phone_number}}</td>
        </tr>
        <tr>
            <th>Description</th>
            <td>{!! $description !!}</td>
        </tr>
        <tr>
            <th>Amount</th>
            <td>{{ $amount }}</td>
        </tr>
        <tr>
            <th>Date</th>
            <td>{{ $created_at }}</td>
        </tr>
    </table>
</body>
</html>
