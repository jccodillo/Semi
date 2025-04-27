@extends('layouts.app')

@section('guest')
    @if(\Request::is('login/forgot-password')) 
        @include('layouts.navbars.guest.nav')
        @yield('content') 
    @elseif(\Request::is('/'))
        <div class="main-content">
            {{-- Welcome page layout --}}
            <nav class="navbar navbar-expand-lg position-absolute top-0 z-index-3 w-100 shadow-none my-3 navbar-transparent mt-4">
                <div class="container">
                    <div class="collapse navbar-collapse" id="navigation">
                        <ul class="navbar-nav ms-auto">
                            @if (Route::has('login'))
                                <div class="buttons">
                                    @auth
                                        <a href="{{ url('/dashboard') }}" class="btn btn-sm bg-gradient-primary btn-round mb-0 me-1">Dashboard</a>
                                    @else
                                        <a href="{{ route('login') }}" class="btn btn-sm bg-gradient-primary btn-round mb-0 me-1">Login</a>
                                        @if (Route::has('register'))
                                            <a href="{{ route('register') }}" class="btn btn-sm bg-gradient-info btn-round mb-0 me-1">Register</a>
                                        @endif
                                    @endauth
                                </div>
                            @endif
                        </ul>
                    </div>
                </div>
            </nav>
            
            {{-- Add a content section for the welcome page --}}
            <div class="container mt-5">
                @yield('content')
            </div>
        </div>
    @else
        {{-- Other guest pages layout --}}
        <div class="container position-sticky z-index-sticky top-0">
            <div class="row">
                <div class="col-12">
                    @include('layouts.navbars.guest.nav')
                </div>
            </div>
        </div>
        @yield('content')        
        @include('layouts.footers.guest.footer')
    @endif
@endsection