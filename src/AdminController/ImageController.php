<?php
namespace App\AdminController;

use App\Model\FileUploadModel;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Model\ImageModel;

class ImageController extends AbstractController
{
    #[Route("/admin/images", name: "images")]
    function multipleUploadImages(Request $request, FileUploadModel $fileUploadModel, ImageModel $imageModel){
        $form = $this->createFormBuilder()
            ->add('images', FileType::class, array(
                'multiple' => true,
                'attr' => array(
                    'multiple' => 'multiple'
                )
            ))
            ->add('submit', SubmitType::class, ['label' => 'Odeslat obrázky'])
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $pictures = $form->getData();
            $picturesFileName = [];
            foreach ($pictures['images'] as $picture) {
                $picturesFileName[] = $fileUploadModel->saveImage($picture, 'GALLERY');
            }
            //dd($picturesFileName);
            $imageModel->resizeImages($picturesFileName ,300, 300);
        }
        return $this->render('admin/image/show.html.twig', [
            'form' => $form->createView(),
        ]);

        /* TODO:
         *
         * Rozpracované obrázky. Zatím se uloží a zmenší. Je potřeba dodělat ukládání do databáze. DB je na to již připravená (imageEntity).
         * */
    }
}