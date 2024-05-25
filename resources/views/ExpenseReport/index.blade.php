<!-- index.blade.php (resources/views/expense_heads/index.blade.php) -->

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Expense Heads</title>
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">

</head>

<body style="font-family: Arial, sans-serif;">
    @extends('welcome')
    @section('content')
    <!-- sucess message  -->
    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    <!-- error message  -->
    @if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
    @endif
    <div class="mt-4 p-4  bg-light">
        <form action="{{ route('report.filter') }}" method="GET" class="row g-2">
            <div class="col-md-4">
                <label for="expense_category" class="form-label h5">Expense Category</label>
                <select class="form-select rounded-0" id="expense_category" name="expense_category">
                    <option value="">All expense category</option>
                    <!-- Add options dynamically if needed -->
                    @foreach ($expenseCategories as $category)
                    <option value="{{ $category->id }}" {{ request('expense_category') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4">
                <div style="margin-bottom: 20px;">
                    <label for="expense_head_category" style="display: block; margin-bottom: 5px;">Expense Head:</label>
                    <select name="expense_head_category" id="expense_head_category" class="form-control" style="width: 100%; padding: 8px;" name="expense_category">
                        <option value="">All Head</option>
                    </select>
                </div>
            </div>

            <div class="col-md-4">
                <label for="fund" class="form-label h5">Fund</label>
                <select class="form-select rounded-0" id="fund_category" name="fund_category">
                    <option value="">All Fund</option>
                    <!-- Add options dynamically if needed -->
                    @foreach ($fundCategories as $category)
                    <option value="{{ $category->id }}" {{ request('fund_category') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label for="fromdate" class="form-label h5">From Date</label>
                <input type="date"  class="form-control rounded-0" id="fromdate" name="fromdate" value="{{ request('fromdate') }}">
            </div>
            <div class="col-md-4">
                <label for="todate" class="form-label h5">To Date</label>
                <input type="date" class="form-control rounded-0" id="todate" name="todate" value="{{ request('todate') }}">
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <button type="submit" id="filterButton" class="btn btn-primary">Filter</button>
            </div>
        </form>
    </div>
    @if(isset($expenses))
    @if($expenses->isEmpty())
        <p>No records found</p>
    @else


    <table style="border-collapse: collapse; width: 100%;">
        <thead>
            <tr style="border-bottom: 1px solid #ddd;">
                <th style="padding: 8px; text-align: left;">ID</th>
                <th style="padding: 8px; text-align: left;">Expense Category</th>
                <th style="padding: 8px; text-align: left;">Expense Head</th>
                <th style="padding: 8px; text-align: left;">Fund Category</th>
                <th style="padding: 8px; text-align: left;">Description</th>
                <th style="padding: 8px; text-align: left;">Amount</th>
                <th style="padding: 8px; text-align: left;">Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach($expenses as $expense)
            <tr style="border-bottom: 1px solid #ddd;">
                <td style="padding: 8px;">{{ $expense->id }}</td>
                <td style="padding: 8px;">{{ $expense->expenseCategory->name }}</td>
                <td style="padding: 8px;">@if($expense->expenseHeadCategory){{ $expense->expenseHeadCategory->name }} @else No Expense Head @endif</td>
                <td style="padding: 8px;">{{ $expense->fundCategory->name }}</td>
                <td style="padding: 8px;">{{ $expense->description }}</td>
                <td style="padding: 8px;">{{ $expense->amount }}</td>
                <td style="padding: 8px;">{{ $expense->created_at->format('Y-m-d') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <!-- <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Expense Category</th>
                <th>Expense Head</th>
                <th>Fund Name</th>
                <th>Description</th>
                <th>Amount</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach($expenses as $expense)
            <tr>
                <td>{{ $expense->id }}</td>
                <td>{{ $expense->expenseCategory->name }}</td>
                <td>@if($expense->expenseHead)
        {{ $expense->expenseHeadCategory->name }}
    @else
        No Expense Head
    @endif</td>
                <td>{{ $expense->fundCategory->name }}</td>
                <td>{{ $expense->description }}</td>
                <td>{{ $expense->amount }}</td>
                <td>{{ $expense->created_at->format('Y-m-d') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table> -->
    @endif
    @endif
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            function loadExpenseHeads() {
                const expenseCategoryId = $('#expense_category').val();
                if (expenseCategoryId) {
                    $.ajax({
                        url: '{{ route("/report.getExpenseHeads") }}',
                        type: 'GET',
                        data: { expense_category_id: expenseCategoryId },
                        success: function(data) {
                            let options = '<option value="">Select Head</option>';
                            data.forEach(function(head) {
                                options += `<option value="${head.id}" ${head.id == '{{ request("expense_head_category") }}' ? 'selected' : ''}>${head.name}</option>`;
                            });
                            $('#expense_head_category').html(options);
                        }
                    });
                } else {
                    $('#expense_head_category').html('<option value="">Select Head</option>');
                }
            }

            $('#expense_category').change(loadExpenseHeads);

            @if(request('expense_category'))
                loadExpenseHeads();
            @endif
        });
    </script>
    @endsection

</body>

</html>