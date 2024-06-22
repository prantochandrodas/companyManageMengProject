@extends('welcome')
@section('content')
    <div style="margin: 0 auto;">
        <div style="background-color: #f0f0f0; padding: 20px;">
            <h2 style="text-align: center;">Role : {{ $findRoal->name }}</h2>
        </div>
        <div style="background-color: #fff; padding: 20px; border: 1px solid #ccc;">
            <form method="POST" action="{{ route('role.give-permission', $findRoal->id) }}">
                @csrf
                @method('PUT')
                <div style="margin-bottom: 20px;">
                    <label>Permissions</label>

                    <div class="row">
                        @foreach ($permissions as $item)
                            <div class="col-4">
                                <label>
                                    <input type="checkbox" name="permission[]" id="permission"
                                         value="{{ $item->name }}"
                                         {{in_array($item->id,$rolesPermissions) ?'checked' :'' }}
                                         >{{ $item->name }}
                                        
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>
                <button type="submit" class="btn btn-success btn-sm">Update</button>
            </form>
        </div>
    </div>
@endsection
