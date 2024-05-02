<?php

namespace App\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

trait HasPhoto
{
    /**
     * Update the photo associated with the specified column.
     *
     * @param  UploadedFile  $photo The uploaded photo file.
     * @param  string  $column The column name representing the photo path.
     * @param  string  $path The storage path for the new photo.
     */
    public function updatePhoto(UploadedFile $photo, string $column, string $path): void
    {
        tap($column, function ($previous) use ($photo, $column, $path) {
            $this->forceFill([
                $column => $photo->storePublicly(
                    $path, ['disk' => 'public']
                ),
            ])->save();

            // delete the previous photo from storage
            if ($previous) {
                Storage::disk('public')->delete($previous);
            }
        });
    }

    /**
     * Delete the photo file and update the specified column in the database.
     *
     * @param  string  $path   The file path of the photo to be deleted.
     * @param  string  $column The database column to be updated with null.
     */
    public function deletePhoto(string $path, string $column): void
    {
        Storage::disk('public')->delete($path);

        $this->forceFill([
            $column => null,
        ])->save();
    }
}
