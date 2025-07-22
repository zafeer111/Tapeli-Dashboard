@extends('layouts.vertical', ['title' => 'Role Edit'])

@section('content')
    <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
        <div class="flex-grow-1">
            <h4 class="fs-18 fw-semibold m-0">Role Management</h4>
        </div>

        <div class="text-end">
            <ol class="breadcrumb m-0 py-0">
                <li class="breadcrumb-item"><a href="javascript: void(0);">Role Management</a></li>
                <li class="breadcrumb-item active">Edit</li>
            </ol>
        </div>
    </div>

    <!-- Form Validation -->
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Role Edit</h5>
                </div><!-- end card header -->

                <div class="card-body">
                    <form class="row g-3" action="{{ route('role-management.update', $role->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Role Name Field -->
                        <div class="col-md-6">
                            <label for="name" class="form-label">Role Name</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                id="name" value="{{ old('name', $role->name) }}" placeholder="Enter role name"
                                required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-12 mt-4">
                            <div class="">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Permissions</h5>
                                </div>

                                <div class="card-body">
                                    <div class="row">
                                        @foreach ($permissions as $permission)
                                            <div class="col-md-3 mb-3">
                                                <div class="form-check d-flex align-items-center">
                                                    <input class="form-check" type="checkbox" name="permissions[]"
                                                        value="{{ $permission->name }}"
                                                        id="permission_{{ $permission->id }}"
                                                        {{ in_array($permission->id, old('permissions', $rolePermissions ?? [])) ? 'checked' : '' }}>
                                                    <label class="form-check" for="permission_{{ $permission->id }}">
                                                        {{ \Illuminate\Support\Str::of($permission->name)->replace('_', ' ')->title() }}

                                                    </label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div> <!-- end card-body -->
                            </div> <!-- end card -->
                        </div> <!-- end col -->

                        <!-- Submit Button -->
                        <div class="col-12 mt-4">
                            <button class="btn btn-primary" type="submit">Update</button>
                        </div>
                    </form>
                </div> <!-- end card-body -->
            </div> <!-- end card-->
        </div> <!-- end col -->
    </div>
@endsection
