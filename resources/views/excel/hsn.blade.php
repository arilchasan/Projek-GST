<head>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>B2B Page</title>
</head>

<body>
    <div class="mx-1">
        <div class="d-flex justify-content-between align-items-center mb-3 p-4">
            <h3 class="flex-grow-1">Export HSN Data</h3>
            <a href="{{ route('export.hsn', ['data' => $filename]) }}" class="btn btn-success">Export</a>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered" id="hsn">
                <thead>
                    <tr>
                        <th style="background-color: #0080ff; color: #ffffff;">HSN Code</th>
                        <th style="background-color: #0080ff; color: #ffffff;">Description</th>
                        <th style="background-color: #0080ff; color: #ffffff;">UQC</th>
                        <th style="background-color: #0080ff; color: #ffffff;">Total Quantity</th>
                        <th style="background-color: #0080ff; color: #ffffff;">Total Value</th>
                        <th style="background-color: #0080ff; color: #ffffff;">Taxable Value</th>
                        <th style="background-color: #0080ff; color: #ffffff;">Integrated Tax Amount</th>
                        <th style="background-color: #0080ff; color: #ffffff;">Central Tax Amount</th>
                        <th style="background-color: #0080ff; color: #ffffff;">State/UT Tax Amount</th>
                        <th style="background-color: #0080ff; color: #ffffff;">Cess Amount</th>
                        <th style="background-color: #0080ff; color: #ffffff;">Rate</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Isi kolom dengan data -->
                </tbody>
            </table>

        </div>
        <div class="table-responsive mx-5">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th style="width: 10%;background-color: #0080ff;color: #ffffff; ">Total Value</th>
                        <th style="width: 10%;background-color: #0080ff;color: #ffffff; ">Total Taxable Tax</th>
                        <th style="width: 10%;background-color: #0080ff;color: #ffffff; ">Total Integrated Tax</th>
                        <th style="width: 10%;background-color: #0080ff;color: #ffffff; ">Total Central Tax</th>
                        <th style="width: 10%;background-color: #0080ff;color: #ffffff; ">Total State/UT Tax</th>
                        <th style="width: 10%;background-color: #0080ff;color: #ffffff; ">Total CESS</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $totalValue }}</td>
                        <td>{{ $taxableAmount }}</td>
                        <td>{{ $integratedTaxAmount }}</td>
                        <td>{{ $centralTaxAmount }}</td>
                        <td>{{ $stateTaxAmount }}</td>
                        <td>{{ $cessAmount }}</td>
                    </tr>

                </tbody>
            </table>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#hsn').DataTable({
                "processing": true,
                order: [
                    [0, 'asc']
                ],
                "serverSide": true,
                "ajax": "{{ route('show.hsn', ['filename' => $filename]) }}",
                pagingType: 'numbers',
                "language": {
                    "search": "Search:",
                    "searchPlaceholder": "Search",
                    "emptyTable": "No data available",
                    "lengthMenu": "Show _MENU_ entries",
                    "zeroRecords": "No matching records found",
                    "info": "Showing _START_ to _END_ of _TOTAL_ entries",
                    "infoEmpty": "Showing 0 to 0 of 0 entries",
                    "infoFiltered": "(filtered from _MAX_ total entries)"
                },
                "columns": [{
                        "data": "HSN Code",
                        "name": "HSN Code"
                    },
                    {
                        "data": "Description",
                        "name": "Description"
                    },
                    {
                        "data": "UQC",
                        "name": "UQC",
                        "render": function(data, type, row, meta) {
                            return `<td style="background-color: #0080ff; color: #ffffff;">${data}</td>`;
                        }
                    },
                    {
                        "data": "Total Quantity",
                        "name": "Total Quantity"
                    },
                    {
                        "data": "Total Value",
                        "name": "Total Value",


                    },
                    {
                        "data": "Taxable Value",
                        "name": "Taxable Value",


                    },
                    {
                        "data": "Integrated Tax Amount",
                        "name": "Integrated Tax Amount",
                        "render": function(data, type, row, meta) {
                            if (type === 'display' && data !== null && data !== undefined) {
                                let numericValue = parseFloat(data);
                                let formattedValue = numericValue.toFixed(2);
                                return `<td>${formattedValue}</td>`;
                            } else {
                                return `<td>${data}</td>`;
                            }
                        }
                    },
                    {
                        "data": "Central Tax Amount",
                        "name": "Central Tax Amount",

                    },
                    {
                        "data": "State/UT Tax Amount",
                        "name": "State/UT Tax Amount",

                    },
                    {
                        "data": "Cess Amount",
                        "name": "Cess Amount",

                    },
                    {
                        "data": "Rate",
                        "name": "Rate"
                    },
                ]
            });
        });
    </script>
