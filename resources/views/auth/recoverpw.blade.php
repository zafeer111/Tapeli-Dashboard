@extends('layouts.auth', ['title' => 'Recover Password'])

@section('content')
<div class="col-xl-5">
    <div class="row">
        <div class="col-md-7 mx-auto">
            <div class="mb-0 border-0 p-md-5 p-lg-0 p-4">
                <div class="mb-4 p-0">
                    <a href="{{ route('home') }}" class="auth-logo">
                        <img src="/images/logo-sm.png" alt="logo-dark" class="mx-auto" height="50" />
                    </a>
                </div>

                <div class="pt-0">

                    <form method="POST" action="{{route('password.email')}}" class="my-4">
                        @csrf
                        <div class="form-group mb-3">
                            <label for="emailaddress" class="form-label">Email address</label>
                            <input class="form-control" name="email" type="email" id="emailaddress" required="" placeholder="Enter your email">
                        </div>

                        <div class="form-group mb-0 row">
                            <div class="col-12">
                                <div class="d-grid">
                                    <button class="btn btn-primary" type="submit"> Recover Password </button>
                                </div>
                            </div>
                        </div>
                    </form>

                    <div class="text-center text-muted">
                        <p class="mb-0">Change the mind ?<a class='text-primary ms-2 fw-medium' href="{{ route('login') }}">Back to Login</a></p>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<div class="col-xl-7">
    <div class="account-page-bg p-md-5 p-4">
        <div class="text-center">
            <h3 class="text-dark mb-3 pera-title">GAME DAY VALET, Admin Panel</h3>
            <div class="auth-image">
                <img src="/images/authentication.svg" class="mx-auto img-fluid" alt="images">
            </div>
        </div>
    </div>
</div>
@endsection