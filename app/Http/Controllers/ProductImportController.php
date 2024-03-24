<?php

namespace App\Http\Controllers;

use App\Models\EloVersion;
use App\Models\Product;
use App\Models\ProductGroup;
use App\Models\ProductVersion;
use App\Traits\SetGlobalNumber;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductImportController extends Controller
{
    use SetGlobalNumber;
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function import(Request $request)
    {
        $request->validate([
            'csv' => 'required|file|mimes:csv,txt'
        ]);
        $data = $request->file('csv');
        $file = fopen($data, "r", "UTF-8");
        if (!mb_check_encoding(file_get_contents($data), 'UTF-8')) {
            $file = fopen($data, "r");
            if (!$file) {
                throw new Exception("Failed to open file");
            }
            $content = stream_get_contents($file);
            $content_utf8 = iconv('ISO-8859-1', 'UTF-8//TRANSLIT', $content);

            // Re-open the file with the UTF-8-encoded content
            $file = fopen('php://memory', 'r+');
            fwrite($file, $content_utf8);
            rewind($file);
        }
        $header_row = fgetcsv($file);
        // determine the column indices for the columns we need

        $article_number_index = array_search('itemno', $header_row);
        $product_group_index = array_search('itemcategory', $header_row);
        $elo_version_index = array_search('plversion', $header_row);
        $maintainence_rate_index = array_search('itemswpuspercent', $header_row);
        $maintainence_price_index = array_search('itemswpusfee', $header_row);
        $product_name_index = array_search('itemdescription', $header_row);
        $product_description_index = array_search('itemnote', $header_row);
        $product_discount_index = array_search('itemdiscount', $header_row);
        $product_listing_price = array_search('itemlistprice', $header_row);
        $products = [];
        while (($row = fgetcsv($file)) !== false) {
            // extract the relevant data from the row
            $article_number = $row[$article_number_index];
            $product_group = $row[$product_group_index];
            $elo_version = $row[$elo_version_index];
            $maintainence_rate = $row[$maintainence_rate_index];
            $maintainence_price = $row[$maintainence_price_index];
            $product_name = $row[$product_name_index];
            $product_description = $row[$product_description_index];
            $product_discount = $row[$product_discount_index];
            $product_listing = $row[$product_listing_price];

            // create a new product array with the extracted data
            $elo_version_string = explode(".", $elo_version);
            $product = [
                'articleNumber' => $article_number,
                'productGroup' => $product_group,
                'eloVersion' => $elo_version_string[0] ?? '',
                'maintainenceRate' => $maintainence_rate,
                'maintainencePrice' => $maintainence_price,
                'productName' => $product_name,
                'productDescription' => $product_description,
                'manufactureDiscount' => $product_discount,
                'productListingPrice' => $product_listing
            ];


            $products[] = $product;
        }

        // close the file
        fclose($file);

        return response()->json(['data' => $products]);
    }

    public function store(Request $request)
    {
        $request->validate([
            "products.*.productName" => "required|string",
            "products.*.articleNumber" => "required|string",
            "products.*.productGroup" => "required",
            "products.*.listingPrice" => "required",
            'productType' => 'required',
            'productPrice' => 'required',
            'productVersion' => 'required',
            'paymentPeriod' => 'required',
            'productUnit' => 'required',
            'productSoftware' => 'required',
        ]);
        DB::transaction(function () use ($request) {
            foreach ($request->input('products', []) as $productData) {
                // Find or create the EloVersion model based on the incoming eloVersion string
                $elo_version = EloVersion::firstOrNew([
                    'name' => (float) preg_replace('/^V/', '', $productData['eloVersion'] ?? '')
                ]);

                $elo_version->type = 'lts';
                $elo_version->save();

                // Find or create the ProductGroup model based on the incoming productGroup name
                $product_group = ProductGroup::firstOrCreate([
                    'name' => $productData['productGroup']
                ]);

                // Find the existing Product model with the same manufacturer_article_number,
                // or create a new one if it doesn't exist
                $product = Product::firstOrNew([
                    'manufacturer_article_number' => $productData['articleNumber'],
                    'product_price_id' => $request->productPrice
                ]);
                $product->payment_details_type = $request->productType;
                $product->payment_period_id = $request->paymentPeriod;
                $product->product_unit_id = $request->productUnit;
                $product->product_software_id = $request->productSoftware;
                $product->elo_version_id =  $elo_version->id;

                $product->product_group_id =  $product_group->id;
                $product->maintenance_rate = $productData['maintainenceRate'];
                $maintanence_price = $productData['maintainencePrice'];
                if (strpos($maintanence_price, ',') !== false) {
                    $maintanence_price = str_replace(',', '.', $maintanence_price); // Replace comma with dot to convert to float
                }
                $product->maintanence_price = (float) $maintanence_price;
                $listing_price = $productData['listingPrice'];
                if (strpos($listing_price, ',') !== false) {
                    $listing_price = str_replace(',', '.', $listing_price); // Replace comma with dot to convert to float
                }
                $product->listing_price = $listing_price;
                $product->manufacturer_discount = $productData['manufactureDiscount'];
                $product->sale_price = $listing_price;
                $product->product_price_id = $request->productPrice;
                $product->name = $productData['productName'];
                $product->description = $productData['productDescription'] ?? null;
                $product->article_number = $this->assignProductNumber($product);
                $product->status = true;
                $product->tax = 19;
                $product->save();
                $product_version = new ProductVersion;
                $product_version->version = $request->productVersion;
                $product_version->product_id = $product->id;
                $product_version->save();
            }
        });
        return response()->json(['message' => trans('messages.record_saved_success', ['name' => 'Product import'])], 200);
    }



    private function assignProductNumber(Product $product)
    {
        if ($product->payment_details_type == 'service') {
            $product =   $this->globalNumber($product, 'productService', 'DL', 1000);
        } else if ($product->payment_details_type == 'ams') {
            $product = $this->globalNumber($product, 'productAMS', 'AM', 1000);
        } else if ($product->payment_details_type == 'software') {
            $product = $this->globalNumber($product, 'productSoftware', 'SW', 1000);
        } else if ($product->payment_details_type == 'cloud-software') {
            $product = $this->globalNumber($product, 'productCloudSoftware', 'CS', 1000);
        } else if ($product->payment_details_type == 'hosting') {
            $product = $this->globalNumber($product, 'productHosting', 'H', 1000);
        } else if ($product->payment_details_type == 'traveling') {
            $product = $this->globalNumber($product, 'productTraveling', 'T', 1000);
        } else {
            $product = $this->globalNumber($product, 'productPaushal', 'PS', 1000);
        }
        return $product;
    }
}
