<?php

namespace App\Http\Controllers;

use App\Models\ProtocolType;
use Illuminate\Http\Request;
use App\Traits\CustomHelper;

class ProtocolTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    use CustomHelper;

    public function index(Request $request)
    {
        $per_page = $request->perPage ?? 25;

        $sort_by = $request->get('sortBy');
        $sort_order = $request->get('sortOrder');
        $model = new ProtocolType();
        if ($sort_by && $sort_order) {
            $model = $this->applySortingBeforePagination($model, $sort_by, $sort_order);
        }

        $protocol_types = $model->orderBy('created_at')->paginate($per_page)
            ->withQueryString()
            ->through(fn ($type) => [
                'id' => $type->id,
                'name' => $type->name
            ]);

        return response()->json([
            'data' => $protocol_types
        ]);
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
            'name' => 'required|string'
        ]);

        $protocol_type = new ProtocolType();
        $protocol_type->name = $request->name;
        $protocol_type->save();

        return response()->json([
            "success" => true,
            "message" => "Record Created Successfully"
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $protocol_type = ProtocolType::findOrFail($id);

        return response()->json([
            "id" => $protocol_type->id,
            "name" => $protocol_type->name
        ]);
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
        $protocol_type = ProtocolType::findOrFail($id);

        $request->validate([
            'name' => 'required|string'
        ]);

        $protocol_type->name = $request->name;
        $protocol_type->save();

        return response()->json([
            "success" => true,
            "message" => "Record Updated Successfully"
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $protocol_type = ProtocolType::findOrFail($id);
        $protocol_type->delete();

        return response()->json([
            "success" => true,
            "message" => "Record Deleted Successfully"
        ]);
    }
}
