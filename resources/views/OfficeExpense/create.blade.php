<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Office Expense</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body style="font-family: Arial, sans-serif;">
    @extends('welcome')
    @section('content')

    <div style="max-width: 600px; margin: 0 auto;">
        <div style="background-color: #f0f0f0; padding: 20px;">
            <h2 style="text-align: center;">Create Expense</h2>
        </div>

        @if(session('success'))
        <div>{{ session('success') }}</div>
        @endif

        <form action="/office_expenses" method="POST">
            @csrf
            <div style="margin-bottom: 20px;">
                <label for="expense_category" style="display: block; margin-bottom: 5px;">Expense Category:</label>
                <select id="expense_category" class="form-control" style="width: 100%; padding: 8px;" name="expense_category" required>
                    <option value="">Select a category</option>
                    @foreach($expenseCategories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div style="margin-bottom: 20px;">
                <label for="expense_head_category" style="display: block; margin-bottom: 5px;">Expense Head:</label>
                <select name="expense_head_category" id="expense_head_category" class="form-control" style="width: 100%; padding: 8px;" required>
                    <option value="">Select Head</option>
                </select>
            </div>
            <div style="margin-bottom: 20px;">
                <label for="fund_category" style="display: block; margin-bottom: 5px;">Fund Category:</label>
                <select id="fund_category" name="fund_category" class="form-control" style="width: 100%; padding: 8px;">
                    @foreach ($fundCategories as $fundCategory)
                    <option value="{{ $fundCategory->id }}">{{ $fundCategory->name }}</option>
                    @endforeach
                </select>
            </div>
            <div style="margin-bottom: 20px;">
                    <label for="amount" style="display: block; margin-bottom: 5px;">amount</label>
                    <input type="number" name="amount" id="amount" style="width: 100%; padding: 8px;" required>
                </div>
            <div style="margin-bottom: 20px;">
                <label for="description" style="display: block; margin-bottom: 5px;">Description</label>
                <textarea name="description" type="text" id="description" style="width: 100%; padding: 8px;" required></textarea>
            </div>
            <button type="submit" style="background-color: #4CAF50; color: white; padding: 10px 20px; border: none; cursor: pointer; border-radius: 5px;">Create</button>
        </form>
    </div>


    <script>
        $('#expense_category').change(function() {
            var categoryId = $(this).val();
            if (categoryId) {
                $.ajax({
                    url: '/officeExpense/' + categoryId,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        $('#expense_head_category').empty();
                        $('#expense_head_category').append('<option value="">Select Head</option>');
                        $.each(data, function(key, value) {
                            $('#expense_head_category').append('<option value="' + value.id + '">' + value.name + '</option>');
                        });
                    }
                });
            } else {
                $('#expense_head_category').empty();
                $('#expense_head_category').append('<option value="">Select Head</option>');
            }
        });
    </script>
 @endsection
</body>

</html>