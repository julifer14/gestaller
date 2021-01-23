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
        
        
        ->add('id', TextColumn::class, ['label' => 'Id  Accions', 'render' => function($value, $context) {
                                        
           $action = "";
           $action = '
           <td>'.$value.'</td>
                        <div class="btn-group">
                            <a role="button" href="/categories/'.$value.'" class="disabled btn btn-info p-1 m-2">Fitxa categoria</a>
                            <a role="button" href="/categories/modificar/'.$value.'" class="btn btn-secondary p-1 m-2">Modificar categoria</a>
                            <a role="button" href="/categories/esborrar/'.$value.'" class="disabled btn btn-danger p-1 m-2">Esborrar categoria</a> 
                        </div>';
           
            return $action;                   
        }])
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
       * @Route("categories/modificar/{id}", name="modificar_categoria")
       */
      public function modificarCategoria(Request $request,Categoria $categoria):Response
      {

         
         $form = $this->createForm(CategoriaType::class, $categoria);
         $form->handleRequest($request);

         if ($form->isSubmitted() && $form->isValid()) {
            
            $this->addFlash('success', 'categoria.success-edit');
            $this->save($categoria);
            
            return $this->redirectToRoute('llistar_categories');
        }

        return $this->render('categoria/modificar_categoria.html.twig', ['form' => $form->createView(),'categoria'=>$categoria ]);

    }


    /**
     * @Route("/categories/afegir",name="afegir_categoria")
     */
    public function createCategoria(Request $request,ValidatorInterface $validator):Response
    {

        $categoria = new Categoria();
       
        

        
        $form = $this->createForm(CategoriaType::class, $categoria);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
           
            $this->addFlash('success', 'categoria.success-add');
            $this->save($categoria);
           
           return $this->redirectToRoute('llistar_categories');
       }

       return $this->render('categoria/afegir.html.twig', ['form' => $form->createView() ]);
       
    }


}
