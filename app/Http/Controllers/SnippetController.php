<?php

namespace App\Http\Controllers;

use App\Models\Snippet;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class SnippetController extends Controller
{
    public function index(Request $request): View
    {
        $query = $request->user()->snippets()->latest('updated_at');
        if ($search = trim((string) $request->query('q'))) {
            $query->where(fn ($q) => $q->where('title', 'like', "%{$search}%")->orWhere('description', 'like', "%{$search}%"));
        }
        $category = (string) $request->query('category');
        if ($category === 'web') {
            $query->whereIn('language', ['html', 'css', 'javascript']);
        } elseif (in_array($category, ['java', 'php', 'python', 'sql', 'text'], true)) {
            $query->where('language', $category);
        }

        return view('snippets.index', ['snippets' => $query->paginate(12)->withQueryString()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->user()->snippets()->create($this->validated($request));

        return back()->with('success', 'Snippet saved.');
    }

    public function update(Request $request, Snippet $snippet): RedirectResponse
    {
        abort_unless($snippet->user_id === $request->user()->id, 403);
        $snippet->update($this->validated($request));

        return back()->with('success', 'Snippet updated.');
    }

    public function destroy(Request $request, Snippet $snippet): RedirectResponse
    {
        abort_unless($snippet->user_id === $request->user()->id, 403);
        $snippet->delete();

        return back()->with('success', 'Snippet deleted.');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:120'],
            'language' => ['required', Rule::in(['html', 'css', 'javascript', 'php', 'sql', 'python', 'java', 'text'])],
            'description' => ['nullable', 'string', 'max:500'],
            'code' => ['required', 'string', 'max:100000'],
            'is_public' => ['sometimes', 'boolean'],
        ]);

        $data['is_public'] = $request->boolean('is_public');

        return $data;
    }
}
