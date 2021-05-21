<?php

namespace App\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Str;

/**
 * Trait UploadAble
 * @package App\Traits
 */
trait UploadAble
{
    /**
     * @param UploadedFile $file
     * @param string $folder
     * @param int $width
     * @param int $height
     * @return false|string
     */
    public function uploadOne(UploadedFile $file, int $width, int $height, string $folder = 'images/')
    {
        $generateName = date('Ymdhms') . Str::random(6);
        $generateNameWithExt = '/' . $generateName . "." . $file->getClientOriginalExtension();
        if (!File::exists(public_path($folder))) {
            File::makeDirectory(public_path($folder), 0777, true);
        }
        Image::make($file)->resize($width, $height)->save(public_path($folder . $generateNameWithExt)); //resizing image
        return $generateNameWithExt;
    }

    public function uploadWithWatermark(UploadedFile $file, int $width, int $height, string $position, int $x, int $y, string $folder = 'images/')
    {
        $generateName = date('Ymdhms') . Str::random(6);
        $generateNameWithExt = '/' . $generateName . "." . $file->getClientOriginalExtension();
        if (!File::exists(public_path($folder))) {
            File::makeDirectory(public_path($folder), 0777, true);
        }
        Image::make($file)->resize($width, $height)->insert(public_path('images/watermark.png'), $position, $x, $y)->save(public_path($folder . $generateNameWithExt)); //resizing image
        return $generateNameWithExt;
    }

    /**
     * @param null $path
     * @param string $disk
     */
    public function deleteOne($directory, $filename)
    {
        File::delete(public_path($directory . $filename));
    }
}