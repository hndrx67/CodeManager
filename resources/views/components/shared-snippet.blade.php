@props(['snippet'])
<article class="shared-card">
    <div class="shared-author">
        @if($snippet->user->avatar_url)<img src="{{ $snippet->user->avatar_url }}" alt="{{ $snippet->user->fullname }}">@else<span>{{ strtoupper(substr($snippet->user->fullname, 0, 1)) }}</span>@endif
        <div><strong>{{ $snippet->user->fullname }}</strong><small>{{ '@'.$snippet->user->username }}</small></div>
        <span class="language-badge">{{ strtoupper($snippet->language) }}</span>
    </div>
    <h3>{{ $snippet->title }}</h3><p>{{ $snippet->description ?: 'Shared with the Code Manager community.' }}</p>
    <pre><code>{{ Str::limit($snippet->code, 320) }}</code></pre>
    <button class="btn-action copy-shared" data-code="{{ base64_encode($snippet->code) }}"><i class="fa-regular fa-copy"></i> Copy code</button>
</article>
