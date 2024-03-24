<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Utils\Logger;
use Illuminate\Support\Facades\App;

class BaseModel extends Model
{
    public function save(array $options = [])
    {

        try {
            $originalAttributes = $this->getOriginal();
            $modifiedAttributes = $this->getDirty();
            $model = "App\Models\\" . class_basename($this);
            $routePath = "";
            $referer = request()->header('referer');
            // Parse the URL
            $parsedUrl = parse_url($referer);

            // Check if the path is present in the URL
            if (isset($parsedUrl['path'])) {
                // Now $parsedUrl['path'] contains the path without the domain and port
                $routePath = $parsedUrl['path'];
            }

            if (!empty($modifiedAttributes) && !empty($originalAttributes)) {
                $changedAttributesOriginal = collect($originalAttributes)->only(array_keys($modifiedAttributes))->toArray();
                Logger::auditLog([
                    'entity' => $this->getTable(),
                    'event' => 'Update',
                    'ip' => request()->ip(),
                    'userId' => request()->get('auth_user_id'),
                    'old' => $changedAttributesOriginal,
                    'new' => $modifiedAttributes,
                ]);
                ModuleHistory::create([
                    'moduleable_type' => $model,
                    'moduleable_id' => $this->getKey(),
                    'user_id' => request()->get('auth_user_id'),
                    'module_action' => 'update',
                    'request_ip' => request()->ip(),
                    'old_fields_data' => @$changedAttributesOriginal,
                    'new_fields_data' => @$modifiedAttributes,
                ]);
                if(!$model != 'App\Models\HashTag') {

                     $isCreated = MyFeed::create([
                        'moduleable_type' => $model,
                        'moduleable_id' => $this->getKey(),
                        'user_id' => request()->get('auth_user_id'),
                        'text' => "Changed the object",
                        'old_fields_data' => @$changedAttributesOriginal,
                        'new_fields_data' => @$modifiedAttributes,
                        'feed_path' => $routePath
                    ]);

                }
                parent::save($options);
            } else {
                // Call the parent save method
                parent::save($options);
                $moduleHistory = ModuleHistory::where('moduleable_type', $model)->where('moduleable_id', $this->getKey())
                    ->first();
                if (!$moduleHistory && $this->wasRecentlyCreated) {
                    ModuleHistory::create([
                        'moduleable_type' => $model,
                        'moduleable_id' => $this->getKey(),
                        'user_id' => request()->get('auth_user_id'),
                        'module_action' => 'create',
                        'request_ip' => request()->ip(),
                        'old_fields_data' => $this->getOriginal(),
                    ]);
                    if(!$model != 'App\Models\HashTag'){
                        MyFeed::create([
                            'moduleable_type' => $model,
                            'moduleable_id' => $this->getKey(),
                            'user_id' => request()->get('auth_user_id'),
                            'text' => "Created the object",
                            'feed_path' => $routePath
                        ]);
                    }
                }
            }
        } catch (\Throwable $exception) {
            // Call the parent save method
            return parent::save($options);
        }
    }
}
