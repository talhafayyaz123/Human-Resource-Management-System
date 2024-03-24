<?php

namespace App\Http\Controllers;

use App\Helpers\CustomerHelper;
use App\Traits\CustomHelper;
use Illuminate\Http\Request;

class PartnerCustomerController extends Controller
{
    use CustomHelper;
    protected $customerHelper;

    public function __construct(CustomerHelper $customerHelper)
    {
        $this->middleware('check.permission:partner-company.list', ['only' => ['index', 'show']]);
        $this->middleware('check.permission:partner-company.edit', ['only' => ['update']]);
        $this->middleware('check.permission:partner-company.delete', ['only' => ['destroy']]);
        $this->customerHelper = $customerHelper;
    }

    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {
        $tenantId = $request->tenantId ?? $this->getAuthTenantId($request);
        return $this->customerHelper->index($request, $tenantId);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */

    public function show($id)
    {
        return $this->customerHelper->show($id);
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
        return $this->customerHelper->update($request, $id);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */

    public function destroy($id)
    {
        return $this->customerHelper->destroy($id);
    }
}
