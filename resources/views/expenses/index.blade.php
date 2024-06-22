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

    {{-- @can('add-posts') --}}
        <a href="{{ route('expense.create') }}"><button type="button" class="btn btn-primary my-4">Add</button></a>
    {{-- @endcan --}}

    <table id="mydata" class="display" style="width:100%">
        <thead>
            <tr>
                <th>Serial.No</th>
                <th>Name</th>
                @can('delete_posts')
                    <th>Action</th>
                @endcan
            </tr>
        </thead>
    </table>
    <script>
        $(document).ready(function() {
            $('#mydata').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('expensesCategory.get') }}',
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
                    @can('delete_posts')
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false
                        }
                    @endcan
                ]
            });
        });
    </script>
@endsection
