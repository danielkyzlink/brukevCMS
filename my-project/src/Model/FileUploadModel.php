<?php
namespace App\Model;

use Symfony\Component\HttpFoundation\File\Exception\FileException;

class FileUploadModel
{
    /**
     * @var string
     */
    public $totalFileName;
    
    /**
     * @var string
     */
    public $originalFileName;
    
    /**
     * @param array|string $images
     * @param string typ obrazku
     * @return string nazev obrazku
     */
    public function saveImage($images, $type="OTHER") {
        if (is_array($images)){
            $image = $images['file'];
        }else{
            $image = $images;
        }
        
        if ($type == "MASTER"){
            $dirName = __DIR__ . '/../../public/uploads/img/master/'; //toto asi neni uplne cajk reseni cesty k obrazkum :-(
        }else{
            $dirName = __DIR__ . '/../../public/uploads/img/'; //toto asi neni uplne cajk reseni cesty k obrazkum :-(
        }
        
        $this->originalFileName = $image->getClientOriginalName();
        $pictureName = $this->fileExistsAndRename($dirName, $image->getClientOriginalName());    
        
        // Move the file to the directory where brochures are stored
        try {
            $image->move($dirName, $pictureName);
        } catch (FileException $e) {
            // ... handle exception if something happens during file upload
            die("chyba ulozeni obrazku");
        }
        
        return $pictureName;
    }
    
    /**
     * @param string slozka obrazku
     * @param string nazev obrazku pro kontrol
     * @param int pocitadlo pruchodu
     * @return string vysledny finalni nazev pro ulozeni a zpis do DB
     */
    public function fileExistsAndRename($dirName, $fileName, $nConst = 0) {
        $nConst += 1;
        if (!$this->totalFileName){
            $this->totalFileName = $this->originalFileName;
        }
        
        if (file_exists($dirName . $fileName)){
            $this->totalFileName = $nConst . $this->originalFileName;
            $this->fileExistsAndRename($dirName, $this->totalFileName, $nConst);
        }
        return $this->totalFileName;
    }
}