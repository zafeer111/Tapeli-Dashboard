@extends('layouts.vertical', ['title' => 'User Create'])

@section('content')
    <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
        <div class="flex-grow-1">
            <h4 class="fs-18 fw-semibold m-0">User Management</h4>
        </div>

        <div class="text-end">
            <ol class="breadcrumb m-0 py-0">
                <li class="breadcrumb-item"><a href="javascript: void(0);">User Management</a></li>
                <li class="breadcrumb-item active">Create</li>
            </ol>
        </div>
    </div>

    <!-- Form Validation -->
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">User Create</h5>
                </div><!-- end card header -->

                <div class="card-body">
                    <form class="row g-3" action="{{ route('user-management.store') }}" method="POST">
                        @csrf

                        <!-- Name Field -->
                        <div class="col-md-6">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                id="name" value="{{ old('name') }}" placeholder="Enter name" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Email Field -->
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label class="form-label">Email Address</label>
                                <div class="col-lg-12 col-xl-12">
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="mdi mdi-email"></i></span>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                                            name="email" placeholder="Enter email" value="{{ old('email') }}"
                                            aria-describedby="basic-addon1">
                                    </div>
                                    @error('email')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Contact Phone Field -->
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label class="form-label">Contact Phone</label>
                                <div class="col-lg-12 col-xl-12">
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="mdi mdi-phone-outline"></i></span>
                                        <input class="form-control @error('contact_number') is-invalid @enderror"
                                            type="text" name="contact_number" placeholder="+61 399615"
                                            value="{{ old('contact_number') }}" aria-describedby="basic-addon1">
                                    </div>
                                    @error('contact_number')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Role Select -->
                        <div class="col-md-6">
                            <label for="validationDefault04" class="form-label">Select Role</label>
                            <select class="form-select @error('role_id') is-invalid @enderror" name="role_id"
                                id="validationDefault04" required>
                                <option selected disabled value="">Select...</option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                        {{ $role->name }}</option>
                                @endforeach
                            </select>
                            @error('role_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Password Field -->
                        <div class="col-md-6">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror"
                                name="password" id="password" value="" placeholder="Enter password" required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <div class="col-12">
                            <button class="btn btn-primary" type="submit">Submit form</button>
                        </div>
                    </form>
                </div> <!-- end card-body -->
            </div> <!-- end card-->
        </div> <!-- end col -->
    </div>
@endsection
