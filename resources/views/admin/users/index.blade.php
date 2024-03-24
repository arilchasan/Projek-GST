@extends('admin.components.app')

@section('container')
    @if (session()->has('error'))
        <div class="w-1/1 relative py-3 pl-4 pr-10 leading-normal text-red-700 bg-red-100 rounded-lg mb-2 mx-auto"
            style="height: 50px;" role="alert" id="error-alert">
            <p>{{ session('error') }}</p>
            <span class="absolute inset-y-0 right-0 flex items-center mr-4" onclick="closeAlert('error-alert')">
                <svg class="w-4 h-4 fill-current" role="button" viewBox="0 0 20 20">
                    <path
                        d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                        clip-rule="evenodd" fill-rule="evenodd"></path>
                </svg>
            </span>
        </div>
    @endif
    @if (session()->has('success'))
        <div class="w-1/1 relative py-3 pl-4 pr-10 leading-normal text-green-700 bg-green-100 rounded-lg mb-2 mx-auto"
            style="height: 50px;" role="alert" id="success-alert">
            <p>{{ session('success') }}</p>
            <span class="absolute inset-y-0 right-0 flex items-center mr-4" onclick="closeAlert('success-alert')">
                <svg class="w-4 h-4 fill-current" role="button" viewBox="0 0 20 20">
                    <path
                        d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                        clip-rule="evenodd" fill-rule="evenodd"></path>
                </svg>
            </span>
        </div>
    @endif
<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" />
    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
</head>
<style>
    @import url('/assets/table.css');

    @media only screen and (max-width: 767px) {
            .table-responsive {
                overflow-x: auto;
                white-space: nowrap;
            }

            .table-responsive table {
                min-width: 100%;
            }

            .table-responsive table thead tr th,
            .table-responsive table tbody tr td {
                min-width: auto !important;
            }

            .table-responsive table thead tr th {
                white-space: nowrap;
            }
        }
</style>
<!-- component -->
<div class="card shadow m-2 p-3">
    <div class="card-header border-0 flex justify-between items-center">
        <h3 class="mb-0">Users Management</h3>
        {{-- <a href="" class="btn btn-success ml-2 leading-2 px-3">+ Users</a> --}}
    </div>
    <div class="table-responsive">
        <table id="user-table" class="table align-items-center table-flush ">
            <thead class="thead-light">
                <tr>
                    <th scope="col" style="width: 20%">Name</th>
                    <th scope="col" style="width: 20%">Email</th>
                    <th scope="col" style="width: 20%">Status</th>
                    <th scope="col" style="width: 20%">Expired At</th>
                    <th scope="col" style="width: 20%">Action</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>
@endsection

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>

<script>
    $(document).ready(function() {
        var table = $('#user-table').DataTable({
            ordering: true,
            order: [[2, 'desc']],
            processing: true,
            serverSide: true,
            responsive: true,
            searchDelay: 500,
            pageLength: 5,
            pagingType: 'simple',
            lengthMenu: [5, 10, 25, 50, 100],
            ajax: "{{ route('user') }}",
            language: {
                "paginate": {
                    "next": "<i class='fas fa-angle-right' ></i>",
                    "previous": "<i class='fas fa-angle-left' ></i>"
                },
                // "loadingRecords": "Loading...",
                // "processing": "Processing...",
                "search": "Search:",
                "searchPlaceholder": "Search",
                "emptyTable": "No data available",
                "lengthMenu": "_MENU_ ",
                "zeroRecords": "No matching records found",
                "info": "Showing _END_ of _TOTAL_ entries",
                "infoEmpty": "Showing 0 of 0 entries",
                "infoFiltered": "(filtered from _MAX_ total entries)",

            },
            columns: [{
                    data: 'name',
                    name: 'name',
                    ordering: true,
                    search: true,
                    render: function(data, type, row, meta) {
                        return `
                            <th scope="row">
                                <div class="media align-items-center">
                                    <div class="media-body">
                                        <span class="mb-0 text-sm">${data}</span>
                                    </div>
                                </div>
                            </th>`;
                    }
                },
                {
                    data: 'email',
                    name: 'email',
                    ordering: true,
                    search: true,
                    render: function(data, type, row, meta) {
                        return `
                            <th scope="row">
                                <div class="media align-items-center">
                                    <div class="media-body">
                                        <span class="mb-0 text-sm">${data}</span>
                                    </div>
                                </div>
                            </th>`;
                    }
                },
                {
                    data: 'status',
                    name: 'status',
                    ordering: true,
                    search: true,
                    render: function(data, type, row, meta) {
                        if (data === 'active') {
                            return `<td class="text-start">
                        <span class="text-center align-baseline inline-flex px-4 py-3 items-center font-semibold text-[.95rem] leading-none text-green-500 bg-green-200 rounded-lg">
                            ${data}
                        </span>
                    </td>`;
                        } else {
                            return `<td class="text-start">
                        <span class="text-center align-baseline inline-flex px-4 py-3 items-center font-semibold text-[.95rem] leading-none text-red-500 bg-red-200 rounded-lg">
                            ${data}
                        </span>
                    </td>`;
                        }
                    }
                },
                {
                    data: 'expired_at',
                    name: 'expired_at',
                    ordering: true,
                    search: true,
                    orderSequence: ['desc', 'asc'],
                    render: function(data, type, row, meta) {
                        return `
                            <th scope="row">
                                <div class="media">
                                    <div class="media-body">
                                      ${data}
                                    </div>
                                </div>
                            </th>`;
                    }
                },
                {
                    data: 'action',
                    name: 'action',
                    ordering: true,
                    search: true,
                    render: function(data, type, row, meta) {
                        return `
                            <th scope="row">
                                <div class="media">
                                    <div class="media-body">
                                      ${data}
                                    </div>
                                </div>
                            </th>`;
                        }
                }
            ],
        });
    });


    function closeAlert(alertId) {
        var alert = document.getElementById(alertId);
        if (alert) {
            alert.style.display = 'none';
        }
    }
</script>
