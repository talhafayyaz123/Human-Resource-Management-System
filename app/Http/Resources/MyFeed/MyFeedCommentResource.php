<?php

namespace App\Http\Resources\MyFeed;

use App\Helpers\GlobalSettingHelper;
use App\Models\MyFeedSubscription;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class MyFeedCommentResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'userId' => $this->user_id,
            'userName' => $this->userProfileData?->full_name,
            'text' => $this->text,
            'createdAt' => $this->created_at->format('Y-m-d'),
            'createdTime' => $this->created_at->format('H:i:s'),
            'image' => $this->getImageUrl($this->image),
            'tags' => $this->tags->pluck('name'),
            'mentionUserIds' => $this->mentions()->pluck('user_id'),
        ];
    }

    private function getImageUrl($image)
    {
        $file = null;
        if (isset($image->storage_name)) {
            $file = Storage::disk('public')->get('myFeed/' . $image->storage_name);
        }
        if (!$file) {
            return null;
        }
        $mime = Storage::disk('public')->mimeType('myFeed/' . $image->storage_name);
        $base64 = base64_encode($file);
        return "data:$mime;base64,$base64";
    }
}
