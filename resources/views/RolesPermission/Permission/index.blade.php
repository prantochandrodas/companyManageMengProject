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

    <h1>Permisions</h1>
    @can('add-permission')
        <a href="{{ route('permissions.create') }}" class="btn btn-primary btn-small mb-2">Add</a>
    @endcan
    <table id="expensesTable" class="table table-striped table-bordered" style="width:100%">
        <thead>
            <tr>
                <th>Serial.No</th>
                <th>Permissions</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($permissions as $permission)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $permission->name }}</td>
                    <td class="d-flex">
                        {{-- @can('edit-permission') --}}
                            <a href="{{ route('permissions.edit', $permission->id) }}"><button
                                    class="btn btn-success btn-sm me-2">Edit</button></a>
                        {{-- @endcan --}}

                        {{-- @can('delete-permission') --}}
                            <form action="{{ route('permissions.distroy', $permission->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm" type="submit">Delete</button>
                            </form>
                        {{-- @endcan --}}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
