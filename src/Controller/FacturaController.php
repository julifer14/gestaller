<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\{Pressupost, Article, Factura, Empresa, OrdreReparacio};
use App\Manager\FacturaManager;
use App\Form\{FacturaType, LiniaFacturaType};
use Symfony\Component\Security\Core\User\UserInterface;

use Omines\DataTablesBundle\Column\TextColumn;
use Omines\DataTablesBundle\DataTableFactory;
use Omines\DataTablesBundle\Adapter\Doctrine\ORMAdapter;

use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use Knp\Snappy\Pdf;
use Symfony\Component\HttpFoundation\JsonResponse;

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
            ->add('ordre', TextColumn::class, ['label' => 'Ordre de reparació', 'searchable' => True, 'field' => 'ordre.id'])
            ->add('estat', TextColumn::class, ["visible"=>false,'label' => 'Estat pagament', 'render' => function ($value, $context) {
                $action = "";
                if ($value == 0) {
                    $action = "<p class='text-danger'>No pagada</p>";
                } else {
                    $action = "<p class='text-success'>Pagada</p>";
                }

                return $action;
            }])
            ->add('id', TextColumn::class, ['label' => '', 'searchable' => True, 'orderable' => True, 'render' => function ($value, $context) {


                $action = "";
                if ($value < 10) {
                    $action = '0';
                }
                $action = $action . $value . ' 
                        <div class="btn-group">
                            <a href="/factures/' . $value . '" class="badge badge-secondary p-2 m-1">Veure factura</a>
                            <a href="/factures/modificar/' . $value . '" class="badge badge-secondary p-2 m-1">Modificar factura</a>
                            <a href="/factures/' . $value . '/pdf" class="badge badge-success p-2 m-1">Generar pdf</a>
                        </div>';
                if (!$context->getEstat()) {
                    $action = $action . '<a  data-toggle="modal" data-target="#modalPagar"   data-id-factura="' . $value . '" class="botoModelPagar badge badge-info p-2 m-1">Pagar</a>';
                } 

                return $action;
            }])
            ->addOrderBy(5, 'desc')
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
     * @Route("factures/{id}/pagar", name="pagar_factura")
     */
    public function pagarFactura(Request $request, Factura $factura): Response
    {
        //$id_factura = $request->request->get('id');
        $tipus_pagament = $request->request->get('tipusPagament');

        //$factura = $this->getDoctrine()->getRepository(Factura::class)->findOneBy(['id' => $id_factura]);
        if ($factura->getEstat()==0 && !is_null($tipus_pagament)) {
            $factura->pagar($tipus_pagament);
            $this->save($factura);
            return new Response("ok", Response::HTTP_OK);
        }
        return new Response("ko", Response::HTTP_FORBIDDEN);
        
    }


    /**
     * @Route("/factures/afegir/{ordre}",name="afegir_factura")
     */
    public function createFactura(Request $request, FacturaManager $facturaManager,  UserInterface $user, OrdreReparacio $ordre): Response
    {
        if ($ordre->esFacturable()) {
            $entityManager = $this->obManager();
            $articles = $this->getDoctrine()
                ->getRepository(Article::class)
                ->findAll();

            $factura = new Factura();



            $date = new \DateTime('@' . strtotime('now'));
            $factura->setAny($date->format('Y'));
            $factura->setData($date);
            $factura->setTotal(0);
            $factura->setTreballador($user);
            $factura->setOrdre($ordre);
            $factura->setTasca($ordre->getTasca());
            $factura->setQuilometres($ordre->getQuilometres());
            $factura->setVehicle($ordre->getVehicle());
            $ordre->setFactura($factura);


            $form = $this->createForm(FacturaType::class, $factura);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

                $noves_linies = $request->request->get('new_linia');
                $facturaManager->saveFactura($factura, $noves_linies);
                $this->save($ordre);
                $this->addFlash('success', 'factura.success-add');

                return $this->redirectToRoute('llistar_factures', array("id" => $factura->getId()));
            }

            return $this->render('factura/afegir.html.twig', ['form' => $form->createView(), 'articles' => $articles]);
        } else {
            return $this->redirectToRoute('llistar_factures');
        }
    }

    /**
     * @Route("factures/{id}", name="factura_show")
     */
    public function show(Factura $factura): Response
    {
        $empresa = $this->getDoctrine()
            ->getRepository(Empresa::class)
            ->findOneBy(['id' => 1]);

        return $this->render('factura/fitxa_factura.html.twig', [
            'factura' => $factura,
            'empresa' => $empresa
        ]);
    }


    /**
     * @Route("factures/{id}/pdf", name="factura_pdf")
     */
    public function pdfAction(Pdf $pdf, Factura $factura)
    {
        $empresa = $this->getDoctrine()
            ->getRepository(Empresa::class)
            ->findOneBy(['id' => 1]);

        $html = $this->renderView('factura/fitxa_factura_pdf.html.twig', [
            'controller_name' => 'PressupostController',
            'factura' => $factura,
            'empresa' => $empresa,
        ]);
        $nomFitxer = "factura" . $factura->getId() . ".pdf";
        return new PdfResponse(
            $pdf->getOutputFromHtml($html),
            $nomFitxer
        );
    }

    /**
     * @Route("factures/modificar/{id}", name="modificar_factura")
     */
    public function modificarFactura(Request $request, Factura $factura, FacturaManager $facturaManager): Response
    {

        $form = $this->createForm(FacturaType::class, $factura);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $noves_linies = $request->request->get('new_linia');
            $facturaManager->saveFactura($factura, $noves_linies);
            $this->addFlash('success', 'factura.success-edit');
            return $this->redirectToRoute('llistar_factures');
        }

        return $this->render('factura/modificar_factura.html.twig', ['form' => $form->createView(), 'factura' => $factura]);
    }
}
