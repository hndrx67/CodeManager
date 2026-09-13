<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Snippet;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $users = User::query()
            ->withCount('snippets')
            ->when(trim((string) $request->query('q')), function ($query, $search) {
                $query->where(fn ($q) => $q->where('fullname', 'like', "%{$search}%")
                    ->orWhere('username', 'like', "%{$search}%"));
            })
            ->latest()->paginate(15)->withQueryString();

        return view('admin.dashboard', [
            'users' => $users,
            'userCount' => User::count(),
            'adminCount' => User::where('is_admin', true)->count(),
            'snippetCount' => Snippet::count(),
        ]);
    }

    public function updateRole(Request $request, User $user): RedirectResponse
    {
        $data = $request->validate(['is_admin' => ['required', 'boolean']]);
        if ($user->is($request->user()) && ! $data['is_admin']) {
            return back()->withErrors(['admin' => 'You cannot remove your own administrator access.']);
        }

        $user->forceFill(['is_admin' => $data['is_admin']])->save();

        return back()->with('success', $user->is_admin ? 'Administrator access granted.' : 'Administrator access revoked.');
    }

    public function destroy(Request $request, User $user): RedirectResponse
    {
        if ($user->is($request->user())) {
            return back()->withErrors(['admin' => 'You cannot delete your own account.']);
        }
        if ($user->profile_pic && ! str_starts_with($user->profile_pic, 'uploads/')) {
            Storage::disk('public')->delete($user->profile_pic);
        }
        $user->delete();

        return back()->with('success', 'User account deleted.');
    }
}
