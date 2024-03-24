<?php

namespace App\Http\Controllers;

use App\Http\Resources\AssetArticleResource;
use App\Models\AssetArticle;
use App\Models\AssetEmployeeListText;
use App\Models\AssetList;
use App\Models\UserProfileData;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Request as staticRequest;

class MyAssetController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $user_id = $request->get('auth_user_id');
        $employee = UserProfileData::where('user_id', $user_id)->first();
        if (isset($employee)) {
            $employee_list = AssetList::where('employee_id', $employee->id)->first();
            if (empty($employee_list)) {
                throw new Exception('No Asset list is assigend to your user. Contact your Manager');
            }

            $asset_articles = AssetArticle::filter(staticRequest::only('search',  'status'))->where('asset_list_id', $employee_list->id)->get();
            return
                [
                    'employeeText' => AssetEmployeeListText::first()->asset_employee_text,
                    'assetArticles' =>  AssetArticleResource::collection($asset_articles)
                ];
        }
        return response()->json(['data' => []]);
    }
}
