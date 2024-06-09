
    @extends('welcome')
    @section('content')
    <!-- success message  -->
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

    <a href="{{ route('income.create') }}"><button type="button" class="btn btn-primary my-4">Add</button></a>
    <a href="{{ route('income.import') }}"><button type="button" class="btn btn-primary my-4">Excel Import</button></a>
    <table id="mydata" class="display" style="width:100%">
        <thead>
            <tr>
                <th>SL.No</th>
                <th>Income Category</th>
                <th>Income Head</th>
                <th>Fund</th>
                <th>Name</th>
                <th>Description</th>
                <th>Amount</th>
                <th>Date</th>
                <th>Action</th>
            </tr>
        </thead>
    </table>

    <!-- Modal -->
    <div class="modal fade" id="viewModal" tabindex="-1" role="dialog" aria-labelledby="viewModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewModalLabel">Expense Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p><strong>Income Category:</strong> <span id="incomeCategory"></span></p>
                    <p><strong>Income Head:</strong> <span id="incomeHeadCategory"></span></p>
                    <p><strong>Fund Category:</strong> <span id="fundCategory"></span></p>
                    <p><strong>Name:</strong> <span id="name"></span></p>
                    <p><strong>Company Name:</strong> <span id="companyName"></span></p>
                    <p><strong>Email:</strong> <span id="email"></span></p>
                    <p><strong>Phone Number:</strong> <span id="phone_number"></span></p>
                    <p><strong>Description:</strong> <span id="description"></span></p>
                    <p><strong>Amount:</strong> <span id="amount"></span></p>
                    <p><strong>Date:</strong> <span id="date"></span></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $('#mydata').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route("income.getincome") }}',
                columns: [{
                        data: null, // Use null to signify that this column does not map directly to any data source
                        name: 'serial_number',
                        render: function(data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1; // Calculate the serial number
                        },
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'income_category',
                        name: 'income_category'
                    },
                    {
                        data: 'income_head',
                        name: 'income_head'
                    },
                    {
                        data: 'fund_category',
                        name: 'fund_category'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'description',
                        name: 'description',
                        render: function(data, type, row) {
                            return data ? $('<div/>').html(data).text() : ''; // This will display the HTML content correctly
                        }
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
                    url: '/income/view/' + id,
                    type: 'GET',
                    success: function(data) {
                        // console.log(data);
                        if (data.error) {
                            alert(data.error);
                        } else {
                            console.log(data);
                            $('#incomeCategory').text(data.income_category_id);
                            $('#incomeHeadCategory').text(data.income_head_id);
                            $('#fundCategory').text(data.fund_category_id);
                            $('#name').text(data.name);
                            $('#companyName').text(data.company_name == null ? "No company name found" : data.company_name);
                            $('#phone_number').text(data.phone_number);
                            $('#email').text(data.email);
                            $('#description').html(data.description);
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
                var url = '/income/print/' + id; // Replace with your actual print page URL

                // Open the print page in a new window and trigger the print dialog
                var printWindow = window.open(url, '_blank');
                printWindow.onload = function() {
                    printWindow.print();
                };
            });
        });
    </script>
    @endsection
