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

    <!-- sucess message   -->

    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
    @endif
    <a href="{{route('fundCategory.create')}}"><button type="button" class="btn btn-primary my-4">Add</button></a>
    <table id="fundCategory" class="display " style="width:100%">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Opening Amount</th>
                <th>Added Amount</th>
                <th>Expensed Amount</th>
                <th>Total Amount</th>
            </tr>
        </thead>
    </table>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#fundCategory').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route("fundsCategory.get") }}',
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'openingAmount',
                        name: 'openingAmount'
                    },
                    {
                        data: 'addedFundAmount',
                        name: 'addedFundAmount'
                    },
                    {
                        data:'expensedAmount',
                        name:'expensedAmount'
                    },
                    {
                        data:'total',
                        name:'total'
                    }
                ]
            });
        });
    </script>
    @endsection
</body>

</html>