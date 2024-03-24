<?php

namespace App\Http\Controllers;

use App\Models\Country;
use Exception;
use App\Traits\CustomHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Request as staticRequest;

class CountriesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    use CustomHelper;

    public function index(Request $request)
    {
        if ($request->perPage) {
            $per_page = $request->perPage ?? 25;
            $sort_by = $request->get('sortBy');
            $sort_order = $request->get('sortOrder');
            $model = new Country;
            if ($sort_by && $sort_order) {
                $model = $this->applySortingBeforePagination($model, $sort_by, $sort_order);
            }
            $models = $model->filter(staticRequest::only('search'))->paginate($per_page);
            $model_collect = $models->map(function ($country) {
                return [
                    'id' => $country->id,
                    'name' => $country->name,
                    'iso' => $country->iso,
                    'region' => $country->region,
                    'subregion' => $country->sub_region
                ];
            });
            return response()->json([
                'data' => $model_collect,
                'links' => $models->links(),
                'current_page' => $models->currentPage(),
                'from' => $models->firstItem(),
                'last_page' => $models->lastPage(),
                'path' => request()->url(),
                'per_page' => $models->perPage(),
                'to' => $models->lastItem(),
                'total' => $models->total(),
            ]);
        }
        return response()->json(['data' => Country::get()]);
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
            'name' => 'required|string',
            'subregion' => 'nullable|string',
            'region' => 'required',
            'iso' => 'required'
        ]);
        $country = new Country();
        $this->saveData($country, $request->all());
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
        $country = Country::findOrFail($id);
        return response()->json(['data' => [
            'id' => $country->id, 'subregion' => $country->sub_region, 'region' => $country->region, 'name' => $country->name,
            'iso' => $country->iso,
            'isDefault' => $country->is_default
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
            'name' => 'required|string',
            'subregion' => 'nullable',
            'region' => 'required',
            'iso' => 'required',
            'isDefault' => 'nullable|boolean'
        ]);
        $country = Country::findOrFail($id);
        if ($request->has('isDefault') && $request->boolean('isDefault')) {
            $existing_default_country = Country::where('is_default', true)
                ->where('id', '<>', $country->id)
                ->first();

            if ($existing_default_country) {
                return response()->json(['message' => 'Another record already has isDefault set to true.'], 422);
            }

            $country->is_default = $request->isDefault;
        } else if ($request->has('isDefault') && $request->isDefault == false) {
            $country->is_default = $request->isDefault;
        }

        $this->saveData($country, $request->all());
        return response()->json(['message' => 'Record updated successfully.'], 200);
    }


    public function storeCsv(Request $request)
    {
        $request->validate([
            'countries.*.name' => 'required|string',
            'countries.*.subregion' => 'nullable|string',
            'countries.*.region' => 'nullable|string',
            'countries.*.iso' => 'required'
        ]);
        $country_collection = collect($request->countries ?? []);
        $country_collection->map(function ($country_data) {
            $country = new Country();
            $this->saveData($country, $country_data);
        });
        return response()->json(['message' => 'Records created successfully.'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $country = Country::findOrFail($id);
        $country->delete();
        return response()->json(['message' => 'Record deleted successfully.'], 200);
    }


    public function saveData(Country $country, array $request): Country
    {
        $country->sub_region = $request['subregion'] ?? '';
        $country->region = $request['region'] ?? '';
        $country->name = $request['name'] ?? '';
        $country->iso = $request['iso'] ?? '';
        $country->save();
        return $country;
    }

    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function uploadCsv(Request $request)
    {
        $request->validate([
            'csv' => 'required|file|mimes:csv,txt'
        ]);
        $data = $request->file('csv');
        $file = fopen($data, "r", "UTF-8");
        if (!mb_check_encoding(file_get_contents($data), 'UTF-8')) {
            $file = fopen($data, "rb");
            if (!$file) {
                throw new Exception("Failed to open file");
            }
            $content = stream_get_contents($file);
            $content_utf8 = iconv(mb_detect_encoding($content, mb_detect_order(), true), 'UTF-8//IGNORE', $content);

            // Re-open the file with the UTF-8-encoded content
            $file = fopen('php://memory', 'r+');
            fwrite($file, $content_utf8);
            rewind($file);
        }
        $header_row = fgetcsv($file);
        $iso_index = array_search('iso', $header_row);
        $name_index = array_search('name', $header_row);
        $region_index = array_search('region', $header_row);
        $subregion_index = array_search('sub-region', $header_row);
        $countries = [];
        while (($row = fgetcsv($file)) !== false) {
            $iso = $row[$iso_index];
            $name = $row[$name_index];
            $region = $row[$region_index];
            $subregion = $row[$subregion_index];
            $countries[] = [
                'iso' => $iso,
                'name' => $name,
                'region' => $region,
                'subregion' => $subregion
            ];
        }
        // close the file
        fclose($file);
        return response()->json(['data' => $countries]);
    }


    public function getAllCountries()
    {
        $models = Country::get();
        $model_collect = $models->map(function ($country) {
            return [
                'id' => $country->id,
                'name' => $country->name,
                'iso' => $country->iso,
                'region' => $country->region,
                'subregion' => $country->sub_region,
                'isDefault' => $country->is_default ? true : ''
            ];
        });
        return response()->json([
            'data' => $model_collect,
        ]);
    }
}
