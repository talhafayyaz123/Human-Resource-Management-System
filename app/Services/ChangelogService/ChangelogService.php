<?php

namespace App\Services\ChangelogService;

use App\Models\Changelog;

class ChangelogService
{
    /**
     * Get all changelogs.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllChangelogs($request)
    {
        return Changelog::orderBy('created_at', 'desc')->get();
    }
    /**
     * Store a new changelog.
     *
     * @param array $data The data for the new changelog.
     * @return Changelog The created changelog.
     */
    public function storeChangelog(array $data)
    {
        // Validate and create the changelog
        $changelog = Changelog::create($data);

        return $changelog;
    }

    /**
     * Update an existing changelog.
     *
     * @param int $id The ID of the changelog to update.
     * @param array $data The updated data for the changelog.
     * @return Changelog The updated changelog.
     */
    public function updateChangelog(int $id, array $data)
    {
        // Find the changelog
        $changelog = Changelog::findOrFail($id);

        // Update and save the changelog
        $changelog->update($data);

        return $changelog;
    }

    /**
     * Delete an changelog.
     *
     * @param int $id The ID of the changelog to delete.
     * @return bool True if the changelog was deleted successfully.
     */
    public function deleteChangelog(int $id)
    {
        // Find the changelog
        $changelog = Changelog::findOrFail($id);

        // Delete the changelog
        $changelog->delete();

        return true;
    }
}
