<?php
namespace App\Model;

use App\Entity\ContentPiece;
use Doctrine\ORM\EntityManagerInterface;

class ContentPieceModel
{
    public $em;

    public function __construct(EntityManagerInterface $entityManager){
        $this->em = $entityManager;
    }

    public function saveContens($contentsData){
        foreach ($contentsData as $key => $value){
            $row = $this->em->getRepository(ContentPiece::class)
                            ->findOneBy(['id' => $key]);
            $row->setText($value);
            $this->em->flush();
        }
    }

    public function showContentsBySection(string $section) {
        $contents = $this->em
            ->getRepository(ContentPiece::class)
            ->findBy(['section' => $section]);

        return $contents;
    }
}