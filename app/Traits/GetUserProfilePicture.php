<?php

namespace App\Traits;

use App\Models\UserProfilePicture;
use Illuminate\Support\Facades\Storage;

trait GetUserProfilePicture
{
    public function getProfilePicture(?string $user_id): ?String
    {
        if (!isset($user_id))
            return '';
        $url = '';
        $user_profile = UserProfilePicture::where('user_id', $user_id)->first();
        $file = null;

        if (isset($user_profile->storage_name)) {
            $file = Storage::get('profile_pic/' . $user_profile->storage_name);
            $mime = Storage::mimeType('profile_pic/' . $user_profile->storage_name);
            if (isset($file)) {
                $base64 = base64_encode($file);
                $url = "data:$mime;base64,$base64";
            }
        }
        return $url;
    }
}
