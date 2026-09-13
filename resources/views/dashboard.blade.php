@extends('layouts.app')
@section('title', 'Home - Code Manager')
@section('page-class', 'dashboard-page')
@section('content')
<div class="welcome-card"><h1>Welcome back, <span class="username-highlight">{{ auth()->user()->fullname }}</span>! 👋</h1><p>Manage your code library and discover snippets shared by the community.</p></div>
<div class="community-heading"><div class="header-title"><h1>Community Snippets <i class="fa-solid fa-earth-americas"></i></h1><p>Reusable code shared publicly by Code Manager members.</p></div><a class="add-code-btn" href="{{ route('snippets.index') }}"><i class="fa-solid fa-plus"></i> Share a Snippet</a></div>
@if($sharedSnippets->isEmpty())<div class="empty-state"><i class="fa-solid fa-users"></i><h3>No Community Snippets Yet</h3><p>Be the first member to share a useful snippet.</p></div>@else<div class="shared-grid">@foreach($sharedSnippets as $snippet)<x-shared-snippet :snippet="$snippet" />@endforeach</div>{{ $sharedSnippets->links() }}@endif
@endsection
@push('scripts')<script>document.querySelectorAll('.copy-shared').forEach(button=>button.onclick=()=>{navigator.clipboard.writeText(atob(button.dataset.code));button.innerHTML='<i class="fa-solid fa-check"></i> Copied';setTimeout(()=>button.innerHTML='<i class="fa-regular fa-copy"></i> Copy code',1500)})</script>@endpush
