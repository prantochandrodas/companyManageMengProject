@extends('welcome')

@section('content')

    <!-- error message  -->
    @if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
    @endif
    <div style="max-width: 600px; margin: 0 auto;">
        <div style="background-color: #f0f0f0; padding: 20px;">
            <h2 style="text-align: center;">Create Income with excel file</h2>
        </div>

        @if ($errors->any())
            <ul class="alert alert-danger">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endif
        <div style="background-color: #fff; padding: 20px; border: 1px solid #ccc;">
            <form method="POST" action="{{ route('income.StoreExcel') }}"  enctype="multipart/form-data">
                @csrf
                <label for="excel" class="d-block" style="font-size:25px; font-weight:500">Excel Import:</label>
                <div style="margin-bottom: 20px;" class="input-group">
                    <input type="file" name="file" accept=".xlsx" class="form-control">
                    <button type="submit" class="btn" style="background-color: #4CAF50; color: white">Create</button>
                </div>
            </form>
        </div>
    </div>

@endsection
