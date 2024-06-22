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
    <h1>Roles</h1>
    @can('add-role')
        <a href="{{ route('role.create') }}" class="btn btn-primary btn-small mb-2">Add</a>
    @endcan
    <table id="expensesTable" class="table table-striped table-bordered" style="width:100%">
        <thead>
            <tr>
                <th>Serial.No</th>
                <th>User Role</th>
                <th>Action</th>
                <th>Role Permission</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($Roles as $role)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $role->name }}</td>
                    @if ($role->name == 'Super-Admin')
                        {{-- @can('delete-superadmin') --}}
                            <td class="d-flex">
                                <a href="{{ route('role.edit', $role->id) }}"><button
                                        class="btn btn-success btn-sm me-3">Edit</button></a>
                                <form action="{{ route('role.distroy', $role->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm" type="submit">Delete</button>
                                </form>
                            </td>
                        {{-- @endcan --}}
                    @else
                        <td class="d-flex">
                            <a href="{{ route('role.edit', $role->id) }}"><button
                                    class="btn btn-success btn-sm me-3">Edit</button></a>
                            <form action="{{ route('role.distroy', $role->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm" type="submit">Delete</button>
                            </form>
                        </td>
                    @endif

                    @if ($role->name == 'Super-Admin')
                        {{-- @can('assign-permission-super-admin') --}}
                            <td><a href="{{ route('role.add-permission', $role->id) }}" class="btn btn-success btn-sm">Add</a>
                            </td>
                        {{-- @endcan --}}
                    @elseif($role->name == 'Admin')
                        {{-- @can('assign-permission-to-admin') --}}
                            <td><a href="{{ route('role.add-permission', $role->id) }}" class="btn btn-success btn-sm">Add</a>
                            </td>
                        {{-- @endcan --}}
                    @else
                        <td><a href="{{ route('role.add-permission', $role->id) }}" class="btn btn-success btn-sm">Add</a>
                        </td>
                    @endif
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
