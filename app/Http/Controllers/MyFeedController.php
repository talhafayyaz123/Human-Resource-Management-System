<?php

namespace App\Http\Controllers;

use App\Http\Requests\MyFeedRequest;
use App\Http\Resources\MyFeed\MyFeedCommentResource;
use App\Http\Resources\MyFeed\MyFeedResource;
use App\Models\HashTag;
use App\Models\MyFeed;
use App\Models\MyFeedComment;
use App\Models\MyFeedSubscription;
use App\Models\MyFeedVote;
use App\Models\MyTask;
use App\Services\MyFeed\MyFeedService;
use App\Traits\CustomHelper;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MyFeedController extends Controller
{
    use CustomHelper;

    protected $myFeedService;

    public function __construct(MyFeedService $myFeedService)
    {
        $this->myFeedService = $myFeedService;
    }

    public function getStats(Request $request)
    {
        $hashTag = HashTag::withCount(['feeds'])->orderBy('feeds_count', 'desc')->get();
        $tagArray = [
            'totalTags' => $hashTag->count(),
            'currentTags' => $hashTag->pluck('name')->take(3),
        ];

        $myTask = MyTask::where('creator_id', $this->getAuthUserId($request))->get();
        // will be implemented after the completion of my task.

        return response()->json([
            'tagStats' => $tagArray
        ]);
    }

    public function myOwnFeed(Request $request)
    {
        $params = [
            'perPage' => $request->get('perPage') ?? 5,
            'userId' => $this->getAuthUserId($request),
            'search' => $request->get('search') ?? null,
        ];
        $myFeeds = $this->myFeedService->getMyOwnFeed($params);
        return $myFeeds->through(function ($feed) {
            return new MyFeedResource($feed);
        });
    }

    public function getFeed($id)
    {
        try {
            $feed = MyFeed::findOrFail($id);
            // If the record is found, it will continue to the next line
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            // Handle the case where the feed with the given ID is not found
            // You might want to return an error response or perform some other action
            return response()->json(['error' => 'Feed not found'], 404);
        }
        return new MyFeedResource($feed);
    }

    public function getComments($id)
    {
        try {
            $comment = MyFeedComment::where('my_feed_id', $id)->orderBy('created_at', 'DESC')
                ->paginate(5);

            $model_collect = $comment->map(function ($comment) {
                return [
                    'id' => $comment->id,
                    'text' => $comment->text,
                    'userId' => $comment->user_id,
                    'userName' => $comment->userProfileData?->full_name,
                    'createdTime' => $comment->created_at->format('H:i:s'),
                    'createdAt' => $comment->created_at->format('Y-m-d'),
                ];
            });

            return response()->json([
                'data' => $model_collect,
                'links' => $comment->links(),
                'meta' => [
                    'current_page' => $comment->currentPage(),
                    'from' => $comment->firstItem(),
                    'last_page' => $comment->lastPage(),
                    'path' => request()->url(),
                    'per_page' => $comment->perPage(),
                    'to' => $comment->lastItem(),
                    'total' => $comment->total(),
                ]
            ], 200);
            // If the record is found, it will continue to the next line
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            // Handle the case where the feed with the given ID is not found
            // You might want to return an error response or perform some other action
            return response()->json(['error' => 'Feed not found'], 404);
        }
    }


    public function mentionFeed(Request $request)
    {
        $params = [
            'perPage' => $request->get('perPage') ?? 5,
            'userId' => $this->getAuthUserId($request),
            'search' => $request->get('search') ?? null,
        ];
        $myFeeds = $this->myFeedService->getMentionFeed($params);
        return $myFeeds->through(function ($feed) {
            return new MyFeedResource($feed);
        });
    }

    public function tagFeed(Request $request)
    {
        $params = [
            'perPage' => $request->get('perPage') ?? 5,
            'search' => $request->get('search') ?? null,
        ];

        // Get the top 3 hashtags with the highest count among associated feeds
        $topTags = HashTag::select('hash_tags.name', DB::raw('COUNT(my_feeds.id) as name_count'))
            ->join('my_feed_tags', 'hash_tags.id', '=', 'my_feed_tags.hash_tag_id')
            ->join('my_feeds', function ($join) {
                $join->on('my_feed_tags.morphable_id', '=', 'my_feeds.id')
                    ->where('my_feed_tags.morphable_type', '=', MyFeed::class);
            })
            ->groupBy('hash_tags.name')
            ->orderByDesc('name_count')
            ->limit(3)
            ->get();


        // Get the count of hashtags with associated feeds
        $totalTagsCount = HashTag::has('feeds')->count();

        $tagsFeed = HashTag::with('feeds')
            ->orwhereHas('feeds', function ($query) use ($params) {
                $query->orderBy('my_feeds.created_at', 'desc')->take(20);
            });

        if ($params['search']) {
            $tagsFeed->where(function ($query) use ($params) {
                $query->where('name', 'like', '%' . $params['search'] . '%');
                $query->orWhereHas('feeds', function ($q) use ($params) {
                    $q->where('text', 'like', '%' . $params['search'] . '%');
                });
                $query->orWhereHas('feeds.comments', function ($q) use ($params) {
                    $q->where('text', 'like', '%' . $params['search'] . '%');
                });
                $query->orWhereHas('feeds.userProfileData', function ($q) use ($params) {
                    $q->where('first_name', 'like', '%' . $params['search'] . '%');
                    $q->where('last_name', 'like', '%' . $params['search'] . '%');
                });
                $query->orWhereHas('feeds.voteAnswers', function ($q) use ($params) {
                    $q->where('text', 'like', '%' . $params['search'] . '%');
                });
            });
        }
        $tagsFeed = $tagsFeed->orderBy('hash_tags.created_at', 'desc')->paginate($params['perPage']);
        return [
            'totalTagsCount' => $totalTagsCount,
            'topTags' => $topTags,
            'tagsFeed' => $tagsFeed->through(function ($tag) {
                return [
                    'id' => $tag->id,
                    'name' => $tag->name,
                    'feeds' => MyFeedResource::collection($tag->feeds)
                ];
            }),
        ];
    }

    public function subscribedFeed(Request $request)
    {
        $myFeedSubscribed = MyFeedSubscription::where('user_id', $this->getAuthUserId($request))->get();
        $params = [
            'perPage' => $request->get('perPage') ?? 5,
            'userId' => $this->getAuthUserId($request),
            'moduleIds' => $myFeedSubscribed->pluck('module_id'),
            'moduleNames' => $myFeedSubscribed->pluck('module_type'),
        ];
        $myFeeds = $this->myFeedService->getSubscribedFeed($params);
        return $myFeeds->through(function ($feed) {
            return new MyFeedResource($feed);
        });
    }

    public function moduleFeed(Request $request)
    {
        $request->validate([
            'moduleId' => 'required',
            'moduleName' => 'required',
        ]);

        $feeds = $this->myFeedService->getModuleFeed([
            'moduleId' => $request->moduleId,
            'moduleName' => "App\Models\\" . $request->moduleName,
            'search' => $request->search ?? '',
            'mentionUserId' => $request->mentionUserId ?? '',
            'perPage' => $request->perPage ?? 10,
        ]);
        // return $feeds->through(function ($feed) {
        //     return new MyFeedResource($feed);
        // });
        // Update database if poll_date is in the past
        $feeds->transform(function ($feed) {
            if ($feed->poll_date && Carbon::parse($feed->poll_date)->isPast()) {
                MyFeed::where('id', $feed->id)->update(['poll_finished' => 1]);
                $feed->poll_finished = 1;
            }
            return new MyFeedResource($feed);
        });
        return $feeds;
    }


    public function store(MyFeedRequest $request)
    {
        $myFeed = $this->myFeedService->create($request->prepareRequest());
        if ($myFeed) {
            if ($request->images && count($request->images) > 0) {
                $this->myFeedService->saveImage($myFeed, $request->images);
            }

            if (isset($request->mentionUserIds) && count($request->mentionUserIds) > 0) {
                $this->myFeedService->saveMentionUsers($myFeed, $request->mentionUserIds);
            }

            if (isset($request->voteAnswers) && count($request->voteAnswers) > 0) {
                $this->myFeedService->saveVoteAnswers($myFeed, $request->voteAnswers);
            }

            if (isset($request->tags) && count($request->tags) > 0) {
                $this->myFeedService->savehashTags($myFeed, $request->tags);
            }
            return response()->json(['message' => 'My feed saved successfully']);
        }
        return response()->json(['message' => 'something went wrong please try again'], 400);
    }

    public function update(MyFeedRequest $request, $id)
    {
        $myFeed = $this->myFeedService->update($request->prepareRequest(), $id);
        if ($myFeed) {
            $myFeed->images()->delete();
            if ($request->images && count($request->images) > 0) {
                $this->myFeedService->saveImage($myFeed, $request->images);
            }

            if (isset($request->mentionUserIds) && count($request->mentionUserIds) > 0) {
                $this->myFeedService->saveMentionUsers($myFeed, $request->mentionUserIds);
            }

            if (isset($request->voteAnswers) && count($request->voteAnswers) > 0) {
                $this->myFeedService->saveVoteAnswers($myFeed, $request->voteAnswers);
            }

            if (isset($request->tags) && count($request->tags) > 0) {
                $this->myFeedService->savehashTags($myFeed, $request->tags);
            }
            return response()->json(['message' => 'My feed update successfully']);
        }
        return response()->json(['message' => 'something went wrong please try again'], 400);
    }

    public function destroy($id)
    {
        MyFeed::where('id', $id)->delete();
        return response()->json(['message' => 'Feed deleted successfully']);
    }

    public function addVote(Request $request)
    {
        $request->validate([
            'myFeedId' => 'required|exists:my_feeds,id',
            'answerId' => 'required',
        ]);
        $userId = $this->getAuthUserId($request);
        $answerVote = MyFeedVote::firstOrNew([
            'my_feed_id' => $request->myFeedId,
            "user_id" => $userId
        ]);
        $answerVote->answer_id = $request->answerId;
        $answerVote->save();
        return response()->json(['message' => 'Your vote is added successfully']);
    }

    public function feedComment(Request $request, $id)
    {
        $perPage = $request->perPage ?? 20;
        $comments = MyFeedComment::where('my_feed_id', $id)->orderBy('id', 'desc')->paginate($perPage);
        return $comments->through(function ($comment) {
            return new MyFeedCommentResource($comment);
        });
    }

    public function addComment(Request $request)
    {
        $request->validate([
            'myFeedId' => 'required|exists:my_feeds,id',
            'text' => 'required',
            'tags' => 'nullable|array',
            'mentionUserIds' => 'nullable|array',
            'image' => 'nullable',
            'image.base64' => 'nullable',
            'image.name' => 'nullable',
            'image.size' => 'nullable',
        ]);

        $comment = MyFeedComment::create([
            'my_feed_id' => $request->myFeedId,
            'text' => $request->text,
            'user_id' => $this->getAuthUserId($request)
        ]);

        if ($request->image) {
            $this->myFeedService->saveImage($comment, $request->image);
        }

        if (isset($request->mentionUserIds) && count($request->mentionUserIds) > 0) {
            $this->myFeedService->saveMentionUsers($comment, $request->mentionUserIds);
        }

        if (isset($request->tags) && count($request->tags) > 0) {
            $this->myFeedService->savehashTags($comment, $request->tags);
        }
        return response()->json(['message' => 'Comment added successfully']);
    }

    public function updateComment(Request $request, $id)
    {
        $request->validate([
            'myFeedId' => 'required|exists:my_feeds,id',
            'text' => 'required',
            'tags' => 'nullable|array',
            'mentionUserIds' => 'nullable|array',
            'image' => 'nullable',
            'image.base64' => 'nullable',
            'image.name' => 'nullable',
            'image.size' => 'nullable',
        ]);

        $comment = MyFeedComment::where('id', $id)->first();
        $comment->text = $request->text;
        $comment->save();

        $comment->image()->delete();
        if ($request->image) {
            $this->myFeedService->saveImage($comment, $request->image);
        }

        if (isset($request->mentionUserIds) && count($request->mentionUserIds) > 0) {
            $this->myFeedService->saveMentionUsers($comment, $request->mentionUserIds);
        }

        if (isset($request->tags) && count($request->tags) > 0) {
            $this->myFeedService->savehashTags($comment, $request->tags);
        }
        return response()->json(['message' => 'Comment updated successfully']);
    }

    public function deleteComment($id)
    {
        MyFeedComment::where('id', $id)->delete();
        return response()->json(['message' => 'Comment deleted successfully']);
    }

    public function subscribeFeed(Request $request)
    {
        // $myFeed = MyFeed::find($request->myFeedId);
        // if ($myFeed) {
        MyFeedSubscription::create([
            'user_id' => $this->getAuthUserId($request),
            'module_id' => $request->moduleId,
            'module_type' => "App\Models\\" . $request->moduleName,
        ]);
        return response()->json(['message' => 'You have successfully subscribed to this module object']);
        // }
        return response()->json(['message' => 'Invalid feed request'], 400);
    }

    public function unsubscribeFeed(Request $request)
    {
        // $myFeed = MyFeed::find($request->myFeedId);
        // if ($myFeed) {
        MyFeedSubscription::where([
            'user_id' => $this->getAuthUserId($request),
            'module_id' => $request->moduleId,
            'module_type' => "App\Models\\" . $request->moduleName,
        ])->delete();
        return response()->json(['message' => 'You have successfully unsubscribed to this module object']);
        // }
        return response()->json(['message' => 'Invalid feed request'], 400);
    }
}
