<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\{Categoria};
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Form\CategoriaType;


use Omines\DataTablesBundle\Adapter\ArrayAdapter;
use Omines\DataTablesBundle\Column\TextColumn;
use Omines\DataTablesBundle\DataTableFactory;
use Omines\DataTablesBundle\Adapter\Doctrine\ORMAdapter;

class CategoriaController extends BaseController
{
   /**
     * @Route("/categories", name="llistar_categories")
     */
    public function llistar_categories(Request $request, DataTableFactory $dataTableFactory): Response
    {
        
        $categories = $this->getDoctrine()
            ->getRepository(Categoria::class)
            ->findAll();

        $table = $dataTableFactory->create()
        ->add('nom', TextColumn::class, ['label' => 'Nom'])
        
        
        /*->add('id', TextColumn::class, ['label' => 'id', 'render' => function($value, $context) {
                                        
           $action = "";
           $action = '
                        <div class="btn-group">
                            <a href="/vehicles/'.$value.'" class="badge badge-info p-1 m-2">Fitxa vehicle</a>
                            <a href="/vehicles/modificar/'.$value.'" class="badge badge-secondary p-1 m-2">Modificar vehicle</a>
                            <a href="/vehicles/esborrar/'.$value.'" class="badge badge-danger p-1 m-2">Esborrar vehicle</a> 
                        </div>';
           
            return $action;                   
        }])*/
        ->createAdapter(ORMAdapter::class, [
            'entity' => Categoria::class,
        ])
        ->handleRequest($request);

        if ($table->isCallback()) {
            return $table->getResponse();
        }

        return $this->render('categoria/index.html.twig', ['datatable' => $table]);

    }


    /**
     * @Route("/categories/afegir",name="afegir_categoria")
     */
    public function createCategoria(Request $request,ValidatorInterface $validator):Response
    {
        $entityManager = $this->obManager();

        $categoria = new Categoria();
       
        

        
        $form = $this->createForm(CategoriaType::class, $categoria);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
           
           $entityManager->persist($categoria);

           $entityManager->flush();
           
           return $this->redirectToRoute('llistar_categories');
       }

       return $this->render('categoria/afegir.html.twig', ['form' => $form->createView() ]);
       
    }


}
