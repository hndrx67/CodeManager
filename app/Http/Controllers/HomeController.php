<?php

namespace App\Http\Controllers;

use App\Models\Snippet;
use App\Models\User;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function landing(): View
    {
        return view('landing', [
            'sharedSnippets' => $this->sharedSnippets(6)->get(),
            'memberCount' => User::count(),
        ]);
    }

    public function dashboard(): View
    {
        return view('dashboard', [
            'sharedSnippets' => Snippet::query()->with('user')->where('is_public', true)->latest()->paginate(12),
        ]);
    }

    private function sharedSnippets(int $limit)
    {
        return Snippet::query()->with('user')->where('is_public', true)->latest()->limit($limit);
    }
}
