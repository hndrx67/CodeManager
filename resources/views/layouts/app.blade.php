@php($avatar = auth()->user()->avatar_url)
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
    <title>@yield('title', 'Code Manager')</title>
    <link rel="icon" href="{{ asset('images/logo.jpg') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/legacy.css') }}">
    <link rel="stylesheet" href="{{ asset('css/community.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="app-page @yield('page-class')">
<nav class="navbar">
    <div class="nav-left"><button class="menu-btn" id="toggleSidebar"><i class="fa-solid fa-bars"></i></button><div class="brand-title"><i class="fa-solid fa-code"></i> Code Manager</div></div>
    <div class="nav-right"><a href="{{ route('profile.edit') }}" class="profile-card-nav">@if($avatar)<img src="{{ $avatar }}" class="profile-avatar-nav" alt="Avatar">@else<span class="profile-avatar-nav initial">{{ strtoupper(substr(auth()->user()->fullname, 0, 1)) }}</span>@endif<div class="profile-info-nav"><span class="profile-name-nav">{{ auth()->user()->fullname }}</span><span class="profile-role-nav">{{ '@'.auth()->user()->username }}{{ auth()->user()->is_admin ? ' · Administrator' : '' }}</span></div></a></div>
</nav>
<div class="main-container">
    <aside class="sidebar" id="sidebar">
        <ul class="sidebar-menu">
            <li><a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}"><i class="fa-solid fa-house"></i><span class="link-text">Home</span></a></li>
            <li><a href="{{ route('snippets.index') }}" class="{{ request()->routeIs('snippets.*') ? 'active' : '' }}"><i class="fa-solid fa-code"></i><span class="link-text">Codes</span></a></li>
            <li><a href="{{ route('profile.edit') }}" class="{{ request()->routeIs('profile.*') ? 'active' : '' }}"><i class="fa-solid fa-user"></i><span class="link-text">Profile</span></a></li>
            @if(auth()->user()->is_admin)<li><a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.*') ? 'active' : '' }}"><i class="fa-solid fa-user-shield"></i><span class="link-text">Admin Dashboard</span></a></li>@endif
        </ul>
        <ul class="sidebar-menu"><li class="logout-item"><a href="#" id="logoutBtn"><i class="fa-solid fa-right-from-bracket"></i><span class="link-text">Logout</span></a></li></ul>
    </aside>
    <main class="content">@yield('content')</main>
</div>
<footer>&copy; <span class="highlight-author">Winnce Tubtub</span> 2026 | Powered by <span class="highlight-xampp">XAMPP</span> & <span class="highlight-php">Laravel</span></footer>
<form id="logoutForm" method="POST" action="{{ route('logout') }}" hidden>@csrf</form>
<script>
document.getElementById('toggleSidebar').onclick=()=>document.getElementById('sidebar').classList.toggle('collapsed');
document.getElementById('logoutBtn').onclick=e=>{e.preventDefault();Swal.fire({title:'Are you sure?',text:'You will be logged out of your session.',icon:'warning',showCancelButton:true,confirmButtonColor:'#ef4444',cancelButtonColor:'#334155',confirmButtonText:'Yes, Logout',background:'#121624',color:'#e0e6ed'}).then(r=>{if(r.isConfirmed)document.getElementById('logoutForm').submit()})};
@if(session('success')) Swal.fire({icon:'success',title:'Success!',text:@json(session('success')),background:'#121624',color:'#e0e6ed',confirmButtonColor:'#00b4d8'}); @endif
@if($errors->any()) Swal.fire({icon:'error',title:'Error',text:@json($errors->first()),background:'#121624',color:'#e0e6ed',confirmButtonColor:'#00b4d8'}); @endif
</script>
@stack('scripts')
</body></html>
