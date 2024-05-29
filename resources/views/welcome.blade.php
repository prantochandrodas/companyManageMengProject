<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
    <!-- CSS only -->
    <link href="/bootstrap-5.3.3-dist/css/bootstrap.css" rel="stylesheet">



</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-6 col-lg-3 bg-light sidebar" style="height: 100vh; overflow-y: auto; position: sticky; top:0">
                <div class="sticky-top">

                    <ul class="nav flex-column" style="list-style-type: none; padding: 0;">
                        <li style="margin-bottom: 10px;">
                            <a href="#" style="text-decoration: none; color: black; display: block;  padding: 10px; border-radius: 5px;" class="fw-bold fs-1 py-4">Dashboard</a>
                        </li>
                        <li style="margin-bottom: 10px;">
                            <a href="/" style="text-decoration: none; color: black; display: block; padding: 10px; border-radius: 5px;">Funds</a>
                        </li>
                        <li style="margin-bottom: 10px;">
                            <a href="{{route('fund.index')}}" style="text-decoration: none; color: black; display: block; padding: 10px; border-radius: 5px;">Funds Adjustment</a>
                        </li>
                        <li style="margin-bottom: 10px;">
                            <a href="{{route('expenses.index')}}" style="text-decoration: none; color: black; display: block; padding: 10px; border-radius: 5px;">Expenses Category</a>
                        </li>
                        <li style="margin-bottom: 10px;">
                            <a href="{{ route('expenseshead.index') }}" style="text-decoration: none; color: black; display: block; padding: 10px; border-radius: 5px;">Expense Head</a>
                        </li>
                        <li style="margin-bottom: 10px;">
                            <a href="{{ route('officeExpense.index') }}" style="text-decoration: none; color: black; display: block; padding: 10px; border-radius: 5px;">Expense</a>
                        </li>
                        <li style="margin-bottom: 10px;">
                            <a href="{{ route('report.page') }}" style="text-decoration: none; color: black; display: block; padding: 10px; border-radius: 5px;">Expense Report</a>
                        </li>
                        
                    </ul>
                </div>
            </div>

            <!-- Main content area -->
            <main role="main" class="col-md-9 ml-sm-auto col-lg-9 px-4">
                @yield('content')
            </main>
        </div>
    </div>
    <!-- JavaScript Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-N4Kk0OeY7o/kl1db+GF1bl6s/Wj4u14Jo2hC8+gR9zZzSVPp/H1g/hb9MKAPvphK" crossorigin="anonymous"></script>
</body>

</html>