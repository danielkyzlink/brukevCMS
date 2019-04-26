<?php
namespace App\Model;

use Symfony\Component\HttpFoundation\File\Exception\FileException;

class FileUploadModel
{
    
    public function saveImage($images) {
        $image = $images['file'];
        
        // Move the file to the directory where brochures are stored
        try {
            $image->move(
                __DIR__ . '/../../public/uploads/img/',  //toto asi neni uplne cajk reseni cesty k obrazkum :-(
                $image->getClientOriginalName()
                );
        } catch (FileException $e) {
            // ... handle exception if something happens during file upload
            die("chyba ulozeni obrazku");
        }
        
        return $image->getClientOriginalName();
    }
}