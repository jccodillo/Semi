@extends('layouts.user_type.guest')

@section('content')
<main class="main-content" style="background-color: #f1f1f1; min-height: 100vh;">
    <section class="d-flex align-items-center justify-content-center min-vh-100">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-4">
                    <div class="card shadow-sm" style="border-radius: 15px;">
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h3 class="font-weight-bold m-0" style="font-size: 1.25rem;">Forgot Password</h3>
                                <div class="d-flex align-items-center gap-2">
                                    <img src="{{ asset('assets/img/TUP.logo.png') }}" alt="TUP Logo" style="height: 50px;">
                                    <img src="{{ asset('assets/img/univaultlogo.png') }}" alt="UniVault Logo" style="height: 50px;">
                                </div>
                            </div>

                            @if($errors->any())
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <span class="alert-text">{{$errors->first()}}</span>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif
                            
                            @if(session('success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <span class="alert-text">{{ session('success') }}</span>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif

                            <p class="text-muted mb-4">Enter your email address and we'll send you a link to reset your password.</p>

                            <form action="/forgot-password" method="POST" role="form">
                                @csrf
                                <div class="mb-3">
                                    <label for="email" class="form-label small">Email</label>
                                    <input id="email" name="email" type="email" class="form-control form-control-sm" 
                                           placeholder="Enter your email" style="background-color: #f1f1f1; border-radius: 8px;">
                                    @error('email')
                                        <p class="text-danger text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-sm text-white" 
                                            style="background-color: #c41e3a; border-radius: 8px; padding: 8px;">
                                        RECOVER YOUR PASSWORD
                                    </button>
                                </div>

                                <div class="mt-3 text-center">
                                    <a href="{{ route('session.login') }}" class="text-sm" style="color: #c41e3a; text-decoration: none;">
                                        Back to login
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<style>
.form-control:focus {
    border-color: #c41e3a;
    box-shadow: 0 0 0 0.2rem rgba(196, 30, 58, 0.25);
}

.btn:hover {
    background-color: #a01830 !important;
}

.alert-success {
    background-color: #d1e7dd;
    color: #0f5132;
    border-color: #badbcc;
}

.alert-danger {
    background-color: #f8d7da;
    color: #842029;
    border-color: #f5c2c7;
}
</style>
@endsection