@extends('welcome')
@section('content')
    <!-- sucess message   -->

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    {{-- @can('add-fund') --}}
        <a href="{{ route('fundCategory.create') }}"><button type="button" class="btn btn-primary my-4">Add</button></a>
    {{-- @endcan --}}

    <table id="fundCategory" class="display" style="width:100%">
        <thead>
            <tr>
                <th>Serial.No</th>
                <th>Name</th>
                <th>Opening Amount</th>
                <th>Added Amount</th>
                <th>Income Amount</th>
                <th>Expensed Amount</th>
                <th>Total Amount</th>
            </tr>
        </thead>
    </table>

    <script>
        $(document).ready(function() {
            $('#fundCategory').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('fundsCategory.get') }}',
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
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'openingAmount',
                        name: 'openingAmount',
                        render: function(data, type, row) {
                            return parseInt(data, 10);
                        }
                    },
                    {
                        data: 'addedFundAmount',
                        name: 'addedFundAmount',
                        render: function(data, type, row) {
                            return parseInt(data, 10);
                        }
                    },
                    {
                        data: 'incomeAmount',
                        name: 'incomeAmount',
                        render: function(data, type, row) {
                            return parseInt(data, 10);
                        }
                    },
                    {
                        data: 'expensedAmount',
                        name: 'expensedAmount',
                        render: function(data, type, row) {
                            return parseInt(data, 10);
                        }
                    },
                    {
                        data: 'total',
                        name: 'total',
                        render: function(data, type, row) {
                            return parseInt(data, 10);
                        }
                    }
                ]
            });

        });
    </script>
@endsection
