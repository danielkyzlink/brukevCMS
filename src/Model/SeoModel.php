<?php
namespace App\Model;

//use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;

class SeoModel
{
    public $em;
    
    public function __construct(EntityManagerInterface $entityManager) {
        $this->em = $entityManager;
    }
    
    public function createSeoTitle(String $nameOfArticle, $entita = \App\Entity\Article::class, int $iterace = 0) {
        if ($iterace == 0){
            $append = "";
        }else{
            $append = " " . $iterace;
        }
        
        $prevodni_tabulka = Array(
            'ä'=>'a',
            'Ä'=>'A',
            'á'=>'a',
            'Á'=>'A',
            'à'=>'a',
            'À'=>'A',
            'ã'=>'a',
            'Ã'=>'A',
            'â'=>'a',
            'Â'=>'A',
            'č'=>'c',
            'Č'=>'C',
            'ć'=>'c',
            'Ć'=>'C',
            'ď'=>'d',
            'Ď'=>'D',
            'ě'=>'e',
            'Ě'=>'E',
            'é'=>'e',
            'É'=>'E',
            'ë'=>'e',
            'Ë'=>'E',
            'è'=>'e',
            'È'=>'E',
            'ê'=>'e',
            'Ê'=>'E',
            'í'=>'i',
            'Í'=>'I',
            'ï'=>'i',
            'Ï'=>'I',
            'ì'=>'i',
            'Ì'=>'I',
            'î'=>'i',
            'Î'=>'I',
            'ľ'=>'l',
            'Ľ'=>'L',
            'ĺ'=>'l',
            'Ĺ'=>'L',
            'ń'=>'n',
            'Ń'=>'N',
            'ň'=>'n',
            'Ň'=>'N',
            'ñ'=>'n',
            'Ñ'=>'N',
            'ó'=>'o',
            'Ó'=>'O',
            'ö'=>'o',
            'Ö'=>'O',
            'ô'=>'o',
            'Ô'=>'O',
            'ò'=>'o',
            'Ò'=>'O',
            'õ'=>'o',
            'Õ'=>'O',
            'ő'=>'o',
            'Ő'=>'O',
            'ř'=>'r',
            'Ř'=>'R',
            'ŕ'=>'r',
            'Ŕ'=>'R',
            'š'=>'s',
            'Š'=>'S',
            'ś'=>'s',
            'Ś'=>'S',
            'ť'=>'t',
            'Ť'=>'T',
            'ú'=>'u',
            'Ú'=>'U',
            'ů'=>'u',
            'Ů'=>'U',
            'ü'=>'u',
            'Ü'=>'U',
            'ù'=>'u',
            'Ù'=>'U',
            'ũ'=>'u',
            'Ũ'=>'U',
            'û'=>'u',
            'Û'=>'U',
            'ý'=>'y',
            'Ý'=>'Y',
            'ž'=>'z',
            'Ž'=>'Z',
            'ź'=>'z',
            'Ź'=>'Z'
        );        
        
        $seoTitle = $nameOfArticle . $append;
        $seoTitle  = strtr($seoTitle, $prevodni_tabulka);
        $seoTitle = str_replace(" ", "-", $seoTitle);
        $seoTitle = preg_replace('~[^-a-zA-Z0-9_]+~', '', $seoTitle); //vyhodi skarede znaky binec
        
        $article = $this->em
        ->getRepository($entita)
        ->findOneBy(array('seoTitle' => $seoTitle));
        
        if (!$article) {
            return $seoTitle;
        }else{
            $iterace += 1;
            return $this->createSeoTitle($nameOfArticle, $iterace);
        }
    }
}