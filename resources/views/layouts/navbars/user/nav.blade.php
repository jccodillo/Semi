<!-- Navbar -->
<nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur" navbar-scroll="true">
    <div class="container-fluid py-2 px-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
            <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark fs-6" href="javascript:;">{{ Auth::user()->name }}</a></li>
            <li class="breadcrumb-item text-sm text-dark active text-capitalize fs-6" aria-current="page">{{ str_replace('-', ' ', Request::path()) }}</li>
            </ol>
            <h6 class="font-weight-bolder mb-0 text-capitalize fs-5">{{ str_replace('-', ' ', Request::path()) }}</h6>
        </nav>
        <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4 d-flex justify-content-end" id="navbar"> 
            <ul class="navbar-nav justify-content-end">
            <li class="nav-item d-flex align-items-center">
                <span class="nav-link text-body font-weight-bold px-0">
                    @if(Auth::user()->avatar)
                    <img src="{{ Auth::user()->avatar }}" class="avatar avatar-sm me-1 rounded-circle" alt="user image">
                    @else
                    <i class="fa fa-user me-sm-1 fs-5"></i>
                    @endif
                    <span class="d-sm-inline d-none fs-6">{{ Auth::user()->name }}</span>
                </span>
            </li>
            <li class="nav-item dropdown pe-2 d-flex align-items-center">
                <a href="{{ route('messages.index') }}" class="nav-link text-body p-0 icon-link position-relative navbar-icon">
                    <i class="fas fa-comments cursor-pointer fs-5" style="{{ Request::is('messages*') ? 'color: #821131;' : '' }}"></i>
                    <span id="nav-unread-count" class="notification-badge" style="display: none;"></span>
                </a>
            </li>
            <li class="nav-item d-xl-none ps-3 d-flex align-items-center">
                <a href="javascript:;" class="nav-link text-body p-0" id="iconNavbarSidenav">
                <div class="sidenav-toggler-inner">
                    <i class="sidenav-toggler-line"></i>
                    <i class="sidenav-toggler-line"></i>
                    <i class="sidenav-toggler-line"></i>
                </div>
                </a>
            </li>
            <li class="nav-item px-4 d-flex align-items-center">
                <a href="javascript:;" class="nav-link text-body p-0 icon-link navbar-icon" id="settingsDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fa fa-cog fixed-plugin-button-nav cursor-pointer fs-5" style="{{ Request::is('user-profile') ? 'color: #821131;' : '' }}"></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-end settings-dropdown" aria-labelledby="settingsDropdown">
                    <li>
                        <a class="dropdown-item" href="{{ url('/user-profile') }}">
                            @if(Auth::user()->avatar)
                            <img src="{{ Auth::user()->avatar }}" class="avatar avatar-xs me-2 rounded-circle" alt="user image" style="width: 18px; height: 18px;">
                            @else
                            <i class="fa fa-user me-2"></i>
                            @endif
                            Profile
                        </a>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <a class="dropdown-item text-danger" href="{{ url('/logout')}}">
                            <i class="fa fa-sign-out me-2"></i> Sign Out
                        </a>
                    </li>
                </ul>
            </li>
            <li class="nav-item dropdown pe-2 d-flex align-items-center">
                <a href="{{ url('/user/view-requests') }}" class="nav-link text-body p-0 icon-link position-relative navbar-icon">
                    <i class="fa fa-bell cursor-pointer fs-5" style="{{ Request::is('user/view-requests') ? 'color: #821131;' : '' }}"></i>
                    @if(isset($requests) && $requests->where('status', 'approved')->count() > 0)
                        <span class="notification-badge"></span>
                    @endif
                </a>
            </li>
            </ul>
        </div>
    </div>
</nav>

<style>
.avatar-sm {
    width: 30px;
    height: 30px;
    object-fit: cover;
}
.avatar-xs {
    width: 18px;
    height: 18px;
    object-fit: cover;
}
.notification-badge {
    position: absolute;
    top: -5px;
    right: -5px;
    width: 10px;
    height: 10px;
    border-radius: 50%;
    background-color: #821131;
    display: block;
}
.navbar-icon {
    margin: 0 10px;
}
.navbar-icon i:hover {
    color: #821131;
    transition: color 0.3s ease;
}
.dropdown-item:hover {
    background-color: #f8f9fa;
    color: #821131;
}
.dropdown-item.text-danger {
    color: #821131 !important;
}
</style>

