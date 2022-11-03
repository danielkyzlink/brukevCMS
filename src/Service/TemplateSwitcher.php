<?php
namespace App\Service;

class TemplateSwitcher
{
    private string $skin = "";
    
    /*
     * --- TECHNIC SKIN ---
     * private string $skin = "technic"; 
     */
    
    public function switch(string $templateFile): string {
        $templateFilePath = '../templates/frontend-'.$this->skin.$templateFile;
        if (file_exists($templateFilePath)){
            //dd("ano");
            return 'frontend-'.$this->skin.$templateFile;
        }else{
            //dd("ne");
            return 'frontend'.$templateFile;
        }
    }
}

