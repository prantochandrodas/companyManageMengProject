@extends('welcome')
@section('content')
    <!-- sucess message  -->
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

    @can('add-fundAdjustment')
        <a href="{{ route('fundCreate.page') }}"><button type="button" class="btn btn-primary my-4">Add</button></a>
    @endcan

    <table id="mydata" class="display" style="width:100%">
        <thead>
            <tr>
                <th>Serial.No</th>
                <th>Description</th>
                <th>Fund</th>
                <th>Amount</th>
                <!-- <th>Action</th> -->
            </tr>
        </thead>
    </table>
    <script>
        $(document).ready(function() {
            $('#mydata').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('fundslist.get') }}',
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
                        data: 'Description',
                        name: 'Description'
                    },
                    {
                        data: 'category_name',
                        name: 'category_name'
                    },
                    {
                        data: 'amount',
                        name: 'amount'
                    },
                    // { data: 'action', name: 'action', orderable: false, searchable: false }
                ]
            });
        });
    </script>
@endsection
