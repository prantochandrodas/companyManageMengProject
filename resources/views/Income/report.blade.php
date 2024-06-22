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

    <div>
        <!-- <div style="background-color: #f0f0f0; padding: 20px;">
                    <h2 style="text-align: center;">Filter Income Report</h2>
                </div> -->
        <form style="background-color: #f0f0f0; padding:20px; margin:20px" action="{{ route('income.filter') }}">
            <h2>Filter Income Data</h2>
            @csrf
            <div class="row">
                <div style="margin-bottom: 20px;" class="col-4">
                    <label for="fund_category_id" style="display: block; margin-bottom: 5px;">Fund Category Name:</label>
                    <select id="fund_category_id" name="fund_category_id" class="form-control"
                        style="width: 100%; padding: 8px;">
                        <option value="">Select fund</option>
                        @foreach ($funds as $fund)
                            <option value="{{ $fund->id }}"
                                {{ request('fund_category_id') == $fund->id ? 'selected' : '' }}>{{ $fund->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div style="margin-bottom: 20px;" class="col-4">
                    <label for="income_category_id" style="display: block; margin-bottom: 5px;">Income Category
                        Name:</label>
                    <select id="income_category_id" name="income_category_id" class="form-control"
                        style="width: 100%; padding: 8px;">
                        <option value="">Select Income Category</option>
                        @foreach ($incomeCategories as $category)
                            <option value="{{ $category->id }}"
                                {{ request('income_category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div style="margin-bottom: 20px;" class="col-4">
                    <label for="income_head_id" style="display: block; margin-bottom: 5px;">Income Head:</label>
                    <select id="income_head_id" name="income_head_id" class="form-control"
                        style="width: 100%; padding: 8px;">
                        <option value="">Select Income head</option>
                    </select>
                </div>
                <div style="margin-bottom: 20px;" class="col-4">
                    <label for="formDate" style="display: block; margin-bottom: 5px;">Form date:</label>
                    <input type="date" id="formDate" name="formDate"
                        value="{{ request('formDate') ?: now()->format('Y-m-d') }}" class="form-control"
                        style="width: 100%; padding: 8px;">
                </div>
                <div style="margin-bottom: 20px;" class="col-4">
                    <label for="toDate" style="display: block; margin-bottom: 5px;">To date:</label>
                    <input type="date" id="toDate" name="toDate" class="form-control"
                        style="width: 100%; padding: 8px;" value="{{ request('toDate') }}">
                </div>
            </div>
            <button type="submit"
                style="background-color: #4CAF50; color: white; padding: 10px 20px; border: none; cursor: pointer; border-radius: 5px;">Filter</button>
        </form>

        @if (isset($incomes))
            @if ($incomes->isEmpty())
                <p>No records found</p>
            @else
                <table id="expensesTable" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Income Category</th>
                            <th>Expense Head</th>
                            <th>Fund Category</th>
                            <th>Description</th>
                            <th>Amount</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($incomes as $income)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $income->incomeCategory->name }}</td>
                                <td>
                                    @if ($income->incomeHead)
                                        {{ $income->incomeHead->name }}
                                    @else
                                        No income Head
                                    @endif
                                </td>
                                <td>{{ $income->fundCategory->name }}</td>
                                <td>{!! $income->description !!}</td>
                                <td>{{ $income->amount }}</td>
                                <td>{{ $income->created_at->format('Y-m-d') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        @endif
    </div>
    <script>
        $(document).ready(function() {
            $('#expensesTable').DataTable();

            function loadExpenseHeads() {
                const incomeCategoryId = $('#income_category_id').val();
                if (incomeCategoryId) {
                    $.ajax({
                        url: '/income/incomeHead/' + incomeCategoryId,
                        type: 'GET',
                        success: function(data) {
                            let options = '<option value="">Select Head</option>';
                            data.forEach(function(head) {
                                options +=
                                    `<option value="${head.id}" ${head.id == '{{ request('income_head_id') }}' ? 'selected' : ''}>${head.name}</option>`;
                            });
                            $('#income_head_id').html(options);
                        }
                    });
                } else {
                    $('#income_head_id').html('<option value="">Select Head</option>');
                }
            }

            $('#income_category_id').change(loadExpenseHeads);

            @if (request('income_category_id'))
                loadExpenseHeads();
            @endif
        });
    </script>
@endsection
