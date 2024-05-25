<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Office Expense</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Add Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
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

        <form action="{{ route('officeExpense.store') }}" method="POST">
    @csrf
    <div id="expense-forms">
        <div class="expense-form row">
            <div class="col-4">
                <div style="margin-bottom: 20px;">
                    <label for="expense_category_0" style="display: block; margin-bottom: 5px;">Expense Category:</label>
                    <select id="expense_category_0" class="form-control expense_category" style="width: 100%; padding: 8px;" name="expense_category[]" required>
                        <option value="">Select a category</option>
                        @foreach($expenseCategories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-4">
                <div style="margin-bottom: 20px;">
                    <label for="expense_head_category_0" style="display: block; margin-bottom: 5px;">Expense Head:</label>
                    <select name="expense_head_category[]" class="expense_head_category form-control" style="width: 100%; padding: 8px;" required>
                        <option value="">Select Head</option>
                    </select>
                </div>
            </div>
            <div class="col-4">
                <div style="margin-bottom: 20px;">
                    <label for="fund_category_0" style="display: block; margin-bottom: 5px;">Fund Category:</label>
                    <select id="fund_category_0" name="fund_category[]" class="form-control" style="width: 100%; padding: 8px;">
                        <option value="">Select fund</option>
                        @foreach ($fundCategories as $fundCategory)
                        <option value="{{ $fundCategory->id }}">{{ $fundCategory->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-4">
                <div style="margin-bottom: 20px;">
                    <label for="amount_0" style="display: block; margin-bottom: 5px;">amount</label>
                    <input type="number" name="amount[]" id="amount_0" style="width: 100%; padding: 8px;" required>
                </div>
            </div>
            <div class="col-8">
                <div style="margin-bottom: 20px;">
                    <label for="description_0" style="display: block; margin-bottom: 5px;">Description</label>
                    <textarea name="description[]" id="description_0" style="width: 100%; padding: 8px;" required></textarea>
                </div>
            </div>
        </div>
    </div>
    <button class="btn btn-primary" id="add-more">Add More</button>
    <button type="submit" class="btn btn-success">Create</button>
</form>
    </div>

    <script>
        $(document).ready(function() {
            let expenseIndex = 1;

            $('#add-more').click(function() {
                let expenseForm = $('.expense-form:first').clone();
                expenseForm.find('input, select,textarea').each(function() {
                    let name = $(this).attr('name');
                    name = name.replace(/\d+/, expenseIndex);
                    $(this).attr('name', name);
                    $(this).val('');
                });

                // Check if remove button already exists before appending
                if (!expenseForm.find('.remove-expense-form').length) {
                    let removeButton = $('<button type="button" class="btn d-flex justify-content-center items-center col-1 mb-2 ml-3 btn-danger remove-expense-form" style="height:40px">X</button>');
                    removeButton.prependTo(expenseForm);
                }

                // Insert the cloned form after the last filled form
                $('.expense-form:last').after(expenseForm);
                expenseIndex++;
            });

            $(document).on('click', '.remove-expense-form', function() {
                $(this).closest('.expense-form').remove();
            });

            $(document).on('change', '.expense_category', function() {
                let expenseCategoryId = $(this).val();
                let expenseHeadCategory = $(this).closest('.expense-form').find('.expense_head_category');

                if (expenseCategoryId) {
                    $.ajax({
                        url: '/officeExpense/' + expenseCategoryId,
                        type: 'GET',
                        success: function(data) {
                            expenseHeadCategory.empty();
                            expenseHeadCategory.append('<option value="">Select Head Category</option>');
                            $.each(data, function(key, value) {
                                expenseHeadCategory.append('<option value="' + value.id + '">' + value.name + '</option>');
                            });
                        }
                    });
                } else {
                    expenseHeadCategory.empty();
                    expenseHeadCategory.append('<option value="">Select Head Category</option>');
                }
            });
        });
    </script>
    @endsection
</body>

</html>