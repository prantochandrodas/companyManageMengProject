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
    <a href="{{route('officeExpense.create')}}"><button type="button" class="btn btn-primary my-4">Add</button></a>
    <table id="mydata" class="display" style="width:100%">
        <thead>
            <tr>
                <th>ID</th>
                <th>Expense Category</th>
                <th>Expense head</th>
                <th>Used Fund</th>
                <th>Description</th>
                <th>Amount</th>
                <th>Action</th>
            </tr>
        </thead>
    </table>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#mydata').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route("officeExpense.get") }}',
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {data: 'expense_category', name: 'expense_category'},
                    {data: 'expenseHead_category', name: 'expenseHead_category'},
                    {data: 'fund_category', name: 'fund_category'},
                    {data: 'description', name: 'description'},
                    {data: 'amount', name: 'amount'},
                    { data: 'action', name: 'action', orderable: false, searchable: false }
                ]
            });
        });
    </script>
    @endsection
</body>

</html>