<?php

namespace App\Controller;

use App\Entity\Empresa;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Form\{EmpresaType};

class EmpresaController extends BaseController
{
    
    /**
     * @Route("/empresa/add", name="add_empresa")
     */
    public function create_empresa(Request $request, ValidatorInterface $validator):Response{

        $empreses = $this->getDoctrine()
            ->getRepository(Empresa::class)
            ->findAll();
        $size = count($empreses);
        
        if($size>=1){
            $this->addFlash('danger', 'empresa.error-add');

            return $this->redirectToRoute('empresa');
        }

        $entityManager = $this->obManager();
        $empresa = new Empresa();

        $form = $this->createForm(EmpresaType::class, $empresa);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            
            $this->addFlash('success', 'empresa.success-add');
            $this->save($empresa);
            return $this->redirectToRoute('empresa');
        }

        return $this->render('empresa/afegir.html.twig', ['form' => $form->createView()]);


    }

    /**
     * @Route("/empresa", name="empresa")
     */
    public function index(): Response
    {
        $empresa = $this->getDoctrine()->getRepository(Empresa::class)->findOneBy(['id' => 0]);
        dump($empresa);
        return $this->render('empresa/index.html.twig', [
            'controller_name' => 'EmpresaController',
        ]);
    }
}
