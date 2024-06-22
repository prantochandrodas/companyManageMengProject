<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Expense Heads</title>
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body style="font-family: Arial, sans-serif;">
    @extends('welcome')
    @section('content')
        <!-- success message  -->
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <!-- error message  -->
        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
        @can('add_posts')
            <a href="{{ route('officeExpense.create') }}"><button type="button" class="btn btn-primary my-4">Add</button></a>
        @endcan
        <table id="mydata" class="display" style="width:100%">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Expense Category</th>
                    <th>Expense Head</th>
                    <th>Used Fund</th>
                    <th>Description</th>
                    <th>Amount</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>
        </table>

        <div class="modal fade" id="viewModal" tabindex="-1" aria-labelledby="viewModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="viewModalLabel">Expense Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p><strong>Expense Category:</strong> <span id="expenseCategory"></span></p>
                        <p><strong>Expense Head:</strong> <span id="expenseHeadCategory"></span></p>
                        <p><strong>Fund Category:</strong> <span id="fundCategory"></span></p>
                        <p><strong>Description:</strong> <span id="description"></span></p>
                        <p><strong>Amount:</strong> <span id="amount"></span></p>
                        <p><strong>Date:</strong> <span id="date"></span></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        <script>
            $(document).ready(function() {
                $('#mydata').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: '{{ route('officeExpense.get') }}',
                    columns: [{
                            data: null, // Use null to signify that this column does not map directly to any data source
                            name: 'serial_number',
                            render: function(data, type, row, meta) {
                                return meta.row + meta.settings._iDisplayStart +
                                    1; // Calculate the serial number
                            },
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'expense_category',
                            name: 'expense_category'
                        },
                        {
                            data: 'expenseHead_category',
                            name: 'expenseHead_category'
                        },
                        {
                            data: 'fund_category',
                            name: 'fund_category'
                        },
                        {
                            data: 'description',
                            name: 'description'
                        },
                        {
                            data: 'amount',
                            name: 'amount',
                            render: function(data, type, row) {
                                return parseInt(data, 10);
                            }
                        },
                        {
                            data: 'created_at',
                            name: 'created_at',
                            render: function(data, type, row) {
                                return new Date(data).toLocaleDateString();
                            }
                        },
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false,
                            render: function(data, type, row) {
                                return '<div class="btn-group">' + data + '</div>';
                            }
                        }
                    ]
                });

                // Handle the view button click
                $(document).on('click', '.view', function() {
                    var id = $(this).data('id');
                    $.ajax({
                        url: '/officeExpenseView/' + id,
                        type: 'GET',
                        success: function(data) {
                            // console.log(data);
                            if (data.error) {
                                alert(data.error);
                            } else {
                                console.log(data);
                                $('#expenseCategory').text(data.expenseCategory);
                                $('#expenseHeadCategory').text(data.expenseHeadCategory);
                                $('#fundCategory').text(data.fundCategory);
                                $('#description').text(data.description);
                                $('#amount').text(data.amount);
                                $('#date').text(new Date(data.created_at).toLocaleDateString());
                                $('#viewModal').modal('show');
                            }
                        },
                        error: function() {
                            alert('Error fetching data.');
                        }
                    });
                });

                $(document).on('click', '.print', function() {
                    var id = $(this).data('id');
                    var url = '/office-expense/print/' + id; // Replace with your actual print page URL

                    // Open the print page in a new window and trigger the print dialog
                    var printWindow = window.open(url, '_blank');
                    printWindow.onload = function() {
                        printWindow.print();
                    };
                });
            });
        </script>
    @endsection
</body>

</html>
