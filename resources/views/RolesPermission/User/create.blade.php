@extends('welcome')
@section('content')
    <div style="max-width: 600px; margin: 0 auto;">
        <div style="background-color: #f0f0f0; padding: 20px;">
            <h2 style="text-align: center;">Create User</h2>
        </div>

        <div style="background-color: #fff; padding: 20px; border: 1px solid #ccc;">
            <form method="POST" action="{{ route('user.store') }}">
                @csrf
                <div style="margin-bottom: 20px;">
                    <label for="name" style="display: block; margin-bottom: 5px;">Name</label>
                    <input type="text" name="name" id="name" style="width: 100%; padding: 8px;" required>
                    @error('name')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div style="margin-bottom: 20px;">
                    <label for="email" style="display: block; margin-bottom: 5px;">Email</label>
                    <input type="email" name="email" id="email" style="width: 100%; padding: 8px;" required>
                    @error('email')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div style="margin-bottom: 20px;">
                    <label for="password" style="display: block; margin-bottom: 5px;">Password</label>
                    <input type="password" name="password" id="password" style="width: 100%; padding: 8px;" required>
                    @error('password')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div style="margin-bottom: 20px;">
                    <label for="Roles" style="display: block; margin-bottom: 5px;">Roles</label>
                    <select name="roles[]" id="" class="form-control" multiple>
                        <option>Select Roles</option>
                        @foreach ($roles as $item)
                            @auth
                                @if (Auth::user()->name == 'Admin' && $item->name == 'Super-Admin')
                                    <option value="{{ $item->name }}" class="d-none">{{ $item->name }}</option>
                                @else
                                    <option value="{{ $item->name }}">{{ $item->name }}</option>
                                @endif
                            @endauth
                        @endforeach
                    </select>
                </div>
                <button type="submit"
                    style="background-color: #4CAF50; color: white; padding: 10px 20px; border: none; cursor: pointer; border-radius: 5px;">Create</button>
            </form>
        </div>
    </div>
@endsection
