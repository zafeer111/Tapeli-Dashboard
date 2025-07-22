@extends('layouts.vertical', ['title' => 'User List'])

@section('css')
    @vite(['node_modules/datatables.net-bs5/css/dataTables.bootstrap5.min.css', 'node_modules/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css', 'node_modules/datatables.net-keytable-bs5/css/keyTable.bootstrap5.min.css', 'node_modules/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css', 'node_modules/datatables.net-select-bs5/css/select.bootstrap5.min.css'])
@endsection

@section('content')
    <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
        <div class="flex-grow-1">
            <h4 class="fs-18 fw-semibold m-0">User Management</h4>
        </div>

        <div class="text-end">
            <ol class="breadcrumb m-0 py-0">
                <li class="breadcrumb-item"><a href="javascript: void(0);">User Management</a></li>
                <li class="breadcrumb-item active">List</li>
            </ol>
        </div>
    </div>

    <!-- Datatables  -->
    <div class="row">
        <div class="col-12">
            <div class="card">

                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">User List</h5>
                    <a href="{{ route('user-management.create') }}" class="btn btn-primary" id="createButton">Create</a>
                </div><!-- end card header -->

                <div class="card-body">
                    <table id="datatable" class="table table-bordered dt-responsive table-responsive nowrap">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Contact Number</th>
                                <th>Role</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->contact_number ?? 'N/A' }}</td>
                                    <td> {{ $user->getRoleNames()->implode(', ') }}</td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <a href="{{ route('user-management.edit', $user->id) }}" class="btn btn-sm btn-primary">Edit</a>
                                            <form action="{{ route('user-management.destroy', $user->id) }}" method="POST" class="delete-user-form" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-sm btn-danger delete-user-btn">Delete</button>
                                            </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
@endsection

@section('script')
    @vite(['resources/js/pages/datatable.init.js'])
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.delete-user-btn').forEach(function(btn) {
                btn.addEventListener('click', function(e) {
                    if (confirm('Are you sure you want to delete this user?')) {
                        btn.closest('form').submit();
                    }
                });
            });
        });
    </script>
@endsection
