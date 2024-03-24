<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductStoreReviewRequest;
use App\Http\Resources\ProductStoreResource;
use App\Models\ProductStoreReview;
use App\Models\ProductStoreReviewFeedback;
use App\Models\UploadedFile;
use App\Traits\CustomHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class ProductStoreReviewController extends Controller
{
    use CustomHelper;

    public function __construct()
    {
        $this->middleware('check.permission:product-store.list', ['only' => ['index', 'show']]);
        $this->middleware('check.permission:product-store.create', ['only' => ['store']]);
        $this->middleware('check.permission:product-store.edit', ['only' => ['update']]);
        $this->middleware('check.permission:product-store.delete', ['only' => ['destroy']]);
    }

    /**
     * Display the specified resource on the bases of product store id
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->productStoreId) {
            $perPage = $request->get('perPage') ?? 12;
            $sortBy = $request->get('sortBy') ?? 'topRated'; // recent
            $reviews = ProductStoreReview::with('userInformation')->where('product_store_id', $request->productStoreId);

            if ($sortBy == 'topRated') {
                $reviews = $reviews->orderBy('ratting', 'desc');
            } else {
                $reviews = $reviews->orderBy('created_at', 'desc');
            }
            $reviews = $reviews->paginate($perPage);
            $returnReviews = $reviews->through(function ($review) use ($request) {
                $returnData = [
                    'id' => $review->id,
                    'title' => $review->title,
                    'user_id' => $review->user_id,
                    'userName' => $review->userInformation?->full_name,
                    'profileImage' => $review->userInformation?->profile_image,
                    'userEmail' => $review->userInformation?->email,
                    'review' => $review->review,
                    'ratting' => $review->ratting,
                    'isReadMore' => false,
                    'createdAt' => $review->created_at->format('F j, Y'),
                    'isHelpful' => $review->helpfulFeedback->where('user_id', $this->getAuthUserId($request))->first() ? true : false,
                    'isBad' => $review->badFeedback->where('user_id', $this->getAuthUserId($request))->first() ? true : false,
                    'helpfulText' => $review->helpfulFeedback->count() . " people found this helpful",
                    'images' => $review->images?->map(function ($image) {
                        return [
                            'viewableName' => $image->viewable_name,
                            'url' => $this->getImageUrl($image),
                            'size' => $image->storage_size,
                        ];
                    })
                ];
                if ($this->hasRole($request, 'admin')) {
                    $returnData['reports'] = $review->reports?->map(function ($report) {
                        return [
                            'userId' => $report->pivot->user_id,
                            'title' => $report->title,
                            'description' => $report->description,
                        ];
                    });
                }
                return $returnData;
            });
            return response()->json(['reviews' => $returnReviews]);
        }
        return response()->json(['message' => 'No product store id provided'], 400);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */

    public function store(ProductStoreReviewRequest $request)
    {
        $data = $request->prepareRequest();

        $data['user_id'] = $request->get('userId') ?? $this->getAuthUserId($request);

        $review = ProductStoreReview::create($data);
        if ($review) {
            if ($request->images && count($request->images) > 0) {
                $this->saveImages($request->images, $review);
            }
            return response()->json(['message' => 'Record added successfully']);
        }
        return response()->json(['message' => 'Something went wrong'], 400);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $review = ProductStoreReview::find($id);
        if ($review) {
            $getUser = Http::withToken($request->bearerToken())->get(config('services.users.vite_auth_service_url') . "/list-user-by-id", [
                'id' => $review->user_id
            ]);
            $userData = [];
            if ($getUser->status() == 200) {
                $userData = json_decode($getUser->body(), true);
            }
            $returnData = [
                'id' => $review->id,
                'productStoreDetail' => new ProductStoreResource($review->productStore),
                'userId' => $review->user_id,
                'userName' => @$userData['first_name'] . " " . @$userData['last_name'],
                'profileImage' => @$userData['profile_image'],
                'userEmail' => @$userData['email'],
                'title' => $review->title,
                'review' => $review->review,
                'ratting' => $review->ratting,
                'createdAt' => $review->created_at->format('F j, Y'),
                'isHelpful' => $review->helpfulFeedback->where('user_id', $this->getAuthUserId($request))->first() ? true : false,
                'isBad' => $review->badFeedback->where('user_id', $this->getAuthUserId($request))->first() ? true : false,
                'helpfulText' => $review->helpfulFeedback->count() . " people found this helpful",
                'images' => $review->images?->map(function ($image) {
                    return [
                        'viewableName' => $image->viewable_name,
                        'url' => $this->getImageUrl($image),
                        'size' => $image->storage_size,
                    ];
                })
            ];
            if ($this->hasRole($request, 'admin')) {
                $returnData['reports'] = $review->reports?->map(function ($report) {
                    return [
                        'userId' => $report->pivot->user_id,
                        'title' => $report->title,
                        'description' => $report->description,
                    ];
                });
            }
            return response()->json(['review' => $returnData]);
        }
        return response()->json(['message' => 'Invalid id provided'], 400);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */

    public function update(ProductStoreReviewRequest $request, $id)
    {
        $review = ProductStoreReview::find($id);
        if ($review && ($this->getAuthUserId($request) == $review->user_id || $this->hasRole($request, 'admin'))) {
            $data = $request->prepareRequest();
            $data['user_id'] = $request->get('userId') ?? $this->getAuthUserId($request);
            $review = ProductStoreReview::updateOrCreate(['id' => $id], $data);
            if ($review) {
                $review->images()->delete();
                if ($request->images && count($request->images) > 0) {
                    $this->saveImages($request->images, $review);
                }
                return response()->json(['message' => 'Record updated successfully']);
            }
            return response()->json(['message' => 'Something went wrong'], 400);
        }
        return response()->json(['message' => 'Invalid id or you are not the author of this review'], 400);
    }

    /**
     * Destroy the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */

    public function destroy(Request $request, $id)
    {
        $review = ProductStoreReview::find($id);
        if ($review && ($this->getAuthUserId($request) == $review->user_id || $this->hasRole($request, 'admin'))) {
            $review->delete();
            return response()->json(['message' => 'Record deleted successfully']);
        }
        return response()->json(['message' => 'Invalid id or you are not the author of this review'], 400);
    }

    private function getImageUrl($image)
    {
        $file = null;
        if (isset($image->storage_name)) {
            $file = Storage::disk('public')->get('productStore/review/' . $image->storage_name);
        }
        if (!$file) {
            return response()->json();
        }
        $mime = Storage::disk('public')->mimeType('productStore/review/' . $image->storage_name);
        $base64 = base64_encode($file);
        return "data:$mime;base64,$base64";
    }

    private function saveImages($images, $productStore): void
    {
        foreach ($images as $image) {
            $originalName = $image['name'];
            $base64Decode = base64_decode($image['base64'], true);
            // Generate a unique file name
            $fileNameToStore = time() . '.' . $originalName;

            // Save the decoded file to disk
            Storage::disk('public')->put('productStore/review/' . $fileNameToStore, $base64Decode);
            $uploaded_file = new UploadedFile();
            $uploaded_file->storage_name = $fileNameToStore;
            $uploaded_file->viewable_name = $originalName;
            $uploaded_file->storage_size = $image['size'];
            $uploaded_file->fileable()->associate($productStore);
            $uploaded_file->save();
        }
    }

    public function storeHelpfulFeedback(Request $request, $id, $feedback = 1)
    {
        $review = ProductStoreReview::find($id);
        if ($review) {
            ProductStoreReviewFeedback::updateOrCreate([
                'review_id' => $id,
                'user_id' => $this->getAuthUserId($request),
                'is_helpful' => $feedback,
            ]);
            return response()->json(['message' => 'Feedback added successfully']);
        }
        return response()->json(['message' => 'Invalid id provided'], 400);
    }

    public function storeReportReview(Request $request)
    {
        $request->validate([
            'reviewId' => 'required|exists:product_store_reviews,id',
            'reviewReportIds' => 'required|array',
            'reviewReportIds.*' => 'required|exists:review_reports,id',
        ]);
        $data = [];
        $review = ProductStoreReview::find($request->reviewId);
        foreach ($request->reviewReportIds as $id) {
            $data[$id] = ['user_id' => $this->getAuthUserId($request)];
        }
        $review->reports()->sync($data);
        return response()->json(['message' => 'Thanks for your report We will investigate in the next few days.'], 400);
    }
}
