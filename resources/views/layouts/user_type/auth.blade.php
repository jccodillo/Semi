@extends('layouts.app')

@section('auth')
    @if(\Request::is('static-sign-up') || \Request::is('static-sign-in')) 
        @include('layouts.navbars.guest.nav')
        @yield('content')
        @include('layouts.footers.guest.footer')
    @else
        @auth
            @if(auth()->user()->role === 'admin')
                @include('layouts.navbars.auth.sidebar')
                <main class="main-content position-relative max-height-vh-100 h-100 mt-1 border-radius-lg {{ (Request::is('rtl') ? 'overflow-hidden' : '') }}">
                @include('layouts.navbars.auth.nav')
                <div class="container-fluid py-4">
                    @yield('content')
                    @include('layouts.footers.auth.footer')
                </div>
            </main>
            @else
                @include('layouts.navbars.user.sidebar')
                <main class="main-content position-relative max-height-vh-100 h-100 mt-1 border-radius-lg {{ (Request::is('rtl') ? 'overflow-hidden' : '') }}">
                @include('layouts.navbars.user.nav')
                <div class="container-fluid py-4">
                    @yield('content')
                    @include('layouts.footers.auth.footer')
                </div>
            </main>
            @endif

            @include('components.fixed-plugin')
        @else
            @include('layouts.navbars.guest.nav')
            @yield('content')
            @include('layouts.footers.guest.footer')
        @endauth
    @endif
@endsection

@push('scripts')
    <script src="{{ asset('assets/js/core/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/core/bootstrap.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @stack('dashboard-scripts')
    <script src="{{ asset('js/dashboard-charts.js') }}"></script>
@endpush