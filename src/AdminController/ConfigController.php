<?php
namespace App\AdminController;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Model\ConfigModel;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;

class ConfigController extends AbstractController
{
    #[Route("/admin/config/listConfig", name: "listConfig")]
    public function configList(ConfigModel $configModel, Request $request) {
        $configData = $configModel->showConfig();

        
        $form = $this->createFormBuilder();
        foreach($configData as $row){
            switch($row->getType()){
                case "int":
                    $type = NumberType::class;
                    $valueRetype = (int)$row->getValue();
                    break;
                case "boolean":
                    $type = CheckboxType::class;
                    if($row->getValue()){
                        $valueRetype = true;
                    }else{
                        $valueRetype = false;
                    }                    
                    break;
                default:
                    $type = TextType::class;
                    $valueRetype = $row->getValue();
            }
            
            $form->add($row->getTextId(), $type, array(
                'label' => $row->getName(),
                'data' => $valueRetype,
                'required' => false,                
            ));
        }
        
        $form->add('save', SubmitType::class, ['label' => 'Ulož změny']);
        
        $form = $form->getForm();
        
        //asi odchyd POSTu
        $form->handleRequest($request);
        
        //formular odeslan
        if ($form->isSubmitted() && $form->isValid()) {
            $configData = $form->getData();
            $configModel->saveConfig($configData);
        }

        //vykresleni sablony s formularem
        return $this->render('admin/config/listConfig.html.twig', [
            //'configData' => $configData,
            'form' => $form->createView(),
        ]);
    }
}