<?php
namespace App\Helpers;
use Intervention\Image\Facades\Image;

trait RotateImage
{
    public function RotateImage($file,$path){
        $image=Image::make($file)->rotate(-90)->resize('1240','1748');

        $image->save($path)->exif();
    }
}


