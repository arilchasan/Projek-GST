<head>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>B2B Page</title>
</head>

<body>
    <div class="mx-1">
        <div class="d-flex justify-content-between align-items-center mb-3 p-4">
            <h3 class="flex-grow-1">Export B2B Data</h3>
            <a href="{{ route('export.b2b', ['data' => $filename]) }}"
                class="btn btn-success">Export</a>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered" id="b2b">
                <thead>
                    <tr>
                        <th style="width: 10%;background-color: #0080ff;color: #ffffff; ">Recipient GSTIN</th>
                        <th style="width: 10%;background-color: #0080ff;color: #ffffff; ">Receiver Name</th>
                        <th style="width: 10%;background-color: #0080ff;color: #ffffff; ">Invoice Number</th>
                        <th style="width: 10%;background-color: #0080ff;color: #ffffff; ">Invoice Date</th>
                        <th style="width: 10%;background-color: #0080ff;color: #ffffff; ">Invoice Value</th>
                        <th style="width: 10%;background-color: #0080ff;color: #ffffff; ">Place Of Supply</th>
                        <th style="width: 10%;background-color: #0080ff;color: #ffffff; ">Reverse Charge</th>
                        <th style="width: 10%;background-color: #0080ff;color: #ffffff; ">Applicable % of Tax Rate</th>
                        <th style="width: 10%;background-color: #0080ff;color: #ffffff; ">Invoice Type</th>
                        <th style="width: 10%;background-color: #0080ff;color: #ffffff; ">E-Commerce GSTIN</th>
                        <th style="width: 5%;background-color: #0080ff;color: #ffffff; ">Rate</th>
                        <th style="width: 5%;background-color: #0080ff;color: #ffffff; ">Taxable Value</th>
                        <th style="width: 2%;background-color: #0080ff;color: #ffffff; ">Cess Amount</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
        <div class="table-responsive mx-5">
            <table class="table table-bordered" >
                <thead>
                    <tr>
                        <th style="width: 10%;background-color: #0080ff;color: #ffffff; ">Total Invoice Value</th>
                        <th style="width: 10%;background-color: #0080ff;color: #ffffff; ">Total Taxable Value</th>
                        <th style="width: 10%;background-color: #0080ff;color: #ffffff; ">Total CESS</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{$invoiceValue}}</td>
                        <td>{{$taxableAmount}}</td>
                        <td>{{$cessAmount}}</td>
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
            $('#b2b').DataTable({
                "processing": true,
                order: [
                    [3, 'asc']
                ],
                "serverSide": true,
                "ajax": "{{ route('show.b2b', ['filename' => $filename]) }}",
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
                        "data": "GSTIN/UIN of Recipient",
                        "name": "GSTIN/UIN of Recipient",
                        "render": function(data, type, row, meta) {
                            return `<td>${data}</td>`;
                        }
                    },
                    {
                        "data": "Receiver Name",
                        "render": function(data, type, row, meta) {
                            return `<td>${data}</td>`;
                        }
                    },
                    {
                        "data": "Invoice Number",
                        "render": function(data, type, row, meta) {
                            return `<td>${data}</td>`;
                        }
                    },
                    {
                        "data": "Invoice Date",
                        "render": function(data, type, row, meta) {
                            return `<td>${data}</td>`;
                        }
                    },
                    {
                        "data": "Invoice Value",
                        "render": function(data, type, row, meta) {
                            return `<td>${data}</td>`;
                        }
                    },
                    {
                        "data": "Place Of Supply",
                        "render": function(data, type, row, meta) {
                            return `<td>${data}</td>`;
                        }
                    },
                    {
                        "data": "Reverse Charge",
                        "render": function(data, type, row, meta) {
                            return `<td>${data}</td>`;
                        }
                    },
                    {
                        "data": "Applicable % of Tax Rate",
                        "render": function(data, type, row, meta) {
                            return `<td>${data}</td>`;
                        }
                    },
                    {
                        "data": "Invoice Type",
                        "render": function(data, type, row, meta) {
                            return `<td>${data}</td>`;
                        }
                    },
                    {
                        "data": "E-Commerce GSTIN",
                        "render": function(data, type, row, meta) {
                            return `<td>${data}</td>`;
                        }
                    },
                    {
                        "data": "Rate",
                        "render": function(data, type, row, meta) {
                            return `<td>${data}</td>`;
                        }
                    },
                    {
                        "data": "Taxable Value",
                        "render": function(data, type, row, meta) {
                            return `<td>${data}</td>`;
                        }
                    },
                    {
                        "data": "Cess Amount",
                        "render": function(data, type, row, meta) {
                            return `<td>${data}</td>`;
                        }
                    }
                ]
            });
        });
    </script>
