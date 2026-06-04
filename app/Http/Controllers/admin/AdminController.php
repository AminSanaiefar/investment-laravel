<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Services\ImageService;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function AdminDashboard() {
        return view("admin.index");
    }

    public function AdminProfile() {
        return view("admin.profile");
    }

    public function AdminProfileUpdate(Request $request, ImageService $imageService) {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'address' => 'required|string|max:1000',
            'phone' => 'required|string|max:11',
            'photo' => 'mimes:jpeg,jpg,png|max:2048',
        ]);

        $user = Auth::User();
        $notification = array(
            'message' => 'your personal infomation successfully updated.',
            'alert-type' => 'success'
        );

        if ($request->hasFile('photo')) {

            if (!empty($user->photo) && file_exists(public_path($user->photo))) {
                if (!$imageService->deleteImage($user->photo)) {
                    $notification['message'] = 'failed to replace old photo.';
                    $notification['alert-type'] = 'error';
                }
            }

            $imagePath = $imageService->saveImage($request->file('photo'),
                'upload/admin-profiles/', 
                imageSize: ['width'=>500, 'height'=>500]
            );

            $user->update(
                $request->only(['first_name', 'last_name', 'email', 'address', 'phone']) +
                ['photo' => $imagePath ]
            );
        } else {
            $user->update(
                $request->only(['first_name', 'last_name', 'email', 'address', 'phone'])
            );
        }
        return redirect()->back()->with($notification);
    }

    public function AdminPasswordUpdate(Request $request) {
        $request->validate([
            'current_password' => 'required|min:8',
            'new_password' => 'required|min:8',
            'password_confirmation' => 'required|min:8|same:new_password'
        ]);

        $notification = array(
            'message' => 'Password updated successfully.',
            'alert-type' => 'success',
        );
        
        if (Hash::check($request->input('current_password'), Auth::user()->password)) {
            Auth::user()->update(['password' => $request->input('new_password')]);
            
            // log out the user
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            
            return redirect()->back()->with($notification);
        } else {
            $notification['message'] = 'Current password is invalid';
            $notification['alert-type'] = 'error';

            return redirect()->back()->withInput()->with($notification)->withErrors(['current_password' => 'current password is invalid.']);
        }
    }
}
