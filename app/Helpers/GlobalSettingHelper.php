<?php

namespace App\Helpers;

use App\Models\GlobalSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use PHPUnit\Exception;

class GlobalSettingHelper
{
    public static function sendEloAPIRequest($content, $elo_request_model = null, $elo = null): void
    {
        $model = GlobalSetting::where("key", "LIKE", "eloConfigUsername")
            ->orWhere("key", "LIKE", "eloConfigPassword")
            ->orWhere("key", "LIKE", "eloConfigUrl")
            ->get();
        $response = [];
        foreach ($model as $item) {
            $response[$item->key] = $item->value;
        }

        $enqueue_url = config('services.elo.enqueue_url');
        if (
            isset($response["eloConfigUsername"]) && $response["eloConfigUsername"] != ''
            && isset($response["eloConfigPassword"]) && $response["eloConfigPassword"] != ''
            && isset($response["eloConfigUrl"]) && $response["eloConfigUrl"] != ''
            && isset($enqueue_url) && $enqueue_url != ''
        ) {
            $token = ApiTokenHelper::getApiToken();
            $json = ["content" => $content];
            if ($elo) {
                $json['elo'] = $elo;
            }
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token,
                'Content-Type' => 'application/json',
            ])->post($enqueue_url, [
                "method" => "POST",
                'url' => $response["eloConfigUrl"],
                'json' => $json,
                'headers' => [
                    'Authorization' => 'Basic ' . base64_encode($response["eloConfigUsername"] . ":" . $response["eloConfigPassword"]),
                ],
            ]);
            if ($response->successful()) {
                if (!empty($elo_request_model)) {
                    $elo_request_model = $elo_request_model->refresh();
                    $record = $elo_request_model->moduleHistory()->latest()->first();
                    if (isset($record)) {
                        $record->is_elo_request_sent = true;
                        $record->save();
                    }
                }
            }
        }
    }


    public static function getModuleName($modelName)
    {
        try {
            $array = [
                'App\Models\SaleOffer' => 'Sale Offer',
                'App\Models\Invoice'=> 'Invoice',
                'App\Models\UserProfileData' => 'User Profile',
                'App\Models\UserJobData' => 'User Job',
                'App\Models\UserCompensationData' => 'User Compensation',
                'App\Models\UserTeam' => 'User Team',
                'App\Models\UserDepartment' => 'User Department',
                'App\Models\UserLocation' => 'User Location',
                'App\Models\Job' => 'Job',
                'App\Models\InvoiceTemplate' => 'Invoice Template',
                'App\Models\Product' => 'Product',
                'App\Models\PerformanceRecord' => 'Performance Record',
                'App\Models\OutboundedContract' => 'Outbounded Contract',
                'App\Models\SkillGroup' => 'Skill Group',
                'App\Models\Asset' => 'Asset',
                'App\Models\Company' => 'Company',
                'App\Models\System' => 'System',
                'App\Models\ContactReport' => 'Contact Report',
                'App\Models\Project' => 'Project',
                'App\Models\Ticket' => 'Ticket',
                'App\Models\HashTag' => 'Hash Tag',
                "App\Models\ApplicationManagementService" => "AMS",
                "App\Models\Supplier" => "Supplier",
                "App\Models\Survey" => "Survey",
                "App\Models\OfferRequest" => "Offer Request",
                "App\Models\CreditorInvoice" => "Creditor Invoice",
                "App\Models\WorkshopTemplate" => "WorkshopTemplate",
                "App\Models\ProjectProtocol" => "Project Protocol",
                "App\Models\AssetList" => "Asset List",
                "App\Models\UserProfile" => "User Profile",
                "App\Models\ProductStore" => "Product Store",
                "App\Models\ProductStoreRequest" => "Product Store Request",
                "App\Models\Skill" => "Skill",
                "App\Models\SkillMatrix" => "Skill Matrix",
                "App\Models\UploadedFile" => "Uploaded File",
                "App\Models\ModifyMyData" => "Change Process",
                "App\Models\FleetCar" => "Fleet Car",
                "App\Models\FuelReceipt" => "Fuel Monitoring",
                "App\Models\JobLevel" => "Job Level",
                "App\Models\SystemHost" => "System Host",
                "App\Models\CloudInfrastructre" => "Cloud Infrastructure",
                "App\Models\AssetDelivery" => "Asset Delivery",
                "App\Models\FleetDriver" => "Fleet Driver",
                "App\Models\OperatingSystem" => "Operating System",
                "App\Models\FormOfContract" => "Form Of Contract",
                "App\Models\HighestEducationLevel" => "Highest Education Level",
                "App\Models\HighestSchoolDegree" => "Highest School Degree",
                "App\Models\PersonalModificationProcessChecklist" => "Process Check List",
                "App\Models\ProductGroup" => "Product Group",
                "App\Models\ProductVersion" => "Product Version",
                "App\Models\ProductCategory" => "Product Category",
                "App\Models\ProductUnit" => "Product Unit",
                "App\Models\PaymentPeriod" => "Payment Period",
                "App\Models\PriceList" => "Price List",
                "App\Models\TermsOfPayment" => "Terms Of Payment",
                "App\Models\Country" => "Country",
                "App\Models\AffectedGroup" => "Affected Group",
                "App\Models\ReportCategory" => "Report Category",
                "App\Models\PartnerOrder" => "Partner Order"

            ];
            return $array[$modelName];
        } catch (\Exception $exception){
            return null;
        }

    }

    public static function getNameOrNumberColumn($modelName)
    {
        try {
            $array = [
                'App\Models\SaleOffer' => 'sale_offer_number',
                'App\Models\Invoice'=> 'invoice_number',
                'App\Models\UserProfileData' => 'email',
                'App\Models\UserJobData' => 'personal_number',
                'App\Models\UserCompensationData' => 'compensation_type',
                'App\Models\UserTeam' => 'name',
                'App\Models\UserDepartment' => 'name',
                'App\Models\UserLocation' => 'street',
                'App\Models\Job' => 'j_number',
                'App\Models\InvoiceTemplate' => 'invoice_category',
                'App\Models\Product' => 'article_number',
                'App\Models\PerformanceRecord' => 'performance_number',
                'App\Models\OutboundedContract' => 'contract_number',
                'App\Models\SkillGroup' => 'name',
                'App\Models\Asset' => 'asset_number',
                'App\Models\Company' => 'company_number',
                'App\Models\System' => 'system_number',
                'App\Models\ContactReport' => 'report_number',
                'App\Models\Project' => 'name',
                "App\Models\JobLevel" => "level_name",
                "App\Models\SystemHost" => "username",
                'App\Models\Ticket' => 'ticket_number',
                "App\Models\ApplicationManagementService" => "ams_number",
                "App\Models\Supplier" => "supplier_number",
                "App\Models\Survey" => "survey_number",
                "App\Models\OfferRequest" => "offer_request_number",
                "App\Models\CreditorInvoice" => "invoice_number",
                "App\Models\WorkshopTemplate" => "template_name",
                "App\Models\ProjectProtocol" => "date",
                "App\Models\AssetList" => "asset_number",
                "App\Models\ProductStore" => "item_number",
                "App\Models\ProductStoreRequest" => "item_number",
                "App\Models\Skill" => "name",
                "App\Models\SkillMatrix" => "name",
                "App\Models\ModifyMyData" => "process-name",
                "App\Models\FleetCar" => "licence_number",
                "App\Models\FuelReceipt" => "delivery_date",
                "App\Models\CloudInfrastructre" => "system_number",
                "App\Models\AssetDelivery" => "invoice_number",
                "App\Models\FleetDriver" => "status",
                "App\Models\OperatingSystem" => "name",
                "App\Models\FormOfContract" => "name",
                "App\Models\HighestEducationLevel" => "name",
                "App\Models\HighestSchoolDegree" => "name",
                "App\Models\PersonalModificationProcessChecklist" => "process",
                "App\Models\ProductGroup" => "name",
                "App\Models\ProductVersion" => "version",
                "App\Models\ProductCategory" => "name",
                "App\Models\ProductUnit" => "name",
                "App\Models\PaymentPeriod" => "name",
                "App\Models\PriceList" => "name",
                "App\Models\TermsOfPayment" => "name",
                "App\Models\Country" => "name",
                "App\Models\AffectedGroup" => "name",
                "App\Models\ReportCategory" => "name",
                "App\Models\PartnerOrder" => "order_number",
            ];
            return $array[$modelName];
        } catch (\Exception $exception){
            return null;
        }

    }
}
