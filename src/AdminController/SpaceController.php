<?php
namespace App\AdminController;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Space;
use App\Model\SpaceModel;
use App\Form\AddSpaceFormType;
use Symfony\Component\HttpFoundation\Request;

class SpaceController extends AbstractController
{
    /**
     * @Route("/admin/space/listSpaces", name="listSpaces")
     */
    public function listSpaces(SpaceModel $spaceModel){
        $spaces = $spaceModel->showAllSpaces(); 
        // vykresleni sablony
        return $this->render('admin/space/listSpaces.html.twig', [
            'spaces' => $spaces,
        ]);
    }
    
    /**
     * @Route("/admin/space/addSpace", name="addSpace")
     */
    public function addSpace(SpaceModel $spaceModel, Request $request)
    {
        $space = new Space();
        $form = $this->createForm(AddSpaceFormType::class, $space);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $space->setState(1);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($space);
            $entityManager->flush();
            return $this->redirectToRoute('listSpaces');
        }
        
        return $this->render('admin/space/addSpace.html.twig', [
            'addSpaceForm' => $form->createView(),
        ]);
    }
    
    /**
     * @Route("/admin/space/editSpace/{spaceId}", name="editSpace")
     */
    public function editSpace(int $spaceId = null, SpaceModel $spaceModel, Request $request)
    {
        $space = $spaceModel->getSpaceById($spaceId);
        $form = $this->createForm(AddSpaceFormType::class, $space);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $space->setState(1);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($space);
            $entityManager->flush();
            return $this->redirectToRoute('listSpaces');
        }
        
        return $this->render('admin/space/editSpace.html.twig', [
            'editSpaceForm' => $form->createView(),
        ]);
    }
}
