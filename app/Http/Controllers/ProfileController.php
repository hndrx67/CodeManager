<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(): View
    {
        return view('profile.edit');
    }

    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();
        $data = $request->validate([
            'fullname' => ['required', 'string', 'max:100'],
            'username' => ['required', 'string', 'max:50', 'alpha_dash', Rule::unique('users')->ignore($user->id)],
            'profile_pic' => ['nullable', 'image', 'mimes:jpg,jpeg,png,gif,webp', 'max:2048'],
        ]);
        if ($request->hasFile('profile_pic')) {
            if ($user->profile_pic) {
                Storage::disk('public')->delete($user->profile_pic);
            }
            $data['profile_pic'] = $request->file('profile_pic')->store('avatars', 'public');
        }
        $user->update($data);

        return back()->with('success', 'Profile updated successfully.');
    }

    public function password(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);
        $request->user()->update(['password' => Hash::make($data['password'])]);

        return back()->with('success', 'Password changed successfully.');
    }
}
