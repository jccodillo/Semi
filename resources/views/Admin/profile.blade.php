@extends('layouts.user_type.auth')

@section('content')
  <div class="main-content position-relative bg-gray-100 max-height-vh-100 h-100">
    <div class="container-fluid">
      <!-- Profile Header Section -->
      <div class="page-header min-height-300 border-radius-xl mt-4" style="background-image: url('../assets/img/curved-images/curved0.jpg'); background-position-y: 50%;">
        <span class="mask bg-gradient-primary opacity-6"></span>
      </div>

      <!-- Main Profile Card -->
      <div class="card card-body blur shadow-blur mx-4 mt-n6 overflow-hidden">
        <div class="row gx-4">
          <div class="col-auto">
            <div class="avatar avatar-xl position-relative">
              <img src="{{ Auth::user()->avatar ?? '../assets/img/default-avatar.png' }}" alt="profile_image" class="w-100 border-radius-lg shadow-sm" id="profile-image">
              <div class="position-absolute bottom-0 end-0">
                <button type="button" class="btn btn-sm btn-primary mb-0 rounded-circle" id="change-avatar-btn" style="padding: 6px 8px;">
                  <i class="fas fa-camera"></i>
                </button>
              </div>
              <form id="avatar-form" style="display: none;">
                @csrf
                <input type="file" name="avatar" id="avatar-upload" accept="image/*">
              </form>
            </div>
          </div>
          <div class="col-auto my-auto">
            <div class="h-100">
              <h5 class="mb-1">{{ Auth::user()->name }}</h5>
              <p class="mb-0 font-weight-bold text-sm">Administrator</p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="container-fluid py-4">
      <div class="row">
        <!-- Profile Information -->
        <div class="col-12 col-xl-4">
          <div class="card h-100">
            <div class="card-header pb-0 p-3">
              <h6 class="mb-0">Profile Information</h6>
            </div>
            <div class="card-body p-3">
              <ul class="list-group">
                <li class="list-group-item border-0 ps-0 pt-0 text-sm"><strong class="text-dark">Full Name:</strong> &nbsp; {{ Auth::user()->name }}</li>
                <li class="list-group-item border-0 ps-0 text-sm"><strong class="text-dark">Email:</strong> &nbsp; {{ Auth::user()->email }}</li>
                <li class="list-group-item border-0 ps-0 text-sm"><strong class="text-dark">Role:</strong> &nbsp; Administrator</li>
                <li class="list-group-item border-0 ps-0 text-sm"><strong class="text-dark">Member Since:</strong> &nbsp; {{ Auth::user()->created_at->format('M Y') }}</li>
              </ul>
            </div>
          </div>
        </div>

        <!-- Account Settings -->
        <div class="col-12 col-xl-4">
          <div class="card h-100">
            <div class="card-header pb-0 p-3">
              <h6 class="mb-0">Account Settings</h6>
            </div>
            <div class="card-body p-3">
              @if (session('success'))
              <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>
              @endif

              @if ($errors->any())
              <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <ul class="mb-0">
                  @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                  @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>
              @endif
              
              <form action="{{ route('admin.profile.update') }}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group">
                  <label for="name" class="form-control-label">Name</label>
                  <input class="form-control" type="text" name="name" id="name" value="{{ Auth::user()->name }}">
                </div>
                <div class="form-group">
                  <label for="email" class="form-control-label">Email</label>
                  <input class="form-control" type="email" name="email" id="email" value="{{ Auth::user()->email }}">
                </div>
                <div class="form-group">
                  <label for="phone" class="form-control-label">Phone</label>
                  <input class="form-control" type="tel" name="phone" id="phone" value="{{ Auth::user()->phone }}">
                </div>
                <div class="form-group">
                  <label for="location" class="form-control-label">Location</label>
                  <input class="form-control" type="text" name="location" id="location" value="{{ Auth::user()->location }}">
                </div>
                <div class="form-group">
                  <label for="password" class="form-control-label">New Password</label>
                  <input class="form-control" type="password" name="password" id="password">
                </div>
                <div class="form-group">
                  <label for="password_confirmation" class="form-control-label">Confirm New Password</label>
                  <input class="form-control" type="password" name="password_confirmation" id="password_confirmation">
                </div>
                <div class="text-end mt-4">
                  <button type="submit" class="btn btn-primary mb-0">Save Changes</button>
                </div>
              </form>
            </div>
          </div>
        </div>

        <!-- Activity Overview -->
        <div class="col-12 col-xl-4">
          <div class="card h-100">
            <div class="card-header pb-0 p-3">
              <h6 class="mb-0">Activity Overview</h6>
            </div>
            <div class="card-body p-3">
              <div class="timeline timeline-one-side">
                <div class="timeline-block mb-3">
                  <span class="timeline-step">
                    <i class="fa fa-bell text-success"></i>
                  </span>
                  <div class="timeline-content">
                    <h6 class="text-dark text-sm font-weight-bold mb-0">Latest Stock Requests</h6>
                    <p class="text-secondary font-weight-bold text-xs mt-1 mb-0">{{ now()->format('M d, Y') }}</p>
                  </div>
                </div>
                <div class="timeline-block mb-3">
                  <span class="timeline-step">
                    <i class="fa fa-users text-primary"></i>
                  </span>
                  <div class="timeline-content">
                    <h6 class="text-dark text-sm font-weight-bold mb-0">User Management</h6>
                    <p class="text-secondary font-weight-bold text-xs mt-1 mb-0">{{ now()->format('M d, Y') }}</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      @include('layouts.footers.auth.footer')
    </div>
  </div>

  <style>
    .avatar-xl {
      width: 74px;
      height: 74px;
    }
    .border-radius-lg {
      border-radius: 0.75rem;
    }
    .form-control-label {
      margin-bottom: 0.5rem;
      font-size: 0.875rem;
      font-weight: 600;
    }
    .form-control {
      margin-bottom: 1rem;
    }
    .btn-primary {
      background-color: #821131;
      border-color: #821131;
    }
    .btn-primary:hover {
      background-color: #6a0e28;
      border-color: #6a0e28;
    }
    .timeline {
      margin: 0;
      padding: 0;
      list-style: none;
    }
    .timeline-block {
      display: flex;
      align-items: flex-start;
    }
    .timeline-step {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      background: #fff;
      border: 2px solid #e9ecef;
      display: flex;
      align-items: center;
      justify-content: center;
      margin-right: 1rem;
      flex-shrink: 0;
    }
    .timeline-content {
      flex-grow: 1;
    }
    .timeline-step i {
      font-size: 1.1rem;
    }
    .list-group-item {
      padding: 0.75rem 0;
    }
    .card {
      box-shadow: 0 4px 6px rgba(50, 50, 93, 0.1), 0 1px 3px rgba(0, 0, 0, 0.08);
    }
  </style>

  <!-- Add JavaScript for avatar upload -->
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const changeAvatarBtn = document.getElementById('change-avatar-btn');
      const avatarUpload = document.getElementById('avatar-upload');
      const avatarForm = document.getElementById('avatar-form');
      const profileImage = document.getElementById('profile-image');
      
      // Trigger file input when button is clicked
      changeAvatarBtn.addEventListener('click', function() {
        avatarUpload.click();
      });
      
      // Handle file selection
      avatarUpload.addEventListener('change', function() {
        if (this.files && this.files[0]) {
          const formData = new FormData(avatarForm);
          
          // Show loading state
          changeAvatarBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
          changeAvatarBtn.disabled = true;
          
          // Send AJAX request
          fetch('{{ route("admin.profile.avatar") }}', {
            method: 'POST',
            body: formData,
            headers: {
              'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            credentials: 'same-origin'
          })
          .then(response => response.json())
          .then(data => {
            if (data.success) {
              // Update image preview
              profileImage.src = data.path + '?t=' + new Date().getTime(); // Add timestamp to prevent caching
              
              // Reset button
              changeAvatarBtn.innerHTML = '<i class="fas fa-camera"></i>';
              changeAvatarBtn.disabled = false;
            } else {
              alert('Failed to upload image');
              changeAvatarBtn.innerHTML = '<i class="fas fa-camera"></i>';
              changeAvatarBtn.disabled = false;
            }
          })
          .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while uploading the image');
            changeAvatarBtn.innerHTML = '<i class="fas fa-camera"></i>';
            changeAvatarBtn.disabled = false;
          });
        }
      });
    });
  </script>
@endsection

