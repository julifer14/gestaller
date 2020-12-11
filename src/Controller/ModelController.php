<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\{Marca,Model};
use Symfony\Component\HttpFoundation\Request;

use Omines\DataTablesBundle\Adapter\ArrayAdapter;
use Omines\DataTablesBundle\Column\TextColumn;
use Omines\DataTablesBundle\DataTableFactory;
use Omines\DataTablesBundle\Adapter\Doctrine\ORMAdapter;

class ModelController extends BaseController
{
    /**
     * @Route("/models", name="llistar_models")
     */
    public function llistar_models(Request $request, DataTableFactory $dataTableFactory): Response
    {
        $models = $this->getDoctrine()
            ->getRepository(Model::class)
            ->findAll();

            $table = $dataTableFactory->create()
            ->add('Marca', TextColumn::class, ['label' => 'Marca','searchable'=> True,'field'=>'Marca.nom']) 
            ->add('nom', TextColumn::class, ['label' => 'Model','searchable'=> True])            
            ->add('id', TextColumn::class, ['label' => '', 'render' => function($value, $context) {
                                            
               $action = "";
               $action = '
                            <div class="btn-group">
                                <a href="/models/modificar/'.$value.'" class="badge badge-secondary p-2 m-1">Modificar model</a>
                               <!-- <a href="/models/esborrar/'.$value.'" class="badge badge-danger p-2 m-1">Esborrar model</a> -->
                            </div>';
               
                return $action;                   
            }])
            ->createAdapter(ORMAdapter::class, [
                'entity' => Model::class,
            ])
            ->handleRequest($request);
    
            if ($table->isCallback()) {
                return $table->getResponse();
            }
    
            return $this->render('model/index.html.twig', ['datatable' => $table]);
    }

}
