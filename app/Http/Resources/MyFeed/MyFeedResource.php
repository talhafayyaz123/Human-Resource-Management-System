<?php

namespace App\Http\Resources\MyFeed;

use App\Helpers\GlobalSettingHelper;
use App\Models\MyFeedSubscription;
use App\Traits\CustomHelper;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class MyFeedResource extends JsonResource
{
    use CustomHelper;
    public function toArray($request)
    {
        $columnName = GlobalSettingHelper::getNameOrNumberColumn($this->moduleable_type);
        return [
            'id' => $this->id,
            'userId' => $this->user_id,
            'userName' => $this->userProfileData?->full_name,
            'text' => $this->text,
            'IsVote' => $this->is_vote,
            'pollFinished' => $this->poll_finished,
            'pollQuestion' => $this->poll_question,
            'pollDueDate' => $this->poll_date,
            'feedRoute' => $this->feed_path,
            'deletedAt' => isset($this->deleted_at) ?  $this->deleted_at->format('Y-m-d') : null,
            'createdAt' => $this->created_at->format('Y-m-d'),
            'comments' => $this->comments?->take(5)->map(function ($comment) {
                return [
                    'id' => $comment->id,
                    'text' => $comment->text,
                    'userId' => $comment->user_id,
                    'userName' => $comment->userProfileData?->full_name,
                    'createdTime' => $comment->created_at->format('H:i:s'),
                    'createdAt' => $comment->created_at->format('Y-m-d'),
                ];
            }),
            'createdTime' => $this->created_at->format('H:i:s'),
            'deletedTime' => isset($this->deleted_at) ?  $this->deleted_at->format('H:i:s') : null,
            'commentsCount' => $this->comments?->count(),
            'isSubscribed' => MyFeedSubscription::where('module_id', $this->moduleable_id)->where('module_type', $this->moduleable_type)
                ->where('user_id', $this->getAuthUserId($request))->first() ? 1 : 0,
            'images' => $this->images?->map(function ($img) {
                return [
                    'name' => $img->viewable_name,
                    'base64' => $this->getImageUrl($img),
                    'size' => $img->storage_size,
                ];
            }),
            'tags' => $this->tags->pluck('name'),
            'voteAnswers' => $this->voteAnswers?->map(function ($answer) use ($request) {
                return [
                    'id' => $answer->id,
                    'text' => $answer->text,
                    'totalVotes' => count($answer->answerVotes),
                    'isVoted' => count($answer->answerVotes->where('user_id', $this->getAuthUserId($request))) > 0 ? 1 : 0,
                ];
            }),
            'moduleName' => GlobalSettingHelper::getModuleName($this->moduleable_type),
            'moduleInformation' => [
                'id' => $this->moduleable?->id,
                'nameOrNumber' => @$this->moduleable?->$columnName
            ],
//            'adjustedFields' => $this->new_fields_data,
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
