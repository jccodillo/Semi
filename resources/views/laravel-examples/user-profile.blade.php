@extends('layouts.user_type.auth')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="page-header mb-4 d-flex align-items-center">
                <h3 class="fw-bold mb-0 me-3">My Profile</h3>
                <span class="text-muted">Manage your personal information and account settings</span>
            </div>
            
            <!-- Profile Picture Card -->
            <div class="card shadow-lg mb-4 hover-card">
                <div class="card-header d-flex align-items-center py-3 bg-gradient-primary">
                    <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fas fa-user text-primary"></i>
                    </div>
                    <h6 class="mb-0 font-weight-bold text-white">Profile Picture</h6>
                </div>
                <div class="card-body text-center">
                    <div class="profile-image-container py-3">
                        <div class="avatar-wrapper">
                            <div class="avatar-image-container">
                                <img src="{{ auth()->user()->avatar ?? asset('assets/img/team-1.jpg') }}" 
                                     alt="Profile" 
                                     class="profile-image">
                            </div>
                            <label for="avatar-upload" class="avatar-edit-button">
                                <i class="fas fa-camera"></i>
                            </label>
                            <input type="file" 
                                   id="avatar-upload" 
                                   class="hidden-input" 
                                   accept="image/*"
                                   onchange="uploadAvatar(this)">
                        </div>
                        <p class="text-muted mt-3 mb-0 small">Click on the camera icon to upload a new photo</p>
                    </div>
                </div>
            </div>

            <!-- Profile Information Card -->
            <div class="card shadow-lg mb-4 hover-card">
                <div class="card-header d-flex align-items-center py-3 bg-gradient-primary">
                    <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fas fa-info-circle text-primary"></i>
                    </div>
                    <h6 class="mb-0 font-weight-bold text-white">Profile Information</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('profile.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Full Name</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white border-end-0">
                                            <i class="fas fa-user text-primary"></i>
                                        </span>
                                        <input type="text" class="form-control ps-2 border-start-0" name="name" value="{{ auth()->user()->name }}" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Email</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white border-end-0">
                                            <i class="fas fa-envelope text-primary"></i>
                                        </span>
                                        <input type="email" class="form-control ps-2 border-start-0" name="email" value="{{ auth()->user()->email }}" required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Phone</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white border-end-0">
                                            <i class="fas fa-phone text-primary"></i>
                                        </span>
                                        <input type="tel" class="form-control ps-2 border-start-0" name="phone" value="{{ auth()->user()->phone }}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Location</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white border-end-0">
                                            <i class="fas fa-map-marker-alt text-primary"></i>
                                        </span>
                                        <input type="text" class="form-control ps-2 border-start-0" name="location" value="{{ auth()->user()->location }}">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn bg-gradient-primary">
                                <i class="fas fa-save me-2"></i> Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Change Password Card -->
            <div class="card shadow-lg hover-card" id="password-section">
                <div class="card-header d-flex align-items-center py-3 bg-gradient-primary">
                    <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fas fa-lock text-primary"></i>
                    </div>
                    <h6 class="mb-0 font-weight-bold text-white">Change Password</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('password.change') }}" method="POST">
                        @csrf
                        
                        <div class="form-group mb-4">
                            <label class="form-label">Current Password</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0">
                                    <i class="fas fa-key text-primary"></i>
                                </span>
                                <input type="password" class="form-control ps-2 border-start-0" name="current_password" required>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">New Password</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white border-end-0">
                                            <i class="fas fa-lock text-primary"></i>
                                        </span>
                                        <input type="password" class="form-control ps-2 border-start-0" name="new_password" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Confirm New Password</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white border-end-0">
                                            <i class="fas fa-check-circle text-primary"></i>
                                        </span>
                                        <input type="password" class="form-control ps-2 border-start-0" name="new_password_confirmation" required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn bg-gradient-primary">
                                <i class="fas fa-lock me-2"></i> Update Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.page-header {
    margin-bottom: 1.5rem !important;
}

.page-header h3 {
    font-size: 1.75rem;
    font-weight: 700;
    color: #821131 !important;
    margin-right: 10px;
}

.page-header .text-muted {
    font-size: 0.9rem;
}

.text-gradient.text-primary {
    background-image: linear-gradient(310deg, #821131, #c41e3a);
    background-clip: text;
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.hover-card {
    transition: all 0.3s ease;
    border: none;
    border-radius: 15px;
    overflow: hidden;
}

.hover-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 1rem 3rem rgba(0, 0, 0, .175) !important;
}

.card-header {
    background: linear-gradient(310deg, #821131, #c41e3a);
    color: white !important;
    border-bottom: none;
    padding: 1rem 1.5rem;
    border-radius: 15px 15px 0 0 !important;
}

.card-header h6 {
    color: white !important;
    font-size: 1rem;
    font-weight: 600 !important;
}

.icon-shape {
    width: 32px;
    height: 32px;
    background-position: center;
    border-radius: 0.5rem;
}

.bg-gradient-primary {
    background: #821131 !important;
    background-image: none !important;
}

.text-primary {
    color: #821131 !important;
}

.profile-image-container {
    padding: 1.5rem 0;
}

.avatar-wrapper {
    position: relative;
    width: 150px;
    height: 150px;
    margin: 0 auto;
    transition: transform 0.3s ease;
}

.avatar-wrapper:hover {
    transform: scale(1.05);
}

.avatar-image-container {
    width: 150px;
    height: 150px;
    border-radius: 50%;
    overflow: hidden;
    border: 3px solid #821131;
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
}

.profile-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    background: #fff;
}

.avatar-edit-button {
    position: absolute;
    right: 5px;
    bottom: 5px;
    background: #821131;
    width: 38px;
    height: 38px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
    z-index: 10;
}

.avatar-edit-button:hover {
    transform: translateY(-2px) scale(1.1);
    box-shadow: 0 8px 15px rgba(0, 0, 0, 0.3);
}

.avatar-edit-button i {
    color: white;
    font-size: 16px;
}

.hidden-input {
    display: none;
}

.form-label {
    font-weight: 600;
    color: #821131;
    margin-bottom: 0.5rem;
    font-size: 0.875rem;
}

.form-control, .input-group-text {
    border: 1px solid #e3e6f0;
    border-radius: 8px;
    padding: 0.75rem 1rem;
    transition: all 0.2s ease;
    font-size: 0.875rem;
}

.input-group {
    border-radius: 8px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
    transition: all 0.3s ease;
}

.input-group:focus-within {
    box-shadow: 0 0 0 0.2rem rgba(130, 17, 49, 0.25);
}

.form-control:focus {
    border-color: #821131;
    box-shadow: none;
}

.input-group-text {
    color: #821131;
}

.btn {
    border: none;
    border-radius: 8px;
    padding: 0.75rem 1.5rem;
    font-weight: 600;
    transition: all 0.3s ease;
    box-shadow: 0 4px 6px rgba(50, 50, 93, .1), 0 1px 3px rgba(0, 0, 0, .08);
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 7px 14px rgba(50, 50, 93, .15), 0 3px 6px rgba(0, 0, 0, .1);
}

.btn.bg-gradient-primary {
    background: #821131 !important;
    border-color: #821131;
    color: white;
}

.btn.bg-gradient-primary:hover {
    background: #6a0e28 !important;
    border-color: #6a0e28;
}

.alert {
    border-radius: 10px;
    margin-bottom: 1.5rem;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    border: none;
}

.alert-success {
    background-color: rgba(130, 17, 49, 0.1);
    color: #821131;
}

.alert-danger {
    background-color: rgba(220, 53, 69, 0.1);
    color: #dc3545;
}

.shadow-lg {
    box-shadow: 0 10px 30px 0 rgba(0, 0, 0, 0.1) !important;
}

@media (max-width: 768px) {
    .col-md-6 {
        margin-bottom: 1rem;
    }
    
    .page-header {
        flex-direction: column;
        align-items: flex-start !important;
    }
    
    .page-header h3 {
        margin-bottom: 0.5rem !important;
    }
}
</style>

<script>
function uploadAvatar(input) {
    if (input.files && input.files[0]) {
        const formData = new FormData();
        formData.append('avatar', input.files[0]);
        
        fetch('/upload-avatar', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.querySelector('.profile-image').src = URL.createObjectURL(input.files[0]);
                // Show success message
                const alert = document.createElement('div');
                alert.className = 'alert alert-success alert-dismissible fade show';
                alert.innerHTML = `
                    <div class="d-flex align-items-center">
                        <i class="fas fa-check-circle me-2"></i>
                        <span>Profile picture updated successfully</span>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                `;
                document.querySelector('.card-body').insertBefore(alert, document.querySelector('.profile-image-container'));
                
                // Auto dismiss after 3 seconds
                setTimeout(() => {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }, 3000);
            } else {
                showErrorMessage('Error uploading image');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showErrorMessage('Error uploading image');
        });
    }
}

function showErrorMessage(message) {
    const alert = document.createElement('div');
    alert.className = 'alert alert-danger alert-dismissible fade show';
    alert.innerHTML = `
        <div class="d-flex align-items-center">
            <i class="fas fa-exclamation-circle me-2"></i>
            <span>${message}</span>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    `;
    document.querySelector('.card-body').insertBefore(alert, document.querySelector('.profile-image-container'));
    
    // Auto dismiss after 3 seconds
    setTimeout(() => {
        const bsAlert = new bootstrap.Alert(alert);
        bsAlert.close();
    }, 3000);
}
</script>

@if(session('success'))
<div class="position-fixed top-0 end-0 p-3" style="z-index: 1050">
    <div class="toast show align-items-center text-white bg-gradient-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body">
                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
</div>
<script>
    setTimeout(() => {
        const toastElement = document.querySelector('.toast');
        const toast = new bootstrap.Toast(toastElement);
        toast.hide();
    }, 3000);
</script>
@endif

@if($errors->any())
<div class="position-fixed top-0 end-0 p-3" style="z-index: 1050">
    <div class="toast show align-items-center text-white bg-gradient-danger border-0" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body">
                <i class="fas fa-exclamation-circle me-2"></i>
                @foreach($errors->all() as $error)
                    {{ $error }}@if(!$loop->last), @endif
                @endforeach
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
</div>
<script>
    setTimeout(() => {
        const toastElement = document.querySelector('.toast');
        const toast = new bootstrap.Toast(toastElement);
        toast.hide();
    }, 5000);
</script>
@endif
@endsection