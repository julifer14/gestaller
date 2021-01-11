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
     * @Route("/empresa", name="empresa")
     */
    public function empresa(Request $request, ValidatorInterface $validator): Response
    {

        $empresa = $this->getDoctrine()
            ->getRepository(Empresa::class)
            ->findOneBy(['id'=>1]);


        if (!$empresa) {
            $empresa = new Empresa();
            // return $this->redirectToRoute('homepage');
        }

        $entityManager = $this->obManager();

        $form = $this->createForm(EmpresaType::class, $empresa);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            $this->addFlash('success', 'empresa.success-add');
            $this->save($empresa);
            return $this->redirectToRoute('homepage');
        }

        return $this->render('empresa/empresa.html.twig', ['form' => $form->createView(),'logo'=>$empresa->getLogo()]);
    }
}
