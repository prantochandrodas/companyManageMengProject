@extends('welcome')
@section('content')
    <div style="max-width: 600px; margin: 0 auto;">
        <div style="background-color: #f0f0f0; padding: 20px;">
            <h2 style="text-align: center;">Addjust Fund</h2>
        </div>
        <div style="background-color: #fff; padding: 20px; border: 1px solid #ccc;">
            <form method="POST" action="{{ route('create_fund.create') }}">
                @csrf
                <div style="margin-bottom: 20px;">
                    <label for="category_id" style="display: block; margin-bottom: 5px;">Category Name:</label>
                    <select id="category_id" name="category_id" class="form-control" style="width: 100%; padding: 8px;">
                        @foreach ($funds as $fund)
                            <option value="{{ $fund->id }}">{{ $fund->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div style="margin-bottom: 20px;">
                    <label for="Description" style="display: block; margin-bottom: 5px;">Fund Description:</label>
                    <textarea name="Description" type="text" id="Description" style="width: 100%; padding: 8px;" required></textarea>
                </div>
                <div style="margin-bottom: 20px;">
                    <label for="amount" style="display: block; margin-bottom: 5px;">Amount:</label>
                    <input type="number" id="amount" name="amount" class="form-control"
                        style="width: 100%; padding: 8px;" required>
                </div>
                <button type="submit"
                    style="background-color: #4CAF50; color: white; padding: 10px 20px; border: none; cursor: pointer; border-radius: 5px;">Create</button>
            </form>
        </div>
    </div>
@endsection
