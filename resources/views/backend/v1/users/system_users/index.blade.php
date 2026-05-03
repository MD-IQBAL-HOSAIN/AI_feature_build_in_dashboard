@extends('backend.master')

@section('title', 'User List')

@push('styles-top')
    <style>
        .role-pill {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 88px;
            padding: 0.28rem 0.65rem;
            border-radius: 999px;
            font-size: 0.75rem;
            font-weight: 600;
            letter-spacing: 0.02em;
            text-transform: capitalize;
        }

        .role-pill.role-admin {
            background-color: #e8f7ef;
            color: #1f8f54;
            border: 1px solid #bfe8cf;
        }

        .role-pill.role-user {
            background-color: #fff3e8;
            color: #d97706;
            border: 1px solid #ffd8b5;
        }
    </style>
@endpush

@section('content')
{{-- PAGE-HEADER --}}
<div class="page-header">
    <div>
        <h1 class="page-title">List of Users</h1>
    </div>
    <div class="ms-auto pageheader-btn">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0);">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Users</li>
        </ol>
    </div>
</div>
{{-- PAGE-HEADER --}}

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                     <div class="d-flex justify-content-end mb-2">
                        <a href="{{ route('system-user.create') }}" class="btn btn-primary">+ Add User</a>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered text-nowrap border-bottom w-100" id="datatable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Image</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                            {{-- dynamic data --}}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection


@push('scripts-bottom')
    <script>
        const csrfToken = '{{ csrf_token() }}';

        $(document).ready(function() {

            $.ajaxSetup({
                headers: {
                    "X-CSRF-TOKEN": csrfToken,
                }
            });
            if (!$.fn.DataTable.isDataTable('#datatable')) {
                let dTable = $('#datatable').DataTable({
                    order: [],
                    lengthMenu: [
                        [10, 25, 50, 100, -1],
                        [10, 25, 50, 100, "All"]
                    ],
                    processing: true,
                    responsive: true,
                    serverSide: true,

                    language: {
                        processing: `<div class="text-center">
                        <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                        <span class="visually-hidden">Loading...</span>
                        </div>
                        </div>`
                    },

                    scroller: {
                        loadingIndicator: false
                    },
                    pagingType: "full_numbers",
                    dom: "<'row justify-content-between table-topbar'<'col-md-2 col-sm-4 px-0'l><'col-md-2 col-sm-4 px-0'f>>tipr",
                    ajax: {
                        url: "{{ route('system-user.index') }}",
                        type: "GET",
                    },

                    columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                        {
                            data: 'avatar',
                            name: 'avatar',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'name',
                            name: 'name',
                            orderable: true,
                            searchable: true
                        },
                        {
                            data: 'email',
                            name: 'email',
                            orderable: true,
                            searchable: true
                        },
                        {
                            data: 'role',
                            name: 'role',
                            orderable: true,
                            searchable: true
                        },

                        {
                            data: 'status',
                            name: 'status',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false
                        },
                    ],
                });
            }
        });

        // Status Change Confirm Alert
        function showStatusChangeAlert(id) {
            event.preventDefault();

            Swal.fire({
                title: 'Are you sure?',
                text: 'You want to update the status?',
                icon: 'info',
                showCancelButton: true,
                confirmButtonText: 'Yes',
                cancelButtonText: 'No',
            }).then((result) => {
                if (result.isConfirmed) {
                    statusChange(id);
                }
            });
        }
        // Status Change
        function statusChange(id) {
            let url = "{{ route('system-user.status', ':id') }}";
            $.ajax({
                type: "POST",
                url: url.replace(':id', id),
                data: {
                    _token: csrfToken,
                },
                success: function(resp) {
                    // Reloade DataTable
                    $('#datatable').DataTable().ajax.reload();
                    if (resp.success === true) {
                        toastr.success(resp.message);
                    } else {
                        toastr.error(resp.message || 'Status change failed.');
                    }
                },
                error: function(error) {
                    toastr.error(error?.responseJSON?.message || 'An error occurred. Please try again.');
                }
            });
        }

       // delete Confirm
        function showDeleteConfirm(id) {
            event.preventDefault();
            Swal.fire({
                title: 'Are you sure you want to delete ?',
                text: 'If you delete this, it will be gone forever.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!',
            }).then((result) => {
                if (result.isConfirmed) {
                    deleteItem(id);
                }
            });
        }

        function edit(id) {
            let url = "{{ route('system-user.edit', ':id') }}";
            url = url.replace(':id', id);

            window.location.href = url;
        }


        // Delete Button
        function deleteItem(id) {
            let url = "{{ route('system-user.destroy', ':id') }}";
            $.ajax({
                type: "DELETE",
                url: url.replace(':id', id),
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                success: function(resp) {
                    $('#datatable').DataTable().ajax.reload();
                    if (resp.success === true) {
                        toastr.success(resp.message);
                    } else {
                        toastr.error(resp.message || 'Delete failed.');
                    }
                },
                error: function(error) {
                    toastr.error(error?.responseJSON?.message || 'An error occurred. Please try again.');
                }
            });
        }
    </script>
@endpush


