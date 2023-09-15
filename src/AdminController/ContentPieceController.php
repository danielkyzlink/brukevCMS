<?php
namespace App\AdminController;

use App\Entity\ContentPiece;
use App\Model\ContentPieceModel;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ContentPieceController extends AbstractController
{
    public $em;
    public function __construct(EntityManagerInterface $entityManager){
        $this->em = $entityManager;
    }
    #[Route("admin/contentPiece/edit/{section}", name: "editContents", defaults: ["section" => "klub"])]
    public function editContents(string $section, Request $request, ContentPieceModel $contentPieceModel){
        $contents = $this->em->getRepository(ContentPiece::class)
            ->findBy(array(
                'section' => $section,
            ));
        if(!$contents){
            throw $this->createNotFoundException("Sekce " . $section . "nenalezena v db.");
        }

        $form = $this->createFormBuilder();
        foreach ($contents as $row){
            if($row->getType() == ContentPiece::TYPE_TEXTAREA){
                $type = TextareaType::class;
                if($row->isPlain()){
                    $class = "";
                }else{
                    $class = "tinymce";
                }
            }elseif ($row->getType() == ContentPiece::TYPE_TEXT){
                $type = TextType::class;
                $class = "";
            }



            $form->add($row->getId(), $type, array(
                'label' => $row->getName(),
                'data' => $row->getText(),
                'attr' => ['class' => $class],
            ));
        }
        $form->add('save', SubmitType::class, ['label' => 'Ulož změny']);
        $form = $form->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $contentsData = $form->getData();
            $contentPieceModel->saveContens($contentsData);
        }


        //vykresleni sablony s formularem
        return $this->render('admin/contentPiece/editContents.html.twig', [
            //'configData' => $configData,
            'form' => $form->createView(),
        ]);
    }
}