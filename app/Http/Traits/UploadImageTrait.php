<?php

namespace App\Http\Traits;

use Exception;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

trait UploadImageTrait
{
    // Tuka: Store an image for services
    // firstly it will check if there is more than one dot in the image name, for security reasons
    // if yes, it will throw an error and not complete the operation
    // if no, it will save the image with this format: currentTime_random10letters.png
    public function storeImage($img, $folderName, $disk = 'images')
    {
        $originalName = $img->getClientOriginalName();
        if (preg_match('/\.[^.]+\./', $originalName)) {
            throw new Exception(trans('general.notAllowedAction'), 403);
        }
    
        // $photo =  time() . '_' . pathinfo($originalName, PATHINFO_FILENAME) .'.png';
        $photo = time() . '_' . Str::random(10) . '.png';
        $path = $img->storeAs($folderName, $photo, $disk);
        
        return $path;
    }

    public function deleteImage($path, $disk = 'images')
    {
        return Storage::disk($disk)->delete($path);
    }

    // don't use this method when you try to upload multiple images because  code runs so quick that the timestamp never changed
    public function verifyAndUploadImage($img, $directory, $imageName = null, $disk = 'images')
    {
        if (!$imageName) {
            $nameWithoutExtension = explode('.', $img->getClientOriginalName())[0];
            $imageName = $nameWithoutExtension . time() . '.' . $img->extension();
        } else {
            $imageName = $imageName . '-' . time() . '.' . $img->extension();
        }
        $path = $img->storeAs($directory, $imageName, $disk);
        return $path;
    }

    // time() method causes an issue because we have duplicate files named the same thing and duplicate file paths stored in the database.
    public function UploadMultipleImages($img, $directory, $imageName = null, $disk = 'images')
    {
        $name = Str::random(10);
        if (!$imageName) {
            $nameWithoutExtension = explode('.', $img->getClientOriginalName())[0];
            $imageName = $nameWithoutExtension . '-' . $name . '.' . $img->extension();
        } else {
            $imageName = $imageName . '-' . $name . '.' . $img->extension();
        }
        $path = $img->storeAs($directory, $imageName, $disk);
        return $path;
    }
}
