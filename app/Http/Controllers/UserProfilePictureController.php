<?php

namespace App\Http\Controllers;

use App\Models\UserProfilePicture;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserProfilePictureController extends Controller
{
    /**
     * Runs when instance is initiated
     */
    public function __construct()
    {
        //  $this->middleware('check.permission:user-profile-picture.show', ['only' => ['getProfilePic']]);
        $this->middleware('check.permission:user-profile-picture.create', ['only' => ['uploadProfilePicture']]);
    }

    /**
     * Upload the profile picture
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function uploadProfilePicture(Request $request)
    {
        $request->validate([
            'userId' => 'required',
            'file' => 'required'
        ]);
        $user = UserProfilePicture::firstOrNew(['user_id' => $request->userId]);
        if (isset($user->storage_name)) {
            Storage::delete('profile_pic/' . $user->storage_name);
        }
        $file = $request->file;
        $original_name = $file->getClientOriginalName();
        $file_name_to_store = $request->userId . '_' . $original_name;
        Storage::disk('local')->putFileAs('profile_pic/', $file, $file_name_to_store);
        $user->storage_name = $file_name_to_store;
        $user->user_id = $request->userId;
        $user->save();
        return response()->json(['message' => 'Profile Pic Saved'], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function getProfilePic($user_id)
    {
        $user = UserProfilePicture::where('user_id', $user_id)->first();
        $file = null;
        if (isset($user->storage_name)) {
            $file = Storage::get('profile_pic/' . $user->storage_name);
        }
        if (!$file) {
            return response()->json();
        }
        $mime = Storage::mimeType('profile_pic/' . $user->storage_name);
        $base64 = base64_encode($file);
        $url = "data:$mime;base64,$base64";
        return response()->json(['url' => $url]);
    }

    public function updateProfilePicture(Request $request)
    {
        $request->validate([
            'userId' => 'required',
            'attachment' => 'required'
        ]);
        try {
            $user = UserProfilePicture::firstOrNew(['user_id' => $request->userId]);
            if (isset($user->storage_name)) {
                Storage::delete('profile_pic/' . $user->storage_name);
            }
            $attachment = $request->attachment;
            $original_name = $attachment['name'];
            $base64Decode = base64_decode($attachment['base64'], true);

            // Generate a unique file name
            $file_name_to_store = $request->userId . '_' . $original_name;

            // Save the decoded file to disk

            Storage::disk('local')->put('profile_pic/' . $file_name_to_store, $base64Decode);

            $user->storage_name = $file_name_to_store;
            $user->user_id = $request->userId;
            $user->save();
            return response()->json(['message' => 'Profile Pic Saved'], 200);
        } catch (\Throwable $exception) {
            return response()->json(['message' => 'Something went wrong'], 400);
        }
    }
}
