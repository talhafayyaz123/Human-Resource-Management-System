<?php

namespace App\Http\Controllers;

use App\Utils\Logger;
use Exception;
use App\Models\License;
use App\Traits\CustomHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LicenseController extends Controller
{
    use CustomHelper;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $per_page = $request->perPage ?? 25;

        $licenses = new License();
        $sort_by = $request->get('sortBy');
        $sort_order = $request->get('sortOrder');
        if ($sort_by && $sort_order) {
            $licenses = $this->applySortingBeforePagination($licenses, $sort_by, $sort_order);
        }
        if ($request->companyId) {
            $licenses = $licenses->where("company_id", $request->companyId); //filter based on companyId
        }
        if ($request->trashed) {
            $licenses = $licenses->onlyTrashed();
        }
        $licenses = $licenses->orderBy('licenses.created_at')->paginate($per_page)
            ->withQueryString()->through(fn ($license) => [
                'id' => $license->id,
                'articleNumber' => $license->product?->article_number,
                'name' => $license->product_name ?? $license->product?->name,
                'isProductNameEdit' => $license->product?->is_product_name_edit,
                'software' => $license->product?->productSoftware,
                'company' => $license->company,
                'productId' => $license->product_id,
                'quantity' => $license->quantity,
                'salePrice' => $license->sale_price,
                'maintenancePrice' => $license->maintenance_price,
                'maintenanceRate' => $license?->product?->maintenance_rate ?? 0,
                'isEvaluation' => $license->is_evaluation == 1,
                'createdAt' => $license->created_at
            ]);
        return response()->json([
            'data' => $licenses
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

        $this->validator($request);

        $license = new License();
        try {
            $this->saveData($license, $request);
        } catch (Exception $e) {
            return response()->json([
                "success" => false,
                "message" => $e->getMessage()
            ], 422);
        }

        return response()->json([
            "message" => "Record created successfully"
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $license = License::findOrFail($id);

        return [
            'id' => $license->id,
            'name' => $license->product_name ?? $license->product?->name,
            'companyId' => $license->company_id,
            'productId' => $license->product_id,
            'saleOfferComponentId' => $license->sale_offer_component_id,
            'quantity' => $license->quantity,
            'salePrice' => $license->sale_price,
            'maintenancePrice' => $license->maintenance_price,
            'maintenanceRate' => $license?->product?->maintenance_rate ?? 0,
            'isEvaluation' => $license->is_evaluation == 1,
        ];
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

        $this->validator($request);

        $license = License::findOrFail($id);
        try {
            $this->saveData($license, $request);
        } catch (Exception $e) {
            return response()->json([
                "success" => false,
                "message" => $e->getMessage()
            ], 422);
        }

        return response()->json([
            "message" => "Record updated successfully"
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            DB::transaction(function () use ($id) {
                $license = License::findOrFail($id);
                $license->saleOfferComponent()->delete();
                $license->delete();
            });
        } catch (Exception $e) {
            return response()->json([
                "success" => false,
                "message" => $e->getMessage()
            ], 422);
        }

        return response()->json([
            "message" => "Record deleted successfully"
        ], 200);
    }

    /**
     * saves the model based on the parameters from the $request
     * @param $model the model that needs to be saved
     * @param $request request containing all the parameters
     */
    public function saveData($model, $request)
    {
        DB::transaction(function () use ($model, $request) {
            $model->company_id = $request->companyId;
            $model->product_name = @$request->name;
            $model->product_id = $request->productId;
            $model->quantity = $request->quantity;
            $model->sale_price = $request->salePrice;
            $model->maintenance_price = $request->maintenancePrice;
            $model->is_evaluation = $request->isEvaluation ?? 0;
            $model->save();
            $model->saleOfferComponent()->update([
                'product_id' => $model->product_id,
                'quantity' => $model->quantity,
                'sale_price' => $model->sale_price,
                'maintenance_rate' => $model->maintenance_price,
            ]);
        });
    }

    /**
     * validates the received request based on defined schema
     * @param $request received request
     */
    public function validator($request)
    {
        $request->validate([
            'companyId' => 'required|string',
            'productId' => 'required|integer',
            'quantity' => 'required|integer',
            'salePrice' => 'required',
            'maintenancePrice' => 'required'
        ]);
    }

    /**
     * computes the statistics used for graphs in the licenses tab in companies
     * @param $request received request
     */
    public function licenseStatistics($company)
    {
        // get all the licenses
        $licenses = License::where("licenses.company_id", $company)->where('is_evaluation', false);

        // get the total sale_price with sale_price * quantity
        $sale_price_total = $licenses->selectRaw('SUM(quantity * sale_price) AS sale_price_total')->value('sale_price_total');
        // get the total maintenance_price with maintenance_price * quantity
        $maintenance_price_total = $licenses->selectRaw('SUM(quantity * maintenance_price) AS maintenance_price_total')
            ->value('maintenance_price_total');

        // reset the raw queries from above
        $licenses->getQuery()->columns = [];

        // join the product_invoice with licenses to get all product_invoice related to the license and sum the 'price_without_tax'
        $net_total_invoices = License::where("licenses.company_id", $company)->where('is_evaluation', false)->selectRaw('invoices.total_amount_without_tax, product_invoice.invoice_id')->join('product_invoice', 'licenses.id', '=', 'product_invoice.license_id')
            ->join('invoices', 'product_invoice.invoice_id', '=', 'invoices.id')
            ->groupBy('product_invoice.invoice_id', 'invoices.total_amount_without_tax')
            ->get()->sum('total_amount_without_tax');

        // reset the raw queries from above
        $licenses->getQuery()->columns = [];
        $licenses->getQuery()->joins = [];

        /**
         * join the products with licenses to get related licenses with product columns and
         * then join the resultant table with product_software table on product_software_id
         * from products and id from product_software. We can get the product_software name and count
         * from the resultant table after grouping by name
         */
        $software_types = $licenses->join('products', 'products.id', '=', 'licenses.product_id')
            ->join('product_software', 'product_software.id', '=', 'products.product_software_id')
            ->selectRaw('product_software.name, COUNT(*) AS count')
            ->groupBy('name')
            ->get();

        // get all the records (deleted as well) and group by year and month
        $models = License::where("company_id", $company)->where('is_evaluation', false)->withTrashed()->orderBy('created_at')
            ->selectRaw('YEAR(created_at) AS year, MONTH(created_at) AS creation_month, YEAR(deleted_at) AS deletion_year, MONTH(deleted_at) AS deletion_month, id, quantity, sale_price, maintenance_price')
            ->orderBy('year')->get()->groupBy(['year', 'creation_month']);

        // the last year inside the $models array
        $last_year = key(array_slice($models->toArray(), -1, 1, true)) ?? (Carbon::now()->format('Y') - 1);

        // the current year based on the current date
        $current_year = Carbon::now()->format('Y');

        // if the $last_year in the $models array is less than the $current_year then add years from the $last_year upto $current_year
        // this is needed because the data in the chart will always be for the current year, doesn't matter if we have records
        // for the current year or not
        for ($i = $last_year + 1; $i <= $current_year; $i++) {
            // set am empty object for the year in $i
            $models[$i] = [];
        }

        // contains the data based on year, the year array in turn contains all the month from 1 to 12 and months contain salePrice and maintenancePrice
        $data_by_year = [];

        // previous_month_number keeps track of the previous month number
        $previous_month_number = null;
        // previous_year keeps track of the previous year number
        $previous_year = null;

        // default year data
        $default_year_data = [
            '1' => [
                'salePrice' => 0,
                'maintenancePrice' => 0,
            ],
            '2' => [
                'salePrice' => 0,
                'maintenancePrice' => 0,
            ],
            '3' => [
                'salePrice' => 0,
                'maintenancePrice' => 0,
            ],
            '4' => [
                'salePrice' => 0,
                'maintenancePrice' => 0,
            ],
            '5' => [
                'salePrice' => 0,
                'maintenancePrice' => 0,
            ],
            '6' => [
                'salePrice' => 0,
                'maintenancePrice' => 0,
            ],
            '7' => [
                'salePrice' => 0,
                'maintenancePrice' => 0,
            ],
            '8' => [
                'salePrice' => 0,
                'maintenancePrice' => 0,
            ],
            '9' => [
                'salePrice' => 0,
                'maintenancePrice' => 0,
            ],
            '10' => [
                'salePrice' => 0,
                'maintenancePrice' => 0,
            ],
            '11' => [
                'salePrice' => 0,
                'maintenancePrice' => 0,
            ],
            '12' => [
                'salePrice' => 0,
                'maintenancePrice' => 0,
            ],
        ];

        // loop over the records, $year is the year number and $data contains the months data for that year
        foreach ($models as $year => $data) {

            // if $year does not exist in $data_by_year then set to default value
            if (!isset($data_by_year[$year])) {
                $data_by_year[$year] = $default_year_data;
            }
            // if there is a previvous year ($previous_year is set) then grab the salePrice and maintenancePrice from the last month
            // of the previous year and set it to the first month of the current year
            $data_by_year[$year]['1'] = [
                'salePrice' => isset($data_by_year[$previous_year][$previous_month_number]['salePrice']) ? $data_by_year[$previous_year][$previous_month_number]['salePrice'] : 0,
                'maintenancePrice' => isset($data_by_year[$previous_year][$previous_month_number]['maintenancePrice']) ? $data_by_year[$previous_year][$previous_month_number]['maintenancePrice'] : 0,
            ];

            // loop over the default year data, $month_number is the number of the month and $month_stats contains salePrice, maintenancePrice
            foreach ($data_by_year[$year] as $month_number => $month_stats) {
                // set sale_price by summing the sale_price of the previous_month_number with the current month
                $sale_price = $data_by_year[$year][$month_number]['salePrice'] + ($previous_month_number ? $data_by_year[$year][$previous_month_number]['salePrice'] : 0);
                // set maintenance_price by summing the maintenance_price of the previous_month_number with the current month
                $maintenance_price = $data_by_year[$year][$month_number]['maintenancePrice'] + ($previous_month_number ? $data_by_year[$year][$previous_month_number]['maintenancePrice'] : 0);

                // check if the current month number exists in the $models
                if (isset($data[$month_number])) {
                    // loop over the current month data from $models and sum the sale_price and maintenance_price for each record
                    $data[$month_number]->each(function ($month_data) use (&$sale_price, &$maintenance_price, $year, &$data_by_year, $default_year_data) {
                        $sale_price += $month_data['sale_price'];
                        $maintenance_price += $month_data['maintenance_price'];

                        // check if the deletion_year of the record is the same the $year in the iteration, if yes then
                        // subtract the sale_price and maintenance_price from the data_by_year array for the
                        // $year based on the deletion_year and deletion_month number
                        if ($month_data['deletion_year'] == $year) {
                            if (isset($data_by_year[$year][$month_data['deletion_month']])) {
                                $data_by_year[$year][$month_data['deletion_month']]['salePrice'] -= $month_data['sale_price'];
                                $data_by_year[$year][$month_data['deletion_month']]['maintenancePrice'] -= $month_data['maintenance_price'];
                            }
                            // checks if the deletion_year is not set and is not equal to the current $year in the iteration
                        } else if (isset($month_data['deletion_year'])) {
                            // if the deletion_year does not exist in the $data_by_year array then add it to the
                            // $data_by_year array with default month values
                            if (!isset($data_by_year[$month_data['deletion_year']])) {
                                $data_by_year[$month_data['deletion_year']] = $default_year_data;
                            }
                            // subtract the sale_price and maintenance_price of the $month_data from the salePrice and maintenancePrice of
                            // deletion_year and deletion_month in the $data_by_year array
                            $data_by_year[$month_data['deletion_year']][$month_data['deletion_month']]['salePrice'] -= $month_data['sale_price'];
                            $data_by_year[$month_data['deletion_year']][$month_data['deletion_month']]['maintenancePrice'] -= $month_data['maintenance_price'];
                        }
                    });
                }

                // set the computed sale_price and maintenance_price for the $month_number in the $data_by_year array for the $year
                $data_by_year[$year][$month_number]['salePrice'] = $sale_price;
                $data_by_year[$year][$month_number]['maintenancePrice'] = $maintenance_price;

                //set the previous_month_number, which will be used to sum the sale_price and maintenance_price in the next iteration
                $previous_month_number = $month_number;
            }
            // keep track of the previous year value, because the first month of the next year will have the same values
            // as the last month of the current iteration $year
            $previous_year = $year;
        }

        return response()->json([
            "success" => true,
            "data" => [
                'salePriceTotal' => $sale_price_total ?? 0,
                'maintenancePriceTotal' => $maintenance_price_total ?? 0,
                'netTotalInvoices' => $net_total_invoices ?? 0,
                'softwareTypes' => $software_types,
                'statisticsByYear' => $data_by_year
            ]
        ], 200);
    }
}
