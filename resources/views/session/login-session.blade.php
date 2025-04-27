@extends('layouts.user_type.guest')
@section('content')
<main class="main-content" style="background-color: #f1f1f1; min-height: 100vh;">
    <section class="d-flex align-items-center justify-content-center min-vh-100">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-5">
                    <div class="card shadow-sm" style="border-radius: 15px;">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h3 class="font-weight-bold m-0">LOGIN</h3>
                                <div class="d-flex align-items-center gap-3">
                                    <img src="{{ asset('assets/img/TUP.logo.png') }}" alt="TUP Logo" style="height: 70px;">
                                    <img src="{{ asset('assets/img/univaultlogo.png') }}" alt="UniVault Logo" style="height: 70px;">
                                </div>
                            </div>

                            <form role="form" method="POST" action="/session">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label">Email:</label>
                                    <input type="email" class="form-control" name="email" id="email" 
                                        placeholder="Enter email" style="background-color: #f1f1f1; border-radius: 8px;">
                                    @error('email')
                                        <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Password:</label>
                                    <input type="password" class="form-control" name="password" id="password" 
                                        placeholder="Enter password" style="background-color: #f1f1f1; border-radius: 8px;">
                                    @error('password')
                                        <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="showPassword">
                                        <label class="form-check-label" for="showPassword">Show Password</label>
                                    </div>
                                </div>

                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn text-white" 
                                        style="background-color: #c41e3a; border-radius: 8px; padding: 10px;">SIGN IN</button>
                                </div>

                                <div class="mt-3 text-center">
                                    <p class="mb-0">
                                        Forgot 
                                        <a href="/login/forgot-password" class="text-primary text-decoration-none">Password</a>?
                                    </p>
                                    <p class="mb-0">
                                        Don't have an account? 
                                        <a href="register" class="text-primary text-decoration-none">Sign up</a>
                                    </p>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<script>
document.getElementById('showPassword').addEventListener('change', function() {
    const passwordInput = document.getElementById('password');
    passwordInput.type = this.checked ? 'text' : 'password';
});
</script>

<style>
.form-control:focus {
    border-color: #c41e3a;
    box-shadow: 0 0 0 0.2rem rgba(196, 30, 58, 0.25);
}

.form-check-input:checked {
    background-color: #c41e3a;
    border-color: #c41e3a;
}

.text-primary {
    color: #c41e3a !important;
}

.btn:hover {
    background-color: #a01830 !important;
}
</style>
@endsection
