<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\{Agenda, Article, Factura, Empresa, OrdreReparacio};
use App\Form\AgendaType;

use Omines\DataTablesBundle\Column\{TextColumn, DateTimeColumn};
use Omines\DataTablesBundle\DataTableFactory;
use Omines\DataTablesBundle\Adapter\Doctrine\ORMAdapter;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Controller\BaseController;

use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints\DateTime;

class AgendaController extends BaseController
{
    /**
     * @Route("/agenda", name="agenda")
     */
    public function index(): Response
    {
        return $this->render('agenda/index.html.twig', [
            'controller_name' => 'AgendaController',
        ]);
    }

  

    /**
     * @Route("/agenda/events", name="llistar_events")
     */
    public function llistar_events(Request $request, DataTableFactory $dataTableFactory): Response
    {
        $table = $dataTableFactory->create()

            ->add('tasca', TextColumn::class, ['label' => 'Tasca', 'searchable' => True, 'field' => 'tasca.nom'])
            ->add('vehicle', TextColumn::class, ['label' => 'Vehicle', 'searchable' => True, 'field' => 'vehicle.Matricula'])
            ->add('dataHora', DateTimeColumn::class, ['label' => 'Data/hora', 'searchable' => True,])
            ->add('treballador', TextColumn::class, ['label' => 'Treballador', 'field' => 'treballador.nom'])
            ->add('estat', TextColumn::class, ["visible" => false, 'label' => 'Estat pagament', 'render' => function ($value, $context) {
                $action = "";
                if ($value == 0) {
                    $action = "<p class='text-danger'>Pendent</p>";
                } else if ($value == 1) {
                    $action = "<p class='text-success'>Completada</p>";
                } else {
                    $action = "<p class='text-secondary'>Anulada</p>";
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


                return $action;
            }])
            //->addOrderBy(5, 'desc')
            ->createAdapter(ORMAdapter::class, [
                'entity' => Agenda::class,
            ])
            ->handleRequest($request);

        if ($table->isCallback()) {
            return $table->getResponse();
        }

        return $this->render('agenda/llistar.html.twig', ['datatable' => $table]);
    }


    /**
     * @Route("/agenda/getEvents",name="get_events")
     */
    public function getEvents(Request $request, SerializerInterface $serializer): Response
    {
        /*$start = $request->query->get('start');
        dump($start);
        //$start = new DateTime($start);
        //$start = DateTime::createFromFormat(DateTime::ISO8601,date($start));
        date(DATE_ISO8601,$start);
        dump($start);
        $end = $request->query->get('end');
        dump($end);
        exit;*/


        $events = $this->getDoctrine()
            ->getRepository(Agenda::class)
            ->findAll();

        $arrayCollection = array();

        foreach ($events as $e) {
            $arrayCollection[] = array(
                'title' => $e->getVehicle()->getMatricula() . " - " . $e->getTasca()->getNom(),

                'start' => $e->getDataHora(),
            );
        }
        return  new JsonResponse($arrayCollection);
    }


    /**
     * @Route("/agenda/afegir",name="afegir_event")
     */
    public function createTasca(Request $request, ValidatorInterface $validator): Response
    {
        $agenda = new Agenda();

        $form = $this->createForm(AgendaType::class, $agenda);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->save($agenda);
            $this->addFlash('success', 'agenda.success-add');
            return $this->redirectToRoute('agenda');
        }

        return $this->render('agenda/afegir.html.twig', ['form' => $form->createView()]);
    }

      /**
     * @Route("/agenda/{id}",name="agenda_show")
     */
    public function show(Agenda $agenda):Response{
        return $this->render('agenda/fitxa_agenda.html.twig', [
            'controller_name' => 'AgendaController',
            'agenda' => $agenda,
        ]);
    }
}
