<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\{Marca,Model};
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Form\MarcaType;


use Omines\DataTablesBundle\Adapter\ArrayAdapter;
use Omines\DataTablesBundle\Column\TextColumn;
use Omines\DataTablesBundle\DataTableFactory;
use Omines\DataTablesBundle\Adapter\Doctrine\ORMAdapter;

class MarcaController extends BaseController
{
    /**
     * @Route("/marques", name="llistar_marques")
     */
    public function llistar_marques(Request $request, DataTableFactory $dataTableFactory): Response
    {
        $marques = $this->getDoctrine()
            ->getRepository(Marca::class)
            ->findAll();

            $table = $dataTableFactory->create()
            ->add('nom', TextColumn::class, ['label' => 'Nom','searchable'=> True])            
            ->add('id', TextColumn::class, ['label' => '', 'render' => function($value, $context) {
                                            
               $action = "";
               $action = '
                            <div class="btn-group">
                                <a href="/marques/modificar/'.$value.'" class="badge badge-secondary p-2 m-1">Modificar marca</a>
                               <!-- <a href="/marques/esborrar/'.$value.'" class="badge badge-danger p-2 m-1">Esborrar marca</a> -->
                            </div>';
               
                return $action;                   
            }])
            ->createAdapter(ORMAdapter::class, [
                'entity' => Marca::class,
            ])
            ->handleRequest($request);
    
            if ($table->isCallback()) {
                return $table->getResponse();
            }
    
            return $this->render('marca/index.html.twig', ['datatable' => $table]);
    }



/**
     * @Route("/marques/afegir",name="afegir_marca")
     */
    public function createMarca(Request $request,ValidatorInterface $validator):Response
    {
        $entityManager = $this->obManager();

        $marca = new Marca();
       
        
        
        $form = $this->createForm(MarcaType::class, $marca);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
           
           $entityManager->persist($marca);

           $entityManager->flush();
           
           return $this->redirectToRoute('llistar_marques');
       }

       return $this->render('marca/afegir.html.twig', ['form' => $form->createView() ]);
       
    }


    /**
       * @Route("marques/modificar/{id}", name="modificar_marca")
       */
      public function modificarMarca(Request $request,Marca $marca):Response
      {
        $entityManager = $this->obManager();

         
         $form = $this->createForm(MarcaType::class, $marca);
         $form->handleRequest($request);

         if ($form->isSubmitted() && $form->isValid()) {
            
            $entityManager->persist($marca);

            $entityManager->flush();
            
            return $this->redirectToRoute('llistar_marques');
        }

        return $this->render('marca/modificar_marca.html.twig', ['form' => $form->createView(),'marca'=>$marca ]);

    }

}
