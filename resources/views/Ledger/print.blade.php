<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ledger</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body style="font-family: Arial, sans-serif;">
        <div class="mt-4">
            <h1>Ledger</h1>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Serial No</th>
                        <th>Date</th>
                        <th>Description</th>
                        <th>Type</th>
                        <th>Debit</th>
                        <th>Credit</th>
                        <th>Balance</th>
                    </tr>
                </thead>

                @if (isset($ledgerEntries))
                    @if ($ledgerEntries->isEmpty())
                        <p>No records found</p>
                    @else
                        <tbody>
                            @php
                                $totalDebit = 0; // Initialize total debit variable
                                $totalCrebit = 0; // Initialize total debit variable
                                $totalBalance = $setOpeningAmount;
                            @endphp
                            {{-- <p>{{$totalBalance}}</p> --}}
                            @foreach ($ledgerEntries as $entry)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ date('Y-m-d', strtotime($entry->date)) }}</td>
                                    <td>{!! $entry->description !!}</td>
                                    <td>{{ $entry->type }}</td>
                                    <td>{{ number_format($entry->debit) }}</td>
                                    <td>{{ number_format($entry->credit) }}</td>
                                    <td>{{ number_format($entry->balance) }}</td>

                                    @php
                                        $totalDebit += $entry->debit; // Add debit amount to total
                                        $totalCrebit += $entry->credit;

                                        $totalBalance += $entry->debit - $entry->credit;
                                    @endphp
                                </tr>
                            @endforeach
                            <tr>
                                <td>
                                    {{ count($ledgerEntries) + 1 }}
                                </td>
                                <td colspan="3">Total</td>
                                <td>{{ $totalDebit }}</td>
                                <td>{{ $totalCrebit }}</td>
                                <td>{{ $totalBalance }}</td>
                            </tr>
                            <tr>
                                <td>
                                    {{ count($ledgerEntries) + 2 }}
                                </td>
                                <td colspan="3">Summary</td>
                                <td colspan="3">
                                    <span style="font-weight:600; font-size:15px">Opening Balance :</span>
                                    {{ $setOpeningAmount }} <br>
                                    <span style="font-weight:600; font-size:15px">Total Debit :</span> {{ $totalDebit }}
                                    <br>
                                    <span style="font-weight:600; font-size:15px">Total credit :</span> {{ $totalCrebit }}
                                    <br>
                                    <span style="font-weight:600; font-size:15px">Final Balance :</span>
                                    {{ $totalBalance }}
                                </td>
                            </tr>
                        </tbody>
                    @endif
                @endif
            </table>
        </div>
</body>

</html>
