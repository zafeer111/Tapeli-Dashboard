@extends('layouts.auth', ['title' => 'Logout'])

@section('content')
<div class="col-md-5">
    <div class="row">
        <div class="col-md-7 mx-auto">
            <div class="mb-0 border-0 p-md-5 p-lg-0 p-4">

                <div class="mb-4 p-0 text-center">
                    <a href="{{ route('home') }}" class="auth-logo">
                        <img src="/images/logo-sm.png" alt="logo-dark" class="mx-auto" height="50"/>
                    </a>
                </div>
                
                <div class="text-center auth-title-section">
                    <h3 class="text-dark fs-20 fw-medium mb-2">You are Logged Out</h3>
                    <p class="text-muted fs-15">Thank you for using GDV Admin Dashboard</p>
                </div>
            
                <div class="text-center">
                    <a href='{{ route('register') }}' class="btn btn-primary mt-3 me-1"> Log In </a>
                </div>

                <div class="maintenance-img text-center pt-4">
                    <img src="/images/svg/logout.svg" height="200" alt="svg-logo">
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
                <img src="/images/authentication.svg" class="mx-auto img-fluid"  alt="images">
            </div>
        </div>
    </div>
</div>
@endsection