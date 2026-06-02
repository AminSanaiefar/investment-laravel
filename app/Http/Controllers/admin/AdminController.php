<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Services\ImageService;

class AdminController extends Controller
{
    public function AdminDashboard() {
        return view("admin.index");
    }

    public function AdminProfile() {
        return view("admin.profile", ["user"=> Auth::user()]);
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
}
