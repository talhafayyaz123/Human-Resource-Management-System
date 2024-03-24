<?php

namespace App\Traits;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;

trait CustomHelper
{

    /**
     * Check if the current authenticated user has the given role
     * @param \Illuminate\Http\Request
     * @param string $role
     *
     */
    public function hasRole($request, $role)
    {
        $roles = $request->get('auth_user_roles');
        if (in_array($role, $roles)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Get the authenticated user id from the token
     * @param \Illuminate\Http\Request
     *
     */
    public function getAuthUserId($request)
    {
        return $request->get('auth_user_id');
    }

    /**
     * Get the authenticated user tenant id from the token
     * @param \Illuminate\Http\Request
     *
     */
    public function getAuthTenantId($request)
    {
        return $request->get('auth_user_tenant_id');
    }

    /**
     * This method is used to validate the parsed information.
     * @param \Illuminate\Http\Request
     *
     */
    public function getAuthUserType($request)
    {
        return $request->get('auth_user_type');
    }

    /**
     * Apply sorting to an Eloquent query builder instance.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     *         The query builder instance to apply sorting to.
     * @param  string|null  $sortBy
     *         The column to sort by.
     * @param  string|null  $sortOrder
     *         The sort order, either 'asc' or 'desc'.
     * @return void
     */
    public function applySorting($collection, $sortBy, $sortOrder)
    {
        return $collection->sortBy($sortBy, SORT_REGULAR, $sortOrder == 'asc' ? false : true)->values();
    }

    public function convertKeysToSnakeCase(Collection $collection): array
    {
        $collection = $collection->mapWithKeys(function ($value, $key) {
            return [Str::snake($key) => $value];
        })->toArray();
        return $collection;
    }


    public function applySortingBeforePagination($model, $sortBy, $sortOrder)
    {
        $sortByParts = explode('.', $sortBy);

        if (count($sortByParts) > 1) {
            // Handle sorting with nested relationships
            $nestedColumn = Str::snake(array_pop($sortByParts));
            $nestedRelationship = implode('.', $sortByParts);             //not possible without the use of joins
            $relatedModel = "App\Models\\" . $nestedRelationship;

            $modelInstance = app($relatedModel);
            $foreignKeyName = $model->{$nestedRelationship}()->getForeignKeyName();
            $model = $model::leftJoin($modelInstance->getTable(), "{$model->getTable()}.{$foreignKeyName}", "=", "{$modelInstance->getTable()}.id")
                ->select("{$modelInstance->getTable()}.*", "{$model->getTable()}.*")
                ->orderBy("{$modelInstance->getTable()}.{$nestedColumn}", $sortOrder);
        } else {
            $sortByParts = explode('-', $sortBy);
            if (count($sortByParts) > 1) {

                // Handle sorting with a date range
                $startColumn = Str::snake($sortByParts[0]);
                $endColumn = Str::snake($sortByParts[1]);

                $model = $model->orderBy($startColumn, $sortOrder)
                    ->orderBy($endColumn, $sortOrder);
            } else {
                $columnName = Str::snake($sortByParts[0]);
                if (strpos($columnName, '_numeric') !== false) {
                    // Remove "_numeric" from the column name for sorting
                    $numericColumnName = str_replace('_numeric', '', $columnName);
                    $model = $model->orderByRaw('CAST(' . $numericColumnName . ' AS SIGNED) ' . $sortOrder);
                } else {
                    $model = $model->orderBy($columnName, $sortOrder);
                }
            }
        }

        return $model;
    }


    public function dashboardTimeTrackerSorting($model, $sortBy, $sortOrder)
    {

        $sortByParts = explode('.', $sortBy);
        if (count($sortByParts) > 1) {

            if ($sortByParts[0] === 'Company') {
                $model = $model->leftJoin('companies', 'time_tracker_companies.company_id', '=', 'companies.id') //not possible without left join
                    ->select('time_tracker_companies.*')->orderBy("companies.company_name", $sortOrder);
            } else {
                $model = $model->leftJoin('user_profile_data', 'time_tracker_companies.user_id', '=', 'user_profile_data.user_id') //not possible without left join
                    ->select('time_tracker_companies.*')->orderBy("user_profile_data.first_name", $sortOrder);
            }
        } else {

            $model = $model->orderBy(Str::snake($sortBy), $sortOrder);
        }
        return $model;
    }


    public function getSelectedIds($model, $collection, $selectedIds)
    {
        $selected_records = $model->whereIn('id', $selectedIds)->get();
        return $selected_records->concat($collection);
    }

    /**
     * converts german number format to regular decimal format
     * @param string $germanNumber
     * @return float 
     */
    public function convertGermanNumberToDecimal($germanNumber)
    {
        // Replace commas with periods and vice versa
        $numberWithDot = str_replace(',', '.', $germanNumber);
        $numberWithComma = str_replace('.', ',', $numberWithDot);

        // Convert the string to a float
        $decimalNumber = floatval($numberWithComma);

        return $decimalNumber;
    }
}
