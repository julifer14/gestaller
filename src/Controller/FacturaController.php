<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\{Pressupost, Article, Factura, Empresa, OrdreReparacio};
use App\Manager\FacturaManager;
use App\Form\{FacturaType, LiniaFacturaType};


use Omines\DataTablesBundle\Column\TextColumn;
use Omines\DataTablesBundle\DataTableFactory;
use Omines\DataTablesBundle\Adapter\Doctrine\ORMAdapter;


class FacturaController extends BaseController
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

    /**
     * @Route("/factures/afegirLinia",name="afegir_linia_factura")
     */
    public function createLinia(Request $request): Response
    {

        $id_factura = $request->request->get('id_factura');
        $total_linia = $request->request->get('total_linia');
        $factura = $this->getDoctrine()->getRepository(Factura::class)->findOneBy(['id' => $id_factura]);
        //Comprovacions de pressupost correcte

        $articles =  $this->getDoctrine()->getRepository(Article::class)->findAll();
        return $this->render('factura/parcial/formLinia.html.twig', ['total_linia' => $total_linia, 'articles' => $articles]);
    }


    /**
     * @Route("/factures/afegir/{ordre}",name="afegir_factura")
     */
    public function createFactura(Request $request, FacturaManager $facturaManager,OrdreReparacio $ordre): Response
    {
        $entityManager = $this->obManager();
        $articles = $this->getDoctrine()
            ->getRepository(Article::class)
            ->findAll();

        $factura = new Factura();


        //Treure!

        $date = new \DateTime('@' . strtotime('now'));
        $factura->setAny($date->format('Y'));
        $factura->setData($date);
        $factura->setTotal(0);
        $factura->setOrdre($ordre);

        $form = $this->createForm(FacturaType::class, $factura);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $noves_linies = $request->request->get('new_linia');
            $facturaManager->saveFactura($factura, $noves_linies);
            $this->addFlash('success', 'factura.success-add');

            return $this->redirectToRoute('llistar_factures', array("id" => $factura->getId()));
        }

        return $this->render('factura/afegir.html.twig', ['form' => $form->createView(), 'articles' => $articles]);
    }


}
