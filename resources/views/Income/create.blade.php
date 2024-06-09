@extends('welcome')

@section('content')
    <div style="max-width: 600px; margin: 0 auto;">
        <div style="background-color: #f0f0f0; padding: 20px;">
            <h2 style="text-align: center;">Create Income</h2>
        </div>

        @if ($errors->any())
            <ul class="alert alert-danger">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endif
        <div style="background-color: #fff; padding: 20px; border: 1px solid #ccc;">
            <form method="POST" action="{{ route('income.store') }}">
                @csrf
                <div style="margin-bottom: 20px;">
                    <label for="income_category_id" style="display: block; margin-bottom: 5px;">Income Category:</label>
                    <select id="income_category_id" name="income_category_id" class="form-control income_category"
                        style="width: 100%; padding: 8px;">
                        <option value="">Select income category</option>
                        @foreach ($incomeCategories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div style="margin-bottom: 20px;">
                    <label for="income_head_id" style="display: block; margin-bottom: 5px;">Income Head:</label>
                    <select id="income_head_id" name="income_head_id" class="form-control"
                        style="width: 100%; padding: 8px;">
                        <option value="">Select head</option>
                    </select>
                </div>
                <div style="margin-bottom: 20px;">
                    <label for="fund_category_id" style="display: block; margin-bottom: 5px;">Fund Category:</label>
                    <select id="fund_category_id" name="fund_category_id" class="form-control"
                        style="width: 100%; padding: 8px;">
                        <option value="">Select fund category</option>
                        @foreach ($fundCategories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div style="margin-bottom: 20px;">
                    <label for="name" style="display: block; margin-bottom: 5px;">Name:</label>
                    <input type="text" id="name" name="name" class="form-control"
                        style="width: 100%; padding: 8px;">
                </div>
                <div style="margin-bottom: 20px;">
                    <label for="company_name" style="display: block; margin-bottom: 5px;">Company Name:</label>
                    <input type="text" id="company_name" name="company_name" class="form-control"
                        style="width: 100%; padding: 8px;">
                </div>
                <div style="margin-bottom: 20px;">
                    <label for="email" style="display: block; margin-bottom: 5px;">Email:</label>
                    <input type="email" id="email" name="email" class="form-control"
                        style="width: 100%; padding: 8px;" required>
                </div>
                <div style="margin-bottom: 20px;">
                    <label for="phone_number" style="display: block; margin-bottom: 5px;">Phone Number:</label>
                    <input type="number" id="phone_number" name="phone_number" class="form-control"
                        style="width: 100%; padding: 8px;" required>
                </div>
                <div style="margin-bottom: 20px;">
                    <label for="amount" style="display: block; margin-bottom: 5px;">Amount:</label>
                    <input type="number" id="amount" name="amount" class="form-control"
                        style="width: 100%; padding: 8px;" required>
                </div>
                <div style="margin-bottom: 20px;">
                    <div style="margin-bottom: 20px;">
                        <label for="description" style="display: block; margin-bottom: 5px;">Description</label>
                        <textarea name="description" class="w-100" id="description" required></textarea>
                    </div>
                </div>
                <button type="submit"
                    style="background-color: #4CAF50; color: white; padding: 10px 20px; border: none; cursor: pointer; border-radius: 5px;">Create</button>
            </form>
        </div>
    </div>


    <script>
        $(document).ready(function() {
            $('#description').summernote();
        });
        $(document).on('change', '.income_category', function() {
            let incomeCategoryId = $(this).val();
            let incomeHead = $(this).find('#income_head_id');
            if (incomeCategoryId) {
                $.ajax({
                    url: '/income/incomeHead/' + incomeCategoryId,
                    type: 'GET',
                    success: function(data) {
                        console.log(data);
                        $('#income_head_id').empty();
                        $('#income_head_id').append('<option>Select income head</option>');
                        $.each(data, function(key, value) {
                            if (data.length > 0) {
                                $('#income_head_id').append('<option value="' + value.id +
                                    '">' + value.name + '</option>');
                            } else {
                                $('#income_head_id').append('<option>' + value + '</option>');
                            }

                        });
                    }
                })
            } else {
                $('#income_head_id').empty();
                $('#income_head_id').append('<option>Select income head</option>');
            }
        });
    </script>
@endsection
