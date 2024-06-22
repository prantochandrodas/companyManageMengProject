<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laravel</title>
    <!-- CSS only -->
    <link href="/bootstrap-5.3.3-dist/css/bootstrap.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.3/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
   
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.3/js/bootstrap.bundle.min.js"></script>
    <style>
        /* Hide scrollbar for Wejbkit browsers (Chrome, Safari) */
        .sidebar::-webkit-scrollbar {
            display: none;
        }

        /* Hide scrollbar for Firefox */
        .sidebar {
            scrollbar-width: none;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Dashboard</a>
            <div class="d-flex justify-content-center mt-3">
                @auth <!-- Check if the user is authenticated -->
                    <p class="fs-5 text-white">{{ Auth::user()->name }}</p>
                    <div class="ms-2">
                        <a href="{{ route('logout') }}" class="btn btn-sm btn-success"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </div>
                @endauth
                @guest <!-- Show login/register links if the user is not authenticated -->
                    <a href="{{ route('login') }}" class="btn btn-sm btn-primary">Login</a>
                    <a href="{{ route('register') }}" class="btn btn-sm btn-secondary ms-2">Register</a>
                @endguest
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-6 col-lg-3 bg-light sidebar"
                style="height: 100vh; overflow-y: auto; position: sticky; top:0">
                <div class="sticky-top">

                    <ul class="nav flex-column" style="list-style-type: none; padding: 0; margin-top:40px">
                        @can('manage-user')
                            <li class="nav-item" style="margin-bottom: 10px;">
                                <div>
                                    <a class="nav-link d-flex align-items-center" href="#manageUserCollapse"
                                        data-bs-toggle="collapse" role="button" aria-expanded="false"
                                        aria-controls="manageUserCollapse"
                                        style="text-decoration: none; gap:10px; color: black; display: block; padding: 10px; border-radius: 5px;">
                                        Manage User
                                        <i class="fas fa-angle-down"></i>
                                    </a>

                                </div>
                                <div class="collapse" id="manageUserCollapse">
                                    <ul class="nav flex-column" style="list-style-type: none; padding-left: 20px;">

                                        @can('user')
                                            <li class="nav-item">
                                                <a class="nav-link" href="{{ url('/user') }}" style="color: black;">
                                                    User</a>
                                            </li>
                                        @endcan

                                        @can('role')
                                            <li class="nav-item">
                                                <a class="nav-link" href="{{ url('/role') }}" style="color: black;">Role</a>
                                            </li>
                                        @endcan

                                        @can('permission')
                                            <li class="nav-item">
                                                <a class="nav-link" href="{{ url('/permissions') }}"
                                                    style="color: black;">Permission</a>
                                            </li>
                                        @endcan
                                        <!-- Add more submenu items as needed -->
                                    </ul>
                                </div>
                            </li>
                        @endcan

                        @can('fund-category')
                            <li style="margin-bottom: 10px;">
                                <a href="/"
                                    style="text-decoration: none; color: black; display: block; padding: 10px; border-radius: 5px;">Funds</a>
                            </li>
                        @endcan


                        @can('fund-addjustment')
                            <li style="margin-bottom: 10px;">
                                <a href="{{ route('fund.index') }}"
                                    style="text-decoration: none; color: black; display: block; padding: 10px; border-radius: 5px;">Funds
                                    Adjustment</a>
                            </li>
                        @endcan

                        @can('expense-category')
                            <li style="margin-bottom: 10px;">
                                <a href="{{ route('expenses.index') }}"
                                    style="text-decoration: none; color: black; display: block; padding: 10px; border-radius: 5px;">Expenses
                                    Category</a>
                            </li>
                        @endcan

                        @can('expense-head')
                            <li style="margin-bottom: 10px;">
                                <a href="{{ route('expenseshead.index') }}"
                                    style="text-decoration: none; color: black; display: block; padding: 10px; border-radius: 5px;">Expense
                                    Head</a>
                            </li>
                        @endcan

                        @can('expense')
                            <li style="margin-bottom: 10px;">
                                <a href="{{ route('officeExpense.index') }}"
                                    style="text-decoration: none; color: black; display: block; padding: 10px; border-radius: 5px;">Expense</a>
                            </li>
                        @endcan

                        @can('expense-report')
                            <li style="margin-bottom: 10px;">
                                <a href="{{ route('report.page') }}"
                                    style="text-decoration: none; color: black; display: block; padding: 10px; border-radius: 5px;">Expense
                                    Report</a>
                            </li>
                        @endcan


                        @can('income-category')
                            <li style="margin-bottom: 10px;">
                                <a href="{{ route('incomeCategory.index') }}"
                                    style="text-decoration: none; color: black; display: block; padding: 10px; border-radius: 5px;">Income
                                    category</a>
                            </li>
                        @endcan

                        @can('income-head')
                            <li style="margin-bottom: 10px;">
                                <a href="{{ route('incomeHead.index') }}"
                                    style="text-decoration: none; color: black; display: block; padding: 10px; border-radius: 5px;">Income
                                    head</a>
                            </li>
                        @endcan

                        @can('income')
                            <li style="margin-bottom: 10px;">
                                <a href="{{ route('income.index') }}"
                                    style="text-decoration: none; color: black; display: block; padding: 10px; border-radius: 5px;">Income</a>
                            </li>
                        @endcan

                        @can('income-report')
                            <li style="margin-bottom: 10px;">
                                <a href="{{ route('income.report') }}"
                                    style="text-decoration: none; color: black; display: block; padding: 10px; border-radius: 5px;">Income
                                    Report</a>
                            </li>
                        @endcan

                        @can('ledger')
                            <li style="margin-bottom: 10px;">
                                <a href="{{ route('ledger.index') }}"
                                    style="text-decoration: none; color: black; display: block; padding: 10px; border-radius: 5px;">Ledger</a>
                            </li>
                        @endcan


                    </ul>
                </div>
            </div>

            <!-- Main content area -->
            <main role="main" class="col-md-9 col-lg-9 mt-3">
                @yield('content')
            </main>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
        integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous">
    </script>
</body>

</html>
