<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up()
    {
        $components = \App\Models\SaleOfferComponent::with('offer')->whereNull('total_netto')->get();
        foreach ($components as $component){
            $totalNetto = 0;
            if ($component->type == 'license'){
                $totalNetto = ($component->sale_price * $component->quantity) -
                    ($component->sale_price * $component->quantity * $component->discount / 100);
            }elseif($component->type == 'maintenance'){
                $maintenancePrice = $component->sale_price * $component->quantity * $component->maintenance_rate / 100;
                $totalNetto = ($maintenancePrice) -
                    ($maintenancePrice * $component->discount / 100);
            }elseif($component->type == 'service' && ($component->offer?->grouped_by == 'nothing' || $component->offer?->grouped_by == 'category')){
                $totalNetto = ($component->hourly_rate * $component->quantity) - (($component->hourly_rate * $component->quantity * $component->discount) / 100);
            }elseif($component->type == 'application'){
                $totalNetto = ($component->hourly_rate * $component->quantity) - (($component->hourly_rate * $component->quantity * $component->discount) / 100);
            }elseif($component->type == 'hosting'){
                $totalNetto = ($component->price_per_period * $component->quantity) - (($component->price_per_period * $component->quantity * $component->discount) / 100);
            }elseif($component->type == 'cloud'){
                $totalNetto = ($component->duration * $component->quantity * $component->sale_price) - (($component->duration * $component->quantity * $component->sale_price * $component->discount) / 100);
            }
            $component->total_netto = $totalNetto;
            $component->save();
        }
    }

};
