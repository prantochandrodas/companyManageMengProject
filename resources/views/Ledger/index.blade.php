<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ledger</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body style="font-family: Arial, sans-serif;">
    @extends('welcome')
    @section('content')
        <!-- Success message -->
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <!-- Error message -->
        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <div class="container mt-4">
            <h1>Ledger</h1>
            <form action="{{ route('ledger.index') }}" method="GET" class="mb-4">
                <div class="row g-2">
                    <div class="col-md-4">
                        <label for="fromDate" class="form-label">From Date</label>
                        <input type="date" class="form-control" id="fromDate" name="fromDate"
                            value="{{ request('fromDate') }}">
                    </div>
                    <div class="col-md-4">
                        <label for="toDate" class="form-label">To Date</label>
                        <input type="date" class="form-control" id="toDate" name="toDate"
                            value="{{ request('toDate') }}">
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary">Filter</button>
                    </div>
                </div>
            </form>

            <div class="d-flex">

                {{-- excel download button  --}}

                @can('ledger-excel')
                <a href="{{ route('ledger.export', ['fromDate' => request('fromDate'), 'toDate' => request('toDate'), 'hi' => 'helo']) }}"
                    class="btn btn-primary btn-small mb-2">Download Excel</a>
                @endcan
                {{-- pdf download btn  --}}
                @can('ledger-pdf')
                <a href="{{ route('ledger.pdf', ['fromDate' => request('fromDate'), 'toDate' => request('toDate')]) }}"
                    class="btn btn-primary btn-small mb-2 mx-2">PDF</a>
                @endcan
                
                @can('ledger-pdf')
                <a href=""><button type="submit" class="btn btn-success btn-small print">Print</button></a>
                @endcan
            </div>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Serial No</th>
                        <th>Date</th>
                        <th>Description</th>
                        <th>Type</th>
                        <th>Debit</th>
                        <th>Credit</th>
                        <th>Balance</th>
                    </tr>
                </thead>

                @if (isset($ledgerEntries))
                    @if ($ledgerEntries->isEmpty())
                        <p>No records found</p>
                    @else
                        <tbody>
                            @php
                                $totalDebit = 0; // Initialize total debit variable
                                $totalCrebit = 0; // Initialize total debit variable
                                $totalBalance = $setOpeningAmount;
                            @endphp
                            {{-- <p>{{$totalBalance}}</p> --}}
                            @foreach ($ledgerEntries as $entry)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ date('Y-m-d', strtotime($entry->date)) }}</td>
                                    <td>{!! $entry->description !!}</td>
                                    <td>{{ $entry->type }}</td>
                                    <td>{{ number_format($entry->debit) }}</td>
                                    <td>{{ number_format($entry->credit) }}</td>
                                    <td>{{ number_format($entry->balance) }}</td>

                                    @php
                                        $totalDebit += $entry->debit; // Add debit amount to total
                                        $totalCrebit += $entry->credit;

                                        $totalBalance += $entry->debit -$entry->credit;
                                    @endphp
                                </tr>
                            @endforeach
                            <tr>
                                <td>
                                    {{ count($ledgerEntries) + 1 }}
                                </td>
                                <td colspan="3">Total</td>
                                <td>{{ $totalDebit }}</td>
                                <td>{{ $totalCrebit }}</td>
                                <td>{{ $totalBalance }}</td>
                            </tr>
                            <tr>
                                <td>
                                    {{ count($ledgerEntries) + 2 }}
                                </td>
                                <td colspan="3">Summary</td>
                                <td colspan="3">
                                    <span style="font-weight:600; font-size:15px">Opening Balance :</span>
                                    {{ $setOpeningAmount }} <br>
                                    <span style="font-weight:600; font-size:15px">Total Debit :</span> {{ $totalDebit }}
                                    <br>
                                    <span style="font-weight:600; font-size:15px">Total credit :</span> {{ $totalCrebit }}
                                    <br>
                                    <span style="font-weight:600; font-size:15px">Final Balance :</span>
                                    {{ $totalBalance }}
                                </td>
                            </tr>
                        </tbody>
                    @endif
                @endif
            </table>
            <!-- Modal -->
            <div class="modal fade" id="viewModal" tabindex="-1" role="dialog" aria-labelledby="viewModalLabel"
                aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="viewModalLabel">Ledger Details</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p><strong>Type:</strong> <span id="type"></span></p>
                            <p><strong>Description:</strong> <span id="description"></span></p>
                            <p><strong>Credit:</strong> <span id="credit"></span></p>
                            <p><strong>Debit:</strong> <span id="debit"></span></p>
                            <p><strong>Balance:</strong> <span id="balance"></span></p>
                            <p><strong>Date:</strong> <span id="date"></span></p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script>
            $(document).ready(function() {
                // When the view button is clicked
                $(".view").click(function() {
                    var entryDate = $(this).data("date"); // Get the ID of the clicked entry
                    var entryType = $(this).data("type"); // Get the type of the clicked entry
                    var entryDebit = $(this).data("debit"); // Get the type of the clicked entry
                    var entryCredit = $(this).data("credit"); // Get the type of the clicked entry
                    var entryBalance = $(this).data("balance"); // Get the type of the clicked entry
                    var entryDescription = $(this).data("des"); // Get the type of the clicked entry


                    var queryString = $.param({
                        type: entryType,
                        date: entryDate,
                        debit: entryDebit,
                        credit: entryCredit,
                        balance: entryBalance,
                        description: entryDescription
                    });
                    $.ajax({
                        url: "/ledger/view?" + queryString,
                        type: "GET",
                        success: function(data) {
                            $('#type').text(data.type);
                            $('#description').text(data.description);
                            $('#debit').text(data.debit);
                            $('#credit').text(data.credit);
                            $('#balance').text(data.balance);
                            $('#date').text(new Date(data.date).toLocaleDateString());
                            $('#viewModal').modal('show');
                        },
                        error: function(xhr, status, error) {
                            console.error(xhr.responseText);
                        }
                    });
                });

                $(document).on('click', '.print', function() {
                    var url = '{{ route('ledger.print') }}';
                    var fromDate = '{{ request('fromDate') }}';
                    var toDate = '{{ request('toDate') }}';
                    var printWindow = window.open(url + '?fromDate=' + fromDate + '&toDate=' + toDate,
                        '_blank');
                    printWindow.onload = function() {
                        printWindow.print();
                    };
                });
            });
        </script>
    @endsection
</body>

</html>
