<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductConfiguration;
use App\Models\ProductGroup;
use App\Models\ProductInstallation;
use App\Models\ProductParameter;
use App\Models\ProductRoutine;
use App\Models\ProductServiceChildren;
use App\Models\ProductServiceSoftwareChildren;
use App\Models\ProductSoftwareServiceChildren;
use App\Models\ProductVersion;
use App\Models\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Traits\NestedArrayTraits;
use App\Traits\SetGlobalNumber;


class ProductService
{
    use NestedArrayTraits, SetGlobalNumber;

    public function create(array $product_array)
    {
        $versions = [];
        $product = new Product;
        DB::transaction(function () use ($product_array, &$versions, $product) {
            $product = $this->saveProductsData($product, $product_array);
            $product->article_number = $this->assignProductNumber($product);
            $product->save();

            /** code to save product software childrens and service childrens */
            if ($product->payment_details_type == 'service' || $product->payment_details_type == 'pauschal' || $product->payment_details_type == 'ams' || $product->payment_details_type == 'traveling') {
                if (isset($product_array['productSoftwareChildrens'])) {
                    $childrens = [];
                    foreach ($product_array['productSoftwareChildrens'] as $data) {
                        $childrens[] = $data['versionId'];
                    }

                    $product->productServiceSoftwareChildrens()->sync($childrens);
                }

                if (isset($product_array['productServiceChildrens'])) {
                    $childrens = [];
                    foreach ($product_array['productServiceChildrens'] as $data) {
                        $childrens[] = $data['id'];
                    }
                    $product->productServiceChildrens()->sync($childrens);
                }
            } else {
                if (!empty($product_array['versions'])) {
                    foreach ($product_array['versions'] as $product_version_details) {
                        $product_version = new ProductVersion();
                        $product_version->version = $product_version_details['name'];
                        $product_version->product_id = $product->id;
                        $product_version->save();
                        $versions[] = ['id' => $product_version->id, 'name' => $product_version->version];

                        //storing routines
                        $this->saveProductRoutines($product_version, $product_version_details);
                        //storing dependent products
                        if (isset($product_version_details['dependentProducts'])) {
                            $array = [];
                            foreach ($product_version_details['dependentProducts'] as $data) {
                                $array[] = ['product_version_id' => $product_version->id, 'dependent_version_id' => $data['id']];
                            }

                            $product_version->dependencies()->sync($array);
                        }

                        //storing childrens
                        if (isset($product_version_details['productChildrens'])) {
                            $childrens = [];
                            foreach ($product_version_details['productChildrens'] as $data) {
                                $childrens[] = ['product_version_id' => $product_version->id, 'child_version_id' => $data['id']];
                            }

                            $product_version->childrens()->sync($childrens);
                        }

                        //storing services childrens for software
                        if (isset($product_version_details['versionServiceChildrens'])) {
                            $childrens = [];
                            foreach ($product_version_details['versionServiceChildrens'] as $data) {
                                $childrens[] = ['product_version_id' => $product_version->id, 'product_id' => $data['id']];
                            }

                            $product_version->productSoftwareServiceChildrens()->sync($childrens);
                        }

                        //saving parameters if exist
                        if (!empty($product_version_details['parameters'])) {
                            foreach ($product_version_details['parameters'] as $parameter) {
                                if (isset($parameter['key'])) {
                                    $model_parameter = new ProductParameter;
                                    $model_parameter->type = $parameter['type'];
                                    $model_parameter->key =  $parameter['key'];
                                    $model_parameter->value =  $parameter['value'];
                                    $model_parameter->file_name = $parameter['file'] ?? "";
                                    $model_parameter->product_version_id = $product_version->id;
                                    $model_parameter->save();
                                }
                            }
                        }
                        //saving installations if exist
                        if (!empty($product_version_details['installations'])) {
                            foreach ($product_version_details['installations'] as $installation) {
                                if (isset($installation['subject'])) {
                                    $model_parameter = new ProductInstallation;
                                    $model_parameter->subject = $installation['subject'];
                                    $model_parameter->time_needed =  $installation['time'] ?? '';
                                    $model_parameter->details =  $installation['detail'] ?? '';
                                    $model_parameter->product_version_id = $product_version->id;
                                    $model_parameter->save();
                                }
                            }
                        }
                        //saving configurations if exist
                        if (!empty($product_version_details['configurations'])) {
                            foreach ($product_version_details['configurations'] as $configuration) {
                                if (isset($configuration['subject'])) {
                                    $model_parameter = new ProductConfiguration;
                                    $model_parameter->subject = $configuration['subject'];
                                    $model_parameter->time_needed = $configuration['time'] ?? '';
                                    $model_parameter->details = $configuration['detail'] ?? '';
                                    $model_parameter->product_version_id = $product_version->id;
                                    $model_parameter->save();
                                }
                            }
                        }
                    }
                }
            }
        });
        return ["versions" => $versions, "id" => $product->id];
    }

    public function uploadFiles(array $product_version_files)
    {

        if (isset($product_version_files['versions'])) {
            foreach ($product_version_files['versions'] as $product_version) {
                if (isset($product_version['files'])) {
                    foreach ($product_version['files'] as $file) {
                        $product_version = ProductVersion::findOrFail($product_version['id']);
                        $original_name = $file->getClientOriginalName();
                        $size = $file->getSize();
                        $file_name_to_store =
                            $product_version->product->article_number . '__' . $original_name;
                        Storage::disk('local')->putFileAs('product/files/', $file, $file_name_to_store);
                        $uploaded_file = new UploadedFile;
                        $uploaded_file->storage_name = $file_name_to_store;
                        $uploaded_file->viewable_name = $original_name;
                        $uploaded_file->storage_size =  $size;
                        $uploaded_file->fileable()->associate($product_version);
                        $uploaded_file->save();
                    }
                }
            }
        }
        return response()->json(['message' => 'Files uploaded successfully'], 200);
    }

    public function update($id, array $product_array)
    {
        $versions = [];
        DB::transaction(function () use ($id, $product_array, &$versions) {

            $product = Product::findOrFail($id);
            $product = $this->saveProductsData($product, $product_array);
            if ($product->isDirty('payment_details_type')) {
                $product->article_number = $this->assignProductNumber($product);
            }
            $product->save();

            // ** //

            $is_new = false;
            //add product service childrens
            /** code to save product software childrens and service childrens */
            if ($product->payment_details_type == 'service' || $product->payment_details_type == 'pauschal' || $product->payment_details_type == 'traveling') {
                if (isset($product_array['productSoftwareChildrens'])) {
                    $childrens = [];
                    foreach ($product_array['productSoftwareChildrens'] as $data) {
                        $childrens[] = $data['versionId'];
                    }
                    $product->productServiceSoftwareChildrens()->sync($childrens);
                }

                if (isset($product_array['productServiceChildrens'])) {
                    $childrens = [];
                    foreach ($product_array['productServiceChildrens'] as $data) {
                        $childrens[] = $data['id'];
                    }
                    $product->productServiceChildrens()->sync($childrens);
                }
            } else if ($product->payment_details_type == 'software' || $product->payment_details_type == 'cloud-software' || $product->payment_details_type == 'hosting') {
                $product_versions = $product_array['versions'];
                //remove versions if not present in an array
                $product_previous_versions = $product->versions->pluck('id');
                $versions_to_remove = $product_previous_versions->diff(collect($product_array['versions'])->pluck('id'));
                ProductVersion::whereIn('id', $versions_to_remove)->delete();
                foreach ($product_versions as $version) {
                    if (isset($version['id'])) {
                        $product_version = ProductVersion::findOrFail($version['id']);
                    } else {
                        $is_new = true;
                        $product_version = new ProductVersion;
                        $product_version->version = $version['name'];
                        $product_version->product_id = $product->id;
                        $product_version->save();
                    }
                    $this->saveProductRoutines($product_version, $version, $is_new);
                    $versions[] = ['id' => $product_version->id, 'name' => $product_version->version];
                    $array = [];
                    if (isset($version['dependentProducts'])) {
                        foreach ($version['dependentProducts'] as $data) {
                            $array[] = $data['versionId'];
                        }
                    }
                    $product_version->dependencies()->sync($array);

                    $childrens = [];
                    //storing childrens
                    if (isset($version['productChildrens'])) {

                        foreach ($version['productChildrens'] as $data) {
                            $childrens[] = $data['versionId'];
                        }
                    }
                    $product_version->childrens()->sync($childrens);


                    //updating services childrens for software
                    if (isset($version['versionServiceChildrens'])) {
                        $childrens = [];
                        foreach ($version['versionServiceChildrens'] as $data) {
                            $childrens[] = $data['id'];
                        }

                        $product_version->productSoftwareServiceChildrens()->sync($childrens);
                    }

                    //add params
                    if (isset($version['parameters'])) {
                        $product_version->productParameters()->delete();
                        foreach ($version['parameters'] as $parameter) {
                            if (isset($parameter['key']) && isset($parameter['value'])) {
                                $model_parameter = new ProductParameter;
                                $model_parameter->type = $parameter['type'];
                                $model_parameter->key =  $parameter['key'];
                                $model_parameter->value =  $parameter['value'];
                                $model_parameter->file_name = $parameter['file'] ?? "";
                                $model_parameter->product_version_id = $product_version->id;
                                $model_parameter->save();
                            }
                        }
                    } else {
                        $product_version->productParameters()->delete();
                    }

                    //update installations
                    if (isset($version['installations'])) {
                        //removing records not present installations array
                        $existing_parameters_ids = ProductInstallation::where('product_version_id', $product_version->id)->pluck('id');
                        $parameters_to_remove = $existing_parameters_ids->diff(collect($version['installations'])->pluck('id'));
                        ProductInstallation::whereIn('id', $parameters_to_remove)->delete();
                        foreach ($version['installations'] as $installation) {
                            if (isset($installation['id'])) {
                                $model_installation = ProductInstallation::find($installation['id']);
                            } else {
                                $model_installation = new ProductInstallation;
                                $model_installation->product_version_id = $product_version->id;
                            }
                            if (isset($installation['subject'])) {
                                $model_installation->subject = $installation['subject'];
                                $model_installation->details = $installation['detail'] ?? '';
                                $model_installation->time_needed = $installation['time'] ?? '';
                                $model_installation->save();
                            }
                        }
                    }

                    //update configurations
                    if (isset($version['configurations'])) {
                        //removing records not present configurations array
                        $existing_parameters_ids = ProductConfiguration::where('product_version_id', $product_version->id)->pluck('id');
                        $parameters_to_remove = $existing_parameters_ids->diff(collect($version['configurations'])->pluck('id'));
                        ProductConfiguration::whereIn('id', $parameters_to_remove)->delete();
                        foreach ($version['configurations'] as $configuration) {
                            if (isset($configuration['id'])) {
                                $model_configuration = ProductConfiguration::find($configuration['id']);
                            } else {
                                $model_configuration = new ProductConfiguration;
                                $model_configuration->product_version_id = $product_version->id;
                            }
                            if (isset($configuration['subject'])) {
                                $model_configuration->subject = $configuration['subject'];
                                $model_configuration->details = $configuration['detail'] ?? '';
                                $model_configuration->time_needed = $configuration['time'] ?? '';
                                $model_configuration->save();
                            }
                        }
                    }
                }
            } else {
                if (isset($product->versions)) {
                    $product->versions()->delete();
                }
            }
        });

        return $versions;
    }

    public function updateFiles(array $product_version_files)
    {
        foreach ($product_version_files as $product_version_file) {
            if (gettype($product_version_file) == 'array') {
                foreach ($product_version_file as $product_version) {
                    if (isset($product_version['files'])) {
                        $new_files_collection = collect($product_version['files']);
                        $product_version = ProductVersion::findOrFail($product_version['id']);

                        $files_to_be_deleted = $product_version->files->whereNotIn('storage_name', $new_files_collection->pluck('storage_name'));
                        $files_to_be_added = $new_files_collection->whereNotIn('storage_name', $product_version->files->pluck('storage_name'));
                        if (!empty($files_to_be_deleted)) {
                            foreach ($files_to_be_deleted as $delete_file) {
                                if (Storage::exists('product/files/' . $delete_file['storage_name'])) {
                                    UploadedFile::find($delete_file['id'])->delete();
                                    Storage::delete('product/files/' . $delete_file['storage_name']);
                                }
                            }
                        }
                        //add files
                        if (!empty($files_to_be_added)) {
                            foreach ($files_to_be_added as $file) {
                                $original_name = $file->getClientOriginalName();
                                $size = $file->getSize();
                                $file_name_to_store =
                                    $product_version->product->article_number . '_' . $original_name;
                                Storage::disk('local')->putFileAs('product/files/', $file, $file_name_to_store);
                                $uploaded_file = new UploadedFile;
                                $uploaded_file->storage_name = $file_name_to_store;
                                $uploaded_file->viewable_name = $original_name;
                                $uploaded_file->storage_size =  $size;
                                $uploaded_file->fileable()->associate($product_version);
                                $uploaded_file->save();
                            }
                        }
                    } else {
                        if (gettype($product_version) == 'array') {
                            if (isset($product_version->files)) {
                                $product_version->files()->delete();
                            }
                        }
                    }
                }
            }
        }
        return response()->json(['message' => 'Files updated successfully'], 200);
    }

    private function saveProductRoutines(ProductVersion $product_version, array $product_array, $is_new = true)
    {
        if ($is_new) {
            $product = new ProductRoutine();
        } else {
            $product = ProductRoutine::where("product_version_id", $product_version->id)->firstOrFail();
        }
        /** windows install routines */
        $product->premise_windows_install_routine = $product_array['windowsPremiseRoutine'] ?? null;
        $product->private_windows_install_routine = $product_array['windowsPrivateRoutine'] ?? null;
        $product->public_windows_install_routine = $product_array['windowsPublicRoutine'] ?? null;
        /** linux install routines */
        $product->premise_linux_install_routine = $product_array['linuxPremiseRoutine'] ?? null;
        $product->private_linux_install_routine = $product_array['linuxPrivateRoutine'] ?? null;
        $product->public_linux_install_routine = $product_array['linuxPublicRoutine'] ?? null;
        /** mac install routines */
        $product->premise_mac_install_routine = $product_array['macPremiseRoutine'] ?? null;
        $product->private_mac_install_routine = $product_array['macPrivateRoutine'] ?? null;
        $product->public_mac_install_routine = $product_array['macPublicRoutine'] ?? null;

        /** windows uninstall routines */
        $product->premise_windows_uninstall_routine = $product_array['windowsUninstallPremiseRoutine'] ?? null;
        $product->private_windows_uninstall_routine = $product_array['windowsUninstallPrivateRoutine'] ?? null;
        $product->public_windows_uninstall_routine = $product_array['windowsUninstallPublicRoutine'] ?? null;

        /** linux uninstall routines */
        $product->premise_linux_uninstall_routine = $product_array['linuxUninstallPremiseRoutine'] ?? null;
        $product->private_linux_uninstall_routine = $product_array['linuxUninstallPrivateRoutine'] ?? null;
        $product->public_linux_uninstall_routine = $product_array['linuxUninstallPublicRoutine'] ?? null;

        /** mac uninstall routines */
        $product->premise_mac_uninstall_routine = $product_array['macUninstallPremiseRoutine'] ?? null;
        $product->private_mac_uninstall_routine = $product_array['macUninstallPrivateRoutine'] ?? null;
        $product->public_mac_uninstall_routine = $product_array['macUninstallPublicRoutine'] ?? null;
        $product->product_version_id = $product_version->id;
        $product->save();
        return $product;
    }


    private function saveProductsData(Product $product, array $product_array)
    {
        $product->name = $product_array['name'];
        $product->status =  $product_array['status'];
        $product->description = $product_array['description'] ?? null;
        $product->listing_price = $product_array['listingPrice'] ?? null;
        $product->discount =  $product_array['discount'] ?? null;
        //
        $product->payment_period_id = $product_array['paymentPeriod'] ?? null;
        //
        $product->sale_price = $product_array['salePrice'] ?? null;
        $product->profit = $product_array['profit'] ?? null;
        $product->tax = $product_array['tax'];
        $product->recurring_payment = $product_array['recurringPayment'] ?? null;
        $product->execution_order_number = $product_array['executionNumber'] ?? null;
        $product->maintenance_rate = $product_array['maintanenceRate'] ?? null;
        $product->maintanence_price = $product_array['maintanencePrice'] ?? null;
        $product->manufacturer_article_number = $product_array['manufacturerNumber'] ?? null;
        $product->product_group_id = isset($product_array['productGroup']['id']) ? $product_array['productGroup']['id'] : null;
        $product->elo_version_id = isset($product_array['eloVersion']['id']) ? $product_array['eloVersion']['id'] : null;
        $product->product_category_id = isset($product_array['productCategoryId']) ? $product_array['productCategoryId'] : null;
        $product->manufacturer_discount = isset($product_array['manufacturerDiscount']) ? $product_array['manufacturerDiscount'] : null;
        $product->payment_details_type = isset($product_array['paymentDetailsType']) ? $product_array['paymentDetailsType'] : null;
        //
        $product->product_unit_id = $product_array['unit'] ?? null;
        $product->product_software_id = $product_array['productSoftware'] ?? null;
        $product->product_price_id = $product_array['productPrice'] ?? null;
        $product->service_hours = $product_array['serviceHours'] ?? null;
        $product->service_days = $product_array['serviceDays'] ?? null;
        //
        $product->hourly_rate = isset($product_array['hourlyRate']) ? $product_array['hourlyRate'] : null;
        $product->daily_rate = isset($product_array['dailyRate']) ? $product_array['dailyRate'] : null;
        $product->quantity = isset($product_array['quantity']) ? $product_array['quantity'] : null;
        //
        $product->price_per_period = isset($product_array['pricePerPeriod']) ? $product_array['pricePerPeriod'] : null;
        $product->fixed_price = isset($product_array['fixedPrice']) ? $product_array['fixedPrice'] : null;

        //saving ams type
        $is_product_name_edit = 0;

        $product->ams_type = $product_array['amsType'] ?? null;
        $product->infrastructure_setting_id = $product_array['infrastructureSetting']['id'] ?? null;

        if(($product_array['isProductNameEdit']) ==='true' ){

            $is_product_name_edit = 1;
        }

        $product->is_product_name_edit = $is_product_name_edit;
        return $product;
    }

    public function getNestedLayoutForProductVersionChildrens($product_version_id = null)
    {
        $categories = ProductCategory::get();
        $service_products_array = [];
        foreach ($categories as $category) {
            $service_products = $category->servicePauschalProducts;
            foreach ($service_products as $product) {

                $product_array = [
                    'productId' => $product->id,
                    'productName' => $product->name,
                    'articleNumber' => $product->article_number
                ];


                $product_array['isChild'] = ProductSoftwareServiceChildren::where('product_id', $product->id)
                    ->where('product_version_id', $product_version_id)->count() < 1 ? false : true;

                $service_products_array[$category->name][] = $product_array;
            }
        }
        return $service_products_array;
    }

    public function getNestedLayoutForProductChildrens($is_check_for_children = false, $product_id = null)
    {
        $groups = ProductGroup::get();
        $parent_array = [];
        //get services products
        $service_products_array = [];
        foreach ($groups as $group) {
            $software_products = $group->softwareProducts;
            foreach ($software_products as $product) {
                $parent_array[$group->name][$product->name][$product->id] = [];
                $versions = $product->versions->pluck('version', 'id');
                $new_key = [];
                foreach ($versions as $key => $version) {
                    $exploded = explode(".", $version);
                    $concat_version_parts = "";
                    $get_all_parents = "";
                    $version_part_count = count($exploded);
                    for ($i = 0; $i < $version_part_count; $i++) {
                        $versionPart = $exploded[$i];

                        $currParent = $concat_version_parts . "X" . str_repeat(".X", $version_part_count - $i - 1);
                        $concat_version_parts .= $versionPart;

                        $concat_version_parts .= ".";
                        $get_all_parents .= $currParent;
                        $get_all_parents .= "|";
                    }
                    $get_all_parents = substr($get_all_parents, 0, -1);

                    $to_be_nested_array = [
                        'value' => substr($concat_version_parts, 0, -1),
                        'productId' => $product->id, 'versionId' => $key, 'id' => $product->article_number
                    ];
                    if ($is_check_for_children) {
                        $to_be_nested_array['isChild'] = ProductServiceSoftwareChildren::where('product_id', $product_id)
                            ->where('product_version_id', $key)->count() < 1 ? false : true;
                    }
                    $new_key = NestedArrayTraits::set($new_key, $get_all_parents, $to_be_nested_array);
                    $parent_array[$group->name][$product->name][$product->id] = $new_key;
                }
            }
        }
        $categories = ProductCategory::get();
        foreach ($categories as $category) {
            $service_products = $category->servicePauschalProducts;
            foreach ($service_products as $product) {

                $product_array = [
                    'productId' => $product->id,
                    'productName' => $product->name,
                    'articleNumber' => $product->article_number
                ];
                if ($is_check_for_children) {
                    $product_array['isChild'] = ProductServiceChildren::where('product_id', $product_id)
                        ->where('child_id', $product->id)->count() < 1 ? false : true;
                }
                $service_products_array[$category->name][] = $product_array;
            }
        }
        return ['servicesChildrens' => $service_products_array, 'childProducts' => $parent_array];
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
