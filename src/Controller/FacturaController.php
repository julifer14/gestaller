<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\{Pressupost, Article, Factura, Empresa};


use Omines\DataTablesBundle\Column\TextColumn;
use Omines\DataTablesBundle\DataTableFactory;
use Omines\DataTablesBundle\Adapter\Doctrine\ORMAdapter;


class FacturaController extends AbstractController
{
    /**
     * @Route("/factures", name="llistar_factures")
     */
    public function llistar_factures(Request $request, DataTableFactory $dataTableFactory): Response
    {
        $table = $dataTableFactory->create()
            
            ->add('vehicle', TextColumn::class, ['label' => 'Vehicle', 'searchable' => True, 'field' => 'vehicle.Matricula'])
            ->add('client', TextColumn::class, ['label' => 'Client', 'searchable' => True, 'field' => 'vehicle.client'])
            ->add('treballador', TextColumn::class, ['label' => 'Treballador', 'field' => 'treballador.nom'])

            ->add('id', TextColumn::class, ['label' => '', 'searchable' => True, 'orderable' => True, 'render' => function ($value, $context) {


                $action = "";
                /*if ($value < 10) {
                    $action = '0';
                }
                $action = $action . $value . ' 
                        <div class="btn-group">
                            <a href="/pressupostos/' . $value . '" class="badge badge-secondary p-2 m-1">Veure pressupost</a>
                            <a href="/pressupostos/modificar/' . $value . '" class="badge badge-secondary p-2 m-1">Modificar pressupost</a>
                            <a href="/pressupostos/' . $value . '/pdf" class="badge badge-success p-2 m-1">Generar pdf</a>
                            <!--<a href="/pressupostos/' . $value . '/acceptat" class="badge badge-light p-2 m-1">✅</a>
                            <a href="/pressupostos/' . $value . '/rebutjat" class="badge badge-light p-2 m-1">❌</a>-->
                        </div>';
                if ($context->getEstat()) {
                    //Pressupost esta acceptat
                    // $action = $action . ' <a href="/pressupostos/' . $context . '/rebutjat" class="badge badge-danger p-2 m-1">Rebutjar pressupost</a>';
                    $action = $action . '<a href="/ordre/afegir/' . $context . '" class="badge badge-info p-2 m-1">Crear Ordre Reparació</a>';
                } else {
                    //Pressupost esta rebutjat
                    //$action = $action .  '<a href="/pressupostos/' . $context . '/acceptat" class="badge badge-success p-2 m-1">Acceptar pressupost</a>';
                }*/

                return $action;
            }])
            //->addOrderBy(4, 'desc')
            ->createAdapter(ORMAdapter::class, [
                'entity' => Factura::class,
            ])
            ->handleRequest($request);

        if ($table->isCallback()) {
            return $table->getResponse();
        }

        return $this->render('factura/index.html.twig', ['datatable' => $table]);
    }
}
