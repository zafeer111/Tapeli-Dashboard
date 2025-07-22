<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */

    public function profile()
    {
        return view('utility.profile', ['user' => Auth::user()]);
    }
    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name' => 'required|string|min:3|max:255',
            'contact_number' => 'required|string|max:20',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);


        if ($request->hasFile('profile_image')) {
            $oldPath = public_path('profile_images/' . $user->profile_image);
            if ($user->profile_image && file_exists($oldPath)) {
                unlink($oldPath);
            }

            $image = $request->file('profile_image');
            $filename = uniqid() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images/profile_images'), $filename);

            $user->profile_image = $filename;
        }

        $user->name = $request->name;
        $user->contact_number = $request->contact_number;
        $user->save();

        return redirect()->back()->with('success', 'Profile updated successfully!');
    }

    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'old_password' => 'required',
            'new_password' => 'required|confirmed|min:8',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator, 'changePassword')
                ->withInput();
        }

        $user = auth()->user();

        if (!Hash::check($request->old_password, $user->password)) {
            return back()->with('change-pass-error', 'Old password is incorrect.');
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return back()->with('success', 'Password changed successfully!');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|confirmed|min:8',
            'password_confirmation' => 'required|string|min:8'
        ]);
        // return $request->all();
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }
}
