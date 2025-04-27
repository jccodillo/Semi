<div class="fixed-plugin">
    <div class="card shadow-lg">
      <div class="card-header pb-0 pt-3">
        <div class="{{ (Request::is('rtl') ? 'float-end' : 'float-start') }}">
          <h5 class="mt-3 mb-0">Profile Settings</h5>
          <p>Manage your account</p>
        </div>
        <div class="{{ (Request::is('rtl') ? 'float-start mt-4' : 'float-end mt-4') }}">
          <button class="btn btn-link text-dark p-0 fixed-plugin-close-button">
            <i class="fa fa-close"></i>
          </button>
        </div>
      </div>
      <div class="card-body pt-sm-3 pt-0">
        <!-- Profile Management -->
        <div class="mt-2">
          <h6 class="mb-0">Account Options</h6>
          <p class="text-sm">Update your profile information</p>
          
          <a class="btn bg-gradient-primary w-100 mb-2" href="/profile/edit">
            <i class="fa fa-user me-2"></i> Edit Profile
          </a>
          
          <a class="btn bg-gradient-info w-100 mb-2" href="/profile/password">
            <i class="fa fa-lock me-2"></i> Change Password
          </a>
          
          <a class="btn btn-outline-danger w-100" href="/logout">
            <i class="fa fa-sign-out me-2"></i> Logout
          </a>
        </div>
      </div>
    </div>
  </div>