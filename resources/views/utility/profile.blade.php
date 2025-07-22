@extends('layouts.vertical', ['title' => 'Profile Management'])

@section('content')
    @if (session('success'))
        <div class="alert alert-success" id="success-message">
            {{ session('success') }}
        </div>
    @endif
    <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
        <div class="flex-grow-1">
            <h4 class="fs-18 fw-semibold m-0">Profile</h4>
        </div>

        <div class="text-end">
            <ol class="breadcrumb m-0 py-0">
                <li class="breadcrumb-item"><a href="javascript: void(0);">Components</a></li>
                <li class="breadcrumb-item active">Profile</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">

                <div class="card-body">

                    <div class="align-items-center">
                        <div class="d-flex align-items-center">
                            <img src="{{ $user->profile_image ? asset('images/profile_images/' . $user->profile_image) : '/images/users/user-11.jpg' }}"
                                class="rounded-circle avatar-xxl img-thumbnail float-start" alt="image profile">

                            <div class="overflow-hidden ms-4">
                                <h4 class="m-0 text-dark fs-20">{{$user->name}}</h4>
                            </div>
                        </div>
                    </div>

                    <ul class="nav nav-underline border-bottom pt-2" id="pills-tab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active p-2" id="setting_tab" data-bs-toggle="tab" href="#profile_setting"
                                role="tab">
                                <span class="d-block d-sm-none"><i class="mdi mdi-school"></i></span>
                                <span class="d-none d-sm-block">Setting</span>
                            </a>
                        </li>
                    </ul>

                    <div class="tab-content text-muted bg-white">
                        <div class="tab-pane active pt-4" id="profile_setting" role="tabpanel">
                            <div class="row">

                                <div class="row">
                                    <div class="col-lg-6 col-xl-6">
                                        <div class="card border mb-0">

                                            <div class="card-header">
                                                <div class="row align-items-center">
                                                    <div class="col">
                                                        <h4 class="card-title mb-0">Personal Information</h4>
                                                    </div><!--end col-->
                                                </div>
                                            </div>

                                            <div class="card-body">
                                                <form action="{{ route('profile.update') }}" method="POST"
                                                    enctype="multipart/form-data">
                                                    @csrf

                                                    @if (sizeof($errors) > 0)
                                                        @foreach ($errors->all() as $error)
                                                            <p class="text-danger mb-3">{{ $error }}</p>
                                                        @endforeach
                                                    @endif

                                                    <div class="form-group mb-3 row">
                                                        <label class="form-label">Profile Image</label>
                                                        <div class="col-lg-12 col-xl-12">
                                                            <img id="imagePreview"
                                                                src="{{ $user->profile_image ? asset('images/profile_images/' . $user->profile_image) : 'https://via.placeholder.com/100' }}"
                                                                alt="Profile" class="mb-2 d-none" width="100">

                                                            <input class="form-control " type="file" name="profile_image"
                                                                id="profileImageInput" accept="image/*">
                                                        </div>
                                                    </div>

                                                    <div class="form-group mb-3 row">
                                                        <label class="form-label">Full Name</label>
                                                        <div class="col-lg-12 col-xl-12">
                                                            <input class="form-control" name="name" type="text"
                                                                value="{{ !empty(old('name')) ? old('name') : $user->name }}">
                                                        </div>
                                                    </div>

                                                    <div class="form-group mb-3 row">
                                                        <label class="form-label">Contact Phone</label>
                                                        <div class="col-lg-12 col-xl-12">
                                                            <div class="input-group">
                                                                <span class="input-group-text"><i
                                                                        class="mdi mdi-phone-outline"></i></span>
                                                                <input class="form-control" type="text"
                                                                    name="contact_number" placeholder="Phone"
                                                                    aria-describedby="basic-addon1"
                                                                    value="{{ !empty(old('contact_number')) ? old('contact_number') : $user->contact_number ?? '' }}">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="form-group mb-3 row">
                                                        <label class="form-label">Email Address</label>
                                                        <div class="col-lg-12 col-xl-12">
                                                            <div class="input-group">
                                                                <span class="input-group-text"><i
                                                                        class="mdi mdi-email"></i></span>
                                                                <input type="email" class="form-control" disabled
                                                                    value="{{ $user->email }}"
                                                                    aria-describedby="basic-addon1">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="form-group row">
                                                        <div class="col-lg-12 col-xl-12">
                                                            <button type="submit" class="btn btn-primary">Update
                                                                Profile</button>
                                                        </div>
                                                    </div>
                                                </form>

                                            </div><!--end card-body-->
                                        </div>
                                    </div>

                                    <div class="col-lg-6 col-xl-6">
                                        <div class="card border mb-0">

                                            <div class="card-header">
                                                <div class="row align-items-center">
                                                    <div class="col">
                                                        <h4 class="card-title mb-0">Change Password</h4>
                                                    </div><!--end col-->
                                                </div>
                                            </div>

                                            <form action="{{ route('user.change-password') }}" method="POST">
                                                @csrf

                                                @if (session('change-pass-error'))
                                                    <div class="alert alert-danger" id="change-pass-error">
                                                        {{ session('change-pass-error') }}
                                                    </div>
                                                @endif

                                                @if ($errors->changePassword->any())
                                                    <div class="alert alert-danger">
                                                        @foreach ($errors->changePassword->all() as $error)
                                                            <div>{{ $error }}</div>
                                                        @endforeach
                                                    </div>
                                                @endif

                                                <div class="card-body mb-0">
                                                    <div class="form-group mb-3 row">
                                                        <label class="form-label">Old Password</label>
                                                        <div class="col-lg-12 col-xl-12">
                                                            <input name="old_password" value="{{ old('old_password') }}"
                                                                class="form-control" type="password"
                                                                placeholder="Old Password" required>
                                                        </div>
                                                    </div>

                                                    <div class="form-group mb-3 row">
                                                        <label class="form-label">New Password</label>
                                                        <div class="col-lg-12 col-xl-12">
                                                            <input name="new_password" value="{{ old('new_password') }}"
                                                                class="form-control" type="password"
                                                                placeholder="New Password" required>
                                                        </div>
                                                    </div>

                                                    <div class="form-group mb-3 row">
                                                        <label class="form-label">Confirm Password</label>
                                                        <div class="col-lg-12 col-xl-12">
                                                            <input name="new_password_confirmation"
                                                                value="{{ old('new_password_confirmation') }}"
                                                                class="form-control" type="password"
                                                                placeholder="Confirm Password" required>
                                                        </div>
                                                    </div>

                                                    <div class="form-group row">
                                                        <div class="col-lg-12 col-xl-12">
                                                            <button type="submit" class="btn btn-primary">Change
                                                                Password</button>
                                                            <a class="btn btn-danger">Cancel</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                            <!--end card-body-->
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div> <!-- end education -->

                    </div> <!-- Tab panes -->
                </div>
            </div>
        </div>
    </div>

    <script>
        setTimeout(function() {
            const success = document.getElementById('success-message');
            const error = document.getElementById('change-pass-error');
            if (success) {
                success.style.transition = "opacity 0.5s";
                success.style.opacity = 0;
                setTimeout(() => success.remove(), 500);
            }
            if (error) {
                error.style.transition = "opacity 0.5s";
                error.style.opacity = 0;
                setTimeout(() => error.remove(), 500);
            }

        }, 3000);


        document.getElementById('profileImageInput').addEventListener('change', function(event) {
            const input = event.target;
            const preview = document.getElementById('imagePreview');

            if (input.files && input.files[0]) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    preview.src = e.target.result;
                };
                preview.classList.remove('d-none'); 
                reader.readAsDataURL(input.files[0]);
            }
        });
    </script>
@endsection
