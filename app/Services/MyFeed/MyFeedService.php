<?php

namespace App\Services\MyFeed;

use App\Models\HashTag;
use App\Models\MyFeed;
use App\Models\MyFeedMention;
use App\Models\MyFeedVoteQuestion;
use App\Models\UploadedFile;
use App\Traits\CustomHelper;
use Illuminate\Support\Facades\Storage;

class MyFeedService
{
    use CustomHelper;

    protected $model;

    public function __construct()
    {
        $this->model = new MyFeed();
    }

    public function create($data)
    {
        $model = new $this->model;
        $model->user_id = $data['userId'];
        $model->text = $data['text'];
        $model->is_vote = $data['isVote'];

        $model->poll_finished = $data['pollFinished'] ?? 0;
        $model->poll_question = $data['pollQuestion'] ?? null;
        $model->poll_date = $data['pollDueDate'] ?? null;

        if (isset($data['moduleableType']) && $data['moduleableType']) {
            $model->moduleable_type = $data['moduleableType'];
        }

        if (isset($data['moduleableId']) && $data['moduleableId']) {
            $model->moduleable_id = $data['moduleableId'];
        }
        if (isset($data['currentRoutePath']) && $data['currentRoutePath']) {
            $model->feed_path = $data['currentRoutePath'];
        }

        $model->save();
        return $model;
    }

    public function update($data, $id)
    {
        $model = $this->model->where('id', $id)->first();
        $model->user_id = $data['userId'];
        $model->text = $data['text'];
        $model->is_vote = $data['isVote'];
        $model->poll_finished = $data['pollFinished'] ;
        $model->poll_question = $data['pollQuestion'] ;
        $model->poll_date = $data['pollDueDate'];
//        if (isset($data['moduleableType']) && $data['moduleableType']) {
//            $model->moduleable_type = $data['moduleableType'];
//        }
//
//        if (isset($data['moduleableId']) && $data['moduleableId']) {
//            $model->moduleable_id = $data['moduleableId'];
//        }

        $model->save();
        return $model;
    }

    public function saveImage($myFeed, $images): void
    {
        foreach ($images as $image) {
            $originalName = $image['name'];
            $base64Decode = base64_decode($image['base64'], true);
            // Generate a unique file name
            $fileNameToStore = time() . '.' . $originalName;
            // Save the decoded file to disk
            Storage::disk('public')->put('myFeed/' . $fileNameToStore, $base64Decode);
            $uploaded_file = new UploadedFile();
            $uploaded_file->storage_name = $fileNameToStore;
            $uploaded_file->viewable_name = $originalName;
            $uploaded_file->storage_size = $image['size'];
            $uploaded_file->fileable()->associate($myFeed);
            $uploaded_file->save();
        }
    }

    public function saveMentionUsers($myFeed, $users): void
    {
        $myFeed->mentions()->delete();
        foreach ($users as $user) {
            MyFeedMention::firstOrCreate([
                'user_id' => $user,
                'morphable_id' => $myFeed->id,
                'morphable_type' => get_class($myFeed),
            ]);
            // $mention->morphable()->associate($myFeed);
            // $mention->save();
        }
    }

    public function saveVoteAnswers($myFeed, $answers): void
    {
        $ids = [];
        foreach ($answers as $answer) {
            $answer = MyFeedVoteQuestion::firstOrNew([
                'my_feed_id' => $myFeed->id,
                'text' => $answer
            ]);
            $answer->save();
            $ids[] = $answer->id;
        }
        $myFeed->voteAnswers()->whereNotIn('id', $ids)->delete();
    }

    public function getMyOwnFeed($params)
    {
        $model = $this->model->withCount(['comments']);
        if (isset($params['search']) && $params['search']) {
            $model->where(function ($query) use ($params) {
                $query->where('text', 'like', '%' . $params['search'] . '%');
                $query->orWhereHas('comments', function ($q) use ($params) {
                    $q->where('text', 'like', '%' . $params['search'] . '%');
                });
                $query->orWhereHas('userProfileData', function ($q) use ($params) {
                    $q->where('first_name', 'like', '%' . $params['search'] . '%');
                    $q->where('last_name', 'like', '%' . $params['search'] . '%');
                });
                $query->orWhereHas('voteAnswers', function ($q) use ($params) {
                    $q->where('text', 'like', '%' . $params['search'] . '%');
                });
                $query->orWhereHas('tags', function ($q) use ($params) {
                    $q->where('name', 'like', '%' . $params['search'] . '%');
                });
            });
        }
        return $model->where('user_id', $params['userId'])
            ->orderBy('created_at', 'desc')->paginate($params['perPage']);
    }

    public function savehashTags($myFeed, $tags)
    {
        $tagIds = [];
        foreach ($tags as $tag) {
            $hashTag = HashTag::firstOrNew(['name' => $tag]);
            $hashTag->save();
            $tagIds[] = $hashTag->id;
        }
        $myFeed->tags()->sync($tagIds);
    }

    public function getMentionFeed($params)
    {
        $model = $this->model->withCount(['comments'])->with(['mentions']);
        if (isset($params['search']) && $params['search']) {
            $model->where(function ($query) use ($params) {
                $query->where('text', 'like', '%' . $params['search'] . '%');
                $query->orWhereHas('comments', function ($q) use ($params) {
                    $q->where('text', 'like', '%' . $params['search'] . '%');
                });
                $query->orWhereHas('userProfileData', function ($q) use ($params) {
                    $q->where('first_name', 'like', '%' . $params['search'] . '%');
                    $q->where('last_name', 'like', '%' . $params['search'] . '%');
                });
                $query->orWhereHas('voteAnswers', function ($q) use ($params) {
                    $q->where('text', 'like', '%' . $params['search'] . '%');
                });
                $query->orWhereHas('tags', function ($q) use ($params) {
                    $q->where('name', 'like', '%' . $params['search'] . '%');
                });
            });
        }

        return $model->where(function ($query) use ($params) {
            $query->whereHas('mentions', function ($mentionQuery) use ($params) {
                $mentionQuery->where('user_id', $params['userId']);
            })
            ->orWhereHas('comments.mentions', function ($commentMentionQuery) use ($params) {
                $commentMentionQuery->where('user_id', $params['userId']);
            });
        })
            ->orderBy('created_at', 'desc')
            ->paginate($params['perPage']);
    }

    public function getSubscribedFeed($params)
    {
        $model = $this->model->withCount(['comments']);
        if (isset($params['search']) && $params['search']) {
            $model->where(function ($query) use ($params) {
                $query->where('text', 'like', '%' . $params['search'] . '%');
                $query->orWhereHas('comments', function ($q) use ($params) {
                    $q->where('text', 'like', '%' . $params['search'] . '%');
                });
                $query->orWhereHas('userProfileData', function ($q) use ($params) {
                    $q->where('first_name', 'like', '%' . $params['search'] . '%');
                    $q->where('last_name', 'like', '%' . $params['search'] . '%');
                });
                $query->orWhereHas('voteAnswers', function ($q) use ($params) {
                    $q->where('text', 'like', '%' . $params['search'] . '%');
                });
                $query->orWhereHas('tags', function ($q) use ($params) {
                    $q->where('name', 'like', '%' . $params['search'] . '%');
                });
            });
        }
        return $model->where('user_id', '=', $params['userId'])
            ->whereIn('moduleable_id', $params['moduleIds'])
            ->whereIn('moduleable_type', $params['moduleNames'])
            ->orderBy('created_at', 'desc')->paginate($params['perPage']);
    }

    public function getModuleFeed($params)
    {
        $search = $params['search'] ?? '';
        if(!empty($search) && str_contains($search, '@')){
            $search = '';
        }


        return $this->model
            ->where('moduleable_id', $params['moduleId'])
            ->where('moduleable_type', $params['moduleName'])
            ->when(!empty($search), function ($query) use($search){
                $query->where('text', 'like', '%'.$search.'%');
            })
            ->when($params['mentionUserId'], function ($query) use($params){
                $query->where('user_id',  $params['mentionUserId']);
            })
            ->orderBy('created_at', 'desc')
            ->paginate($params['perPage']);
    }

    public function getTrashedModuleFeed($params)
    {
        $search = $params['search'] ?? '';
        if(!empty($search) && str_contains($search, '@')){
            $search = '';
        }

        return $this->model
            ->onlyTrashed()
            ->where('moduleable_id', $params['moduleId'])
            ->where('moduleable_type', $params['moduleName'])
            ->when(!empty($search), function ($query) use($search){
                $query->where('text', 'like', '%'.$search.'%');
            })
            ->when($params['mentionUserId'], function ($query) use($params){
                $query->where('user_id',  $params['mentionUserId']);
            })
            ->orderBy('created_at', 'desc')
            ->paginate($params['perPage']);
    }
}
