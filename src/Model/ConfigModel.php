<?php
namespace App\Model;

use App\Entity\Config;
use Doctrine\ORM\EntityManagerInterface;

class ConfigModel
{
    public $em;
    
    public function __construct(EntityManagerInterface $entityManager){
        $this->em = $entityManager;
    }
    
    public function showConfig() {
        $config = $this->em
        ->getRepository(Config::class)
        ->findAll();
        
        return $config;
    }
    
    public function saveConfig($configData) {
        foreach($configData as $key => $value){
            $row = $this->em
                ->getRepository(Config::class)
                ->findOneBy(['textId' => $key]);
            $row->setValue($value);
            $this->em->flush(); 
        }
    }
    
    public function getConfigParameter($textId){
        $parameterRow = $this->em
            ->getRepository(Config::class)
            ->findOneBy(['textId' => $textId]);
        $value = $parameterRow->getValue();
        
        switch($parameterRow->getType()){
            case "int":
                $value = (int)$value;
                break;
            case "boolean":
                if($value){
                    $value = true;
                }else{
                    $value = false;
                }
                break;
        }
        
        return $value;
    }
}