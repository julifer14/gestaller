<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\{Article};

use Omines\DataTablesBundle\Adapter\ArrayAdapter;
use Omines\DataTablesBundle\Column\TextColumn;
use Omines\DataTablesBundle\DataTableFactory;
use Omines\DataTablesBundle\Adapter\Doctrine\ORMAdapter;

class ArticleController extends BaseController
{
    /**
     * @Route("/articles", name="llistar_articles")
     */
    public function llistar_articles(Request $request, DataTableFactory $dataTableFactory): Response
    {
        
        $articles = $this->getDoctrine()
            ->getRepository(Article::class)
            ->findAll();

        $table = $dataTableFactory->create()
        ->add('nom', TextColumn::class, ['label' => 'Nom','searchable'=> True])
        ->add('descripcio', TextColumn::class, ['label' => 'Descripció'])
        ->add('preu', TextColumn::class, ['label' => 'Preu'])
        ->add('iva', TextColumn::class, ['label' => 'IVA'])
        ->add('stock', TextColumn::class, ['label' => 'Stock'])
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
            'entity' => Article::class,
        ])
        ->handleRequest($request);

        if ($table->isCallback()) {
            return $table->getResponse();
        }

        return $this->render('article/index.html.twig', ['datatable' => $table]);

    }



    /**
     * @Route("/articles", name="article")
     */
    public function index(): Response
    {
        return $this->render('article/index.html.twig', [
            'controller_name' => 'ArticleController',
        ]);
    }
}
