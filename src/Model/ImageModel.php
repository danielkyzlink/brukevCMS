<?php
namespace App\Model;

use Imagick;

class ImageModel
{
    /*function loadWorkingImages(): array{
        $imagesWorkingFolder = "./workingImages/";
        $images = glob($imagesWorkingFolder."*.jpg");

        return $images;
    }*/
    
    function resizeImages(array $imagesPathArray, int $width = 1920, int $height = 1080){
        //$images = $this->loadWorkingImages();
        $images = $imagesPathArray;
        $i = 0;
        foreach ($images as $image){
            $i ++;
            $imageFilename = $image;
            $image = "uploads/gallery/original/" . $image;

            $imagick = new Imagick($image);
                        
            if ($imagick->getImageWidth() > $width || $imagick->getImageHeight() > $height){
                $imagick->resizeImage($width, $height, Imagick::FILTER_LANCZOS, 1, true);
                /*
                $imageFilename = pathinfo($imagick->getImageFilename());
                $imageFilename = $imageFilename['basename'];
                */
                $imagick->writeImage("uploads/gallery/medium/" . $imageFilename);
                //echo("Změnšuju...<br>");
            }else{
                copy("uploads/gallery/original/" . $imageFilename, "uploads/gallery/medium/" . $imageFilename);
                //echo("Obrázek je malej.<br>");
            }            
        }
    }
}