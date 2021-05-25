<?php

namespace App\Controller;

use App\Entity\{OrdreReparacio, Pressupost, Empresa};
use App\Form\OrdreReparacioType;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;

use Omines\DataTablesBundle\Adapter\ArrayAdapter;
use Omines\DataTablesBundle\Column\{BoolColumn, TextColumn, DateTimeColumn};
use Omines\DataTablesBundle\DataTableFactory;
use Omines\DataTablesBundle\Adapter\Doctrine\ORMAdapter;
use Symfony\Component\Security\Core\User\UserInterface;

use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use Knp\Snappy\Pdf;

class OrdreReparacioController extends BaseController
{
    /**
     * @Route("/ordres", name="llistar_ordres")
     */
    public function llistar_ordres(Request $request, DataTableFactory $dataTableFactory): Response
    {
        $ordres = $this->getDoctrine()->getRepository(OrdreReparacio::class)->findAll();
        $table = $dataTableFactory->create()

            ->add('vehicle', TextColumn::class, ['label' => 'Vehicle', /*'searchable' => True,*/ 'field' => 'vehicle.Matricula'])
            ->add('client', TextColumn::class, ['label' => 'Client',/* 'searchable' => True,*/ 'field' => 'vehicle.client'])
            /* ->add('pressupost', TextColumn::class, ['label' => 'Pressupost', 'field' => 'pressupost.id', 'render' => function ($value, $context) {

                if ($value) {
                    return $value . 'patata';
                } else {
                    return '';
                }

                if(is_null($context->getPressupost())) return 'sense res';
                return $context->getPressupost()->__toString()."patata";
            }])*/

            ->add('dataEntrada', DateTimeColumn::class, ['label' => 'Data d\'entrada', 'format' => 'd-m-Y'])
            ->add('estat', BoolColumn::class, ['label' => 'Tancada?', 'render' => function ($value, $context) {
                $action = "";
                if ($value == "true") {
                    $action = "<span class='text-success text-center'>SI</span>";
                } else {
                    $action = "<span class='text-danger text-center'>NO</span>";
                }
                return $action;
            }])

            ->add('autoritzacio', BoolColumn::class, ['label' => 'Autoritzada?', 'render' => function ($value, $context) {
                $action = "";
                if ($value == "true") {
                    $action = "<span class='text-success text-center'>SI</span>";
                } else {
                    $action = "<span class='text-danger text-center'>NO</span>";
                }
                return $action;
            }])
            ->add('id', TextColumn::class, ['label' => '', 'searchable' => True, 'orderable' => True, 'render' => function ($value, $context) {


                $action = "";
                
                $action = $action . ' 
                        <div class="btn-group">
                            <a href="/ordres/' . $value . '" class="badge badge-info p-2 m-1">Veure ordre</a>
                            <a href="/ordres/modificar/' . $value . '" class="badge badge-secondary p-2 m-1">Modificar ordre</a>
                            <a href="/ordres/' . $value . '/pdf" class="badge badge-success p-2 m-1">Generar pdf</a>
                            
                        </div>';
                if ($context->esFacturable()) {
                    //Pressupost esta acceptat
                    // $action = $action . ' <a href="/pressupostos/' . $context . '/rebutjat" class="badge badge-danger p-2 m-1">Rebutjar pressupost</a>';
                    $action = $action . '<a href="/factures/afegir/' . $context->getId() . '" class="badge badge-warning p-2 m-1">Crear factura</a>';
                } else {
                    //Pressupost esta rebutjat
                    //$action = $action .  '<a href="/pressupostos/' . $context . '/acceptat" class="badge badge-success p-2 m-1">Acceptar pressupost</a>';
                }
                if ($context->getFactura()) {

                    $action = $action . '<a href="/factures/' . $context->getFactura()->getId() . '" class="badge badge-primary p-2 m-1">Veure factura</a>';
                }

                return $action;
            }])
            ->addOrderBy(5, 'desc')
            ->createAdapter(ORMAdapter::class, [
                'entity' => OrdreReparacio::class,
            ])
            ->handleRequest($request);

        if ($table->isCallback()) {
            return $table->getResponse();
        }

        return $this->render('ordre_reparacio/index.html.twig', [
            'datatable' => $table,
        ]);
    }


    /**
     * @Route("ordres/afegir/{pressupost}",defaults={"pressupost"=-1}, name="afegir_ordre")
     */
    public function createOrdre(Request $request, ValidatorInterface $validator, UserInterface $user, Pressupost $pressupost = null): Response
    {
        if (is_null($pressupost) || !$pressupost->getOrdreReparacio()) {

            //dump($pressupost);
            //exit;
            //ini_set( 'date.timezone', 'Europe/Madrid' ); 
            date_default_timezone_set("Europe/Madrid");
            $ordre = new OrdreReparacio();
            if ($pressupost) {
                $ordre->setTasca($pressupost->getTasca());
                $ordre->setPressupost($pressupost);
                $ordre->setVehicle($pressupost->getVehicle());
            }
            $date = new \DateTime('@' . strtotime('now'));
            $ordre->setAny($date->format('Y'));
            $ordre->setDataCreacio($date);
            $ordre->setDataEntrada($date);
            $ordre->setTreballador($user);
            $form = $this->createForm(OrdreReparacioType::class, $ordre);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $this->addFlash('success', 'ordre.success-add');
                $this->save($ordre);

                return $this->redirectToRoute('llistar_ordres');
            }

            return $this->render('ordre_reparacio/afegir.html.twig', ['form' => $form->createView()]);
        } else {
            $this->addFlash('danger', 'ordre.error-add');

            return $this->redirectToRoute("llistar_pressupostos");
        }
    }

    /**
     * @Route("ordres/{id}", name="ordre_show")
     */
    public function show(OrdreReparacio $ordre): Response
    {
        $empresa = $this->getDoctrine()
            ->getRepository(Empresa::class)
            ->findOneBy(['id' => 1]);

        return $this->render('ordre_reparacio/fitxa_ordre.html.twig', [
            'controller_name' => 'OrdreReparacioController',
            'ordre' => $ordre,
            'empresa' => $empresa
        ]);
    }

    /**
     * @Route("ordres/{id}/acceptar",name="acceptar_ordre")
     */
    public function acceptarOrdre(Request $request, OrdreReparacio $ordre): Response
    {
        if ($ordre->getEstat()) {
            $this->addFlash('danger', 'ordre.tancada');

            return $this->redirectToRoute('llistar_ordres');
        }
        $ordre->setEstat(1);
        $ordre->setAutoritzacio(1);
        $this->addFlash('success', 'ordre.canviEstatOK');
        $this->save($ordre);

        return $this->redirectToRoute('llistar_ordres');
    }

    /**
     * @Route("ordres/{id}/rebutjar",name="rebutjar_ordre")
     */
    public function rebutjarOrdre(Request $request, OrdreReparacio $ordre): Response
    {
        if ($ordre->getEstat()) {
            $this->addFlash('danger', 'ordre.tancada');

            return $this->redirectToRoute('llistar_ordres');
        }
        $ordre->setEstat(1);
        $ordre->setAutoritzacio(0);
        $this->addFlash('success', 'ordre.canviEstatKO');
        $this->save($ordre);

        return $this->redirectToRoute('llistar_ordres');
    }

    /**
     * @Route("ordres/modificar/{id}", name="modificar_ordre")
     */
    public function modificarOrdre(Request $request, OrdreReparacio $ordre): Response
    {
        if ($ordre->getEstat()) {
            $this->addFlash('danger', 'ordre.tancada');

            return $this->redirectToRoute('llistar_ordres');
        }
        $form = $this->createForm(OrdreReparacioType::class, $ordre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->addFlash('success', 'ordre.success-edit');
            $this->save($ordre);

            return $this->redirectToRoute('llistar_ordres');
        }

        return $this->render('ordre_reparacio/modificar_ordre.html.twig', ['form' => $form->createView(), 'ordre' => $ordre]);
    }

    /**
     * @Route("ordres/{id}/pdf", name="ordres_pdf")
     */
    public function pdfAction(Pdf $pdf, OrdreReparacio $ordre)
    {
        $empresa = $this->getDoctrine()
            ->getRepository(Empresa::class)
            ->findOneBy(['id' => 1]);

        $html = $this->renderView('ordre_reparacio/fitxa_ordre_pdf.html.twig', [
            'controller_name' => 'OrdreReparacuoController',
            'ordre' => $ordre,
            'empresa' => $empresa,
        ]);


        $nomFitxer = "ordre_reparacio" . $ordre->getId() . ".pdf";
        return new PdfResponse(
            $pdf->getOutputFromHtml($html),
            $nomFitxer
        );
    }
}
