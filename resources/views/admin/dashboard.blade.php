@extends('layouts.app')
@section('title', 'Admin Dashboard - Code Manager')
@section('page-class', 'admin-page')
@section('content')
<div class="header-section"><div class="header-title"><h1>Admin Dashboard <i class="fa-solid fa-user-shield"></i></h1><p>Manage accounts and monitor the Code Manager application.</p></div></div>
<div class="admin-stats"><div class="card"><i class="fa-solid fa-users"></i><strong>{{ $userCount }}</strong><span>Total Accounts</span></div><div class="card"><i class="fa-solid fa-user-shield"></i><strong>{{ $adminCount }}</strong><span>Administrators</span></div><div class="card"><i class="fa-solid fa-code"></i><strong>{{ $snippetCount }}</strong><span>Saved Snippets</span></div></div>
<div class="card admin-users">
<div class="admin-toolbar"><div class="card-title"><i class="fa-solid fa-users-gear"></i> Account Management</div><form method="GET" class="admin-search"><input class="form-control" name="q" value="{{ request('q') }}" placeholder="Search name or username"><button class="btn-submit"><i class="fa-solid fa-search"></i></button></form></div>
<div class="table-wrap"><table><thead><tr><th>Account</th><th>Role</th><th>Snippets</th><th>Registered</th><th>Actions</th></tr></thead><tbody>
@foreach($users as $user)
<tr><td><div class="admin-account">@if($user->avatar_url)<img class="admin-avatar" src="{{ $user->avatar_url }}" alt="{{ $user->fullname }}">@else<span class="admin-avatar">{{ strtoupper(substr($user->fullname, 0, 1)) }}</span>@endif<div><strong>{{ $user->fullname }}</strong><small>{{ '@'.$user->username }} @if($user->is(auth()->user()))(You)@endif</small></div></div></td><td><span class="role-badge {{ $user->is_admin ? 'admin' : '' }}">{{ $user->is_admin ? 'Administrator' : 'User' }}</span></td><td>{{ $user->snippets_count }}</td><td>{{ optional($user->created_at)->format('M d, Y') ?: 'Legacy account' }}</td><td><div class="admin-actions">
@if(!$user->is(auth()->user()))
<form method="POST" action="{{ route('admin.users.role', $user) }}">@csrf @method('PATCH')<input type="hidden" name="is_admin" value="{{ $user->is_admin ? 0 : 1 }}"><button class="btn-action" title="{{ $user->is_admin ? 'Revoke administrator' : 'Grant administrator' }}"><i class="fa-solid {{ $user->is_admin ? 'fa-user-minus' : 'fa-user-shield' }}"></i></button></form>
<form method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('Permanently delete this account and all its snippets?')">@csrf @method('DELETE')<button class="btn-action delete" title="Delete account"><i class="fa-solid fa-trash"></i></button></form>
@else<span class="self-protected">Protected</span>@endif
</div></td></tr>
@endforeach
</tbody></table></div>{{ $users->links() }}</div>
@endsection
