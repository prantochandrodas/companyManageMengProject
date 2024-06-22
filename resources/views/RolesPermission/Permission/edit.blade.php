@extends('welcome')
@section('content')
    <div style="max-width: 600px; margin: 0 auto;">
        <div style="background-color: #f0f0f0; padding: 20px;">
            <h2 style="text-align: center;">Create Permission</h2>
        </div>
        <div style="background-color: #fff; padding: 20px; border: 1px solid #ccc;">
            <form method="POST" action="{{route('permissions.update',$findData->id) }}">
                @csrf
                @method('PUT')
                <div style="margin-bottom: 20px;">
                    <label for="name" style="display: block; margin-bottom: 5px;">Name</label>
                    <input type="text" name="name" id="name" style="width: 100%; padding: 8px;" value="{{$findData->name}}" required>
                </div>
                <button type="submit" class="btn btn-success btn-sm">Update</button>
            </form>
        </div>
    </div>
@endsection
