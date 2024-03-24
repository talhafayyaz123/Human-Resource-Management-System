<?php

namespace App\Http\Controllers;

use App\Models\ReviewReport;
use App\Traits\CustomHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Request as staticRequest;

class ReviewReportController extends Controller
{
    use CustomHelper;

    public function __construct()
    {
        $this->middleware('check.permission:review-report.list', ['only' => ['index', 'show']]);
        $this->middleware('check.permission:review-report.create', ['only' => ['store']]);
        $this->middleware('check.permission:review-report.edit', ['only' => ['update']]);
        $this->middleware('check.permission:review-report.delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $per_page = $request->perPage ?? 25;
        $sort_by = $request->get('sortBy');
        $sort_order = $request->get('sortOrder');
        $model = new ReviewReport();
        if ($sort_by && $sort_order) {
            $model = $this->applySortingBeforePagination($model, $sort_by, $sort_order);
        }
        $model = $model->filter(staticRequest::only('search'))->paginate($per_page)
            ->through(fn($item) => [
                'id' => $item->id,
                'title' => $item->title,
                'description' => $item->description,
            ]);
        return response()->json(['reviewReport' => $model]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            "title" => "required|string",
            "description" => "required|string",
        ]);

        //Create Skill
        ReviewReport::create([
            'title' => $request->title,
            'description' => $request->description,
        ]);
        return response()->json(['message' => 'Record saved successfully'], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show($id)
    {
        $reportReview = ReviewReport::find($id);
        if ($reportReview) {
            return response()->json(['skillLevel' => [
                'id' => $reportReview->id,
                "title" => $reportReview->title,
                "description" => $reportReview->description,
            ]]);
        }
        return response()->json(['message' => 'Invalid id provide'], 400);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            "title" => "required|string",
            "description" => "required|integer",
        ]);

        //Update Skill
        $model = ReviewReport::find($id);
        if ($model) {
            $model->update(['title' => $request->title, 'description' => $request->description]);
            return response()->json(['message' => 'Record updated successfully.'], 200);
        }
        return response()->json(['message' => 'Invalid id provide'], 400);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy($id)
    {
        $model = ReviewReport::find($id);
        if ($model) {
            $model->delete();
            return response()->json(['message' => 'Record deleted successfully.'], 200);
        }
        return response()->json(['message' => 'Invalid id provide'], 400);
    }

}
