<?php

namespace App\Http\Controllers;

use App\Models\CustomerPortalNews;
use App\Traits\CustomHelper;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Request as staticRequest;

class CustomerPortalNewsController extends Controller
{
    use CustomHelper;

    /**
     *Runs when instance is initiated
     */

    public function __construct()
    {
        $this->middleware('check.permission:customer-portal-news.list', ['only' => ['index', 'show']]);
        $this->middleware('check.permission:customer-portal-news.create', ['only' => ['store']]);
        $this->middleware('check.permission:customer-portal-news.edit', ['only' => ['update']]);
        $this->middleware('check.permission:customer-portal-news.delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $sort_by = $request->get('sortBy');
        $sort_order = $request->get('sortOrder');
        $per_page = $request->perPage;

        $model = new CustomerPortalNews;
        $models = $model->filter(staticRequest::only('search'))->paginate($per_page);

        $model_collect = $models->map(function ($item) {
            return [
                'id' => $item->id,
                "subject" => $item->language == 'english' ? $item->subject : $item->german_subject,
                "audience" => $item->audience,
                'description' => $item->description, //need for customer portal
                'germanDescription' => $item->german_description,
                'germanSubject' => $item->german_subject,
                'language' => $item->language,
                "createdAt" => Carbon::parse($item->created_at)->toDateString(),
                "updatedBy" => $item->updated_at,
            ];
        });
        $sort_by = $request->get('sortBy');
        $sort_order = $request->get('sortOrder');
        if ($sort_by && $sort_order) {
            $model_collect = $this->applySorting($model_collect, $sort_by, $sort_order);
        }
        return response()->json([
            'data' => $model_collect,
            'links' => $models->links(),
            'meta' => [
                'current_page' => $models->currentPage(),
                'from' => $models->firstItem(),
                'last_page' => $models->lastPage(),
                'path' => request()->url(),
                'per_page' => $models->perPage(),
                'to' => $models->lastItem(),
                'total' => $models->total(),
            ]
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            "subject" => "required",
            "userId" => "required",
            'language' => 'required|in:english,german',
            'description' => 'required',
            "audience" => "required"
        ]);

        //Create news
        $model = new CustomerPortalNews;
        $this->saveData($model, $request);

        return response()->json(['message' => 'Record created successfully.'], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $model = CustomerPortalNews::find($id);
        return response()->json(["data" => [
            'id' => $model->id,
            "subject" => $model->subject,
            "description" =>  $model->description,
            'userId' => $model->user_id,
            "audience" => $model->audience,
            'germanDescription' => $model->german_description,
            'germanSubject' => $model->german_subject,
            'language' => $model->language,
        ]]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            "subject" => "required",
            "userId" => "required",
            'language' => 'required|in:english,german',
            'description' => 'required',
            "audience" => "required"
        ]);

        //Create news
        $model = CustomerPortalNews::find($id);
        $this->saveData($model, $request);

        return response()->json(['message' => 'Record updated successfully.'], 200);
    }

    /**
     * Saves the data based on provided resource item
     *
     * @param   object  $model
     * @param   object  $request
     * @param   array   Response
     */
    public function saveData($model, $request)
    {
        $model->user_id = $request->userId;
        $model->audience = $request->audience;
        $model->language = $request->language;
        if ($model->language == 'english') {
            $model->subject = $request->subject ?? null;
            $model->description = $request->description ?? null;
        } else {
            $model->german_subject = $request->subject ?? null;
            $model->german_description = $request->description ?? null;
        }
        $model->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        CustomerPortalNews::where('id', $id)->delete();
        return response()->json(['message' => 'Record deleted.'], 200);
    }
}
