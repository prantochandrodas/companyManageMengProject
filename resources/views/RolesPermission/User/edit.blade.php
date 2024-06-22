@extends('welcome')
@section('content')
    <div style="max-width: 600px; margin: 0 auto;">
        <div style="background-color: #f0f0f0; padding: 20px;">
            <h2 style="text-align: center;">Update User</h2>
        </div>
        <div style="background-color: #fff; padding: 20px; border: 1px solid #ccc;">
            <form method="POST" action="{{ route('user.update', $findData->id) }}">
                @csrf
                @method('PUT')
                <div style="margin-bottom: 20px;">
                    <label for="name" style="display: block; margin-bottom: 5px;">Name</label>
                    <input type="text" name="name" id="name" value="{{ $findData->name }}"
                        style="width: 100%; padding: 8px;">
                    @error('name')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div style="margin-bottom: 20px;">
                    <label for="email" style="display: block; margin-bottom: 5px;">Email</label>
                    <input type="email" readonly name="email" id="email" value="{{ $findData->email }}"
                        style="width: 100%; padding: 8px;">
                    @error('email')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div style="margin-bottom: 20px;">
                    <label for="password" style="display: block; margin-bottom: 5px;">Password</label>
                    <input type="password" name="password" id="password" style="width: 100%; padding: 8px;">
                    @error('password')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div style="margin-bottom: 20px;">
                    <label for="Roles" style="display: block; margin-bottom: 5px;">Roles</label>
                    <select name="roles[]" id="" class="form-select form-select-sm" multiple>
                        <option>Select Roles</option>
                        @foreach ($roles as $item)
                            <option value="{{ $item->name }}" {{ in_array($item->name, $userRoles) ? 'selected' : '' }}>
                                {{ $item->name }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit"
                    style="background-color: #4CAF50; color: white; padding: 10px 20px; border: none; cursor: pointer; border-radius: 5px;">Update</button>
            </form>
        </div>
    </div>
@endsection
