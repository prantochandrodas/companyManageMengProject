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
    <h1>User</h1>
    {{-- @can('create_user') --}}
    <a href="{{ route('user.create') }}" class="btn btn-primary btn-small mb-2">Add</a>
    {{-- @endcan --}}
    <table id="expensesTable" class="table table-striped table-bordered" style="width:100%">
        <thead>
            <tr>
                <th>Serial.No</th>
                <th>Name</th>
                <th>Email</th>
                <th>Roles</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>
                        @if (!@empty($user->getRoleNames()))
                            @foreach ($user->getRoleNames() as $item)
                                <label class="badge bg-primary">{{ $item }}</label>
                            @endforeach
                        @endif
                    </td>
                    @php
                        $array = $user->getRoleNames()->toArray();
                        $valueToSearch = 'Super-Admin';
                        $userSearch = 'Admin';
                        $key = array_search($valueToSearch, $array);
                        $key2 = array_search($userSearch, $array);
                    @endphp
                    @if ($key !== false)
                        @can('delete-superadmin')
                            <td class="d-flex">
                                <a href="{{ route('user.edit', $user->id) }}"><button
                                        class="btn btn-success btn-sm me-2">Edit</button></a>
                                <form action="{{ route('user.distroy', $user->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm" type="submit">Delete</button>
                                </form>
                            </td>
                        @endcan
                    @elseif($key2 !== false)
                        @can('delete-admin')
                            <td class="d-flex">
                                <a href="{{ route('user.edit', $user->id) }}"><button
                                        class="btn btn-success btn-sm me-2">Edit</button></a>
                                <form action="{{ route('user.distroy', $user->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm" type="submit">Delete</button>
                                </form>
                            </td>
                        @endcan
                    @else
                        @can('delete-user')
                            <td class="d-flex">
                                <a href="{{ route('user.edit', $user->id) }}"><button
                                        class="btn btn-success btn-sm me-2">Edit</button></a>
                                <form action="{{ route('user.distroy', $user->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm" type="submit">Delete</button>
                                </form>
                            </td>
                        @endcan
                    @endif


                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
