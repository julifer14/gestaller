<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\{Agenda, User, Factura, Empresa, OrdreReparacio};
use App\Form\AgendaType;

use Omines\DataTablesBundle\Column\{TextColumn, DateTimeColumn};
use Omines\DataTablesBundle\DataTableFactory;
use Omines\DataTablesBundle\Adapter\Doctrine\ORMAdapter;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Controller\BaseController;
use Symfony\Component\Security\Core\User\UserInterface;


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
     * @Route("agenda/event/modificar/{id}", name="modificar_event")
     */
    public function modificarEvent(Request $request, Agenda $agenda): Response
    {


        $form = $this->createForm(AgendaType::class, $agenda);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->addFlash('success', 'agenda.success-edit');
            $this->save($agenda);


            return $this->redirectToRoute('agenda',['id'=>$agenda->getTreballador()->getId()]);
        }

        return $this->render('agenda/modificar_agenda.html.twig', ['form' => $form->createView(), 'agenda' => $agenda]);
    }

    /**
     * @Route("/agenda/event/esborrar/{id}",name="esborrar_event")
     */
    public function esborrarEvent(Request $request, Agenda $agenda):Response{
        $agendaUsuari = $agenda->getTreballador();
        $resultat = $this->esborrar($agenda);


        if (!$resultat) {
            $this->addFlash('error', 'agenda.error-del');
        }
        $this->addFlash('success', 'agenda.success-del');
        return $this->redirectToRoute('agenda',['id'=>$agendaUsuari->getId()]);
    }



    /**
     * @Route("/agenda/events", name="llistar_events")
     */
    public function llistar_events(Request $request, DataTableFactory $dataTableFactory): Response
    {
        $table = $dataTableFactory->create()

            ->add('feina', TextColumn::class, ['label' => 'Tasca',  ])
            ->add('vehicle', TextColumn::class, ['label' => 'Vehicle', 'searchable' => True, 'field' => 'vehicle.Matricula'])
            ->add('dataHoraInici', DateTimeColumn::class, ['label' => 'Data/hora', ])
            ->add('dataHoraFi', DateTimeColumn::class, ['label' => 'Data/hora',])
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
                            <a href="/agenda/events/' . $value . '" class="badge badge-secondary p-2 m-1">Veure event</a>
                            <a href="/agenda/event/modificar/' . $value . '" class="badge badge-secondary p-2 m-1">Modificar event</a>
                            <a href="/agenda/event/esborrar/' . $value . '/pdf" class="badge badge-danger p-2 m-1">Eliminar event</a>
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
     * @Route("/agenda/afegir",name="afegir_event")
     */
    public function createTasca(Request $request, ValidatorInterface $validator): Response
    {

        $usuari_id = $request->query->get('usuari');
        $usuari = $this->getDoctrine()->getRepository(User::class)->find($usuari_id);
        $agenda = new Agenda();
        $agenda->setTreballador($usuari);

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
     * @Route("/agenda/{id}", defaults={"id"=-1}, name="agenda")
     * @Route("/",  name="agenda_home")
     */
    public function index(Request $request, User $user = null, UserInterface $userActual): Response
    {
        $routeName = $request->attributes->get('_route');

        if($routeName == "agenda_home"){
                $user = $userActual;
        }

        if ($user) {
            return $this->render('agenda/agenda.html.twig', [
                'controller_name' => 'AgendaController',
                'user' => $user
            ]);
        } else {
            
            $usuaris = $this->getDoctrine()
                ->getRepository(User::class)
                ->findAll();
            return $this->render('agenda/index.html.twig', [
                'usuaris' => $usuaris
            ]);
        }
    }

    /**
     * @Route("/agenda/event/{id}",name="agenda_show")
     */
    public function show(Agenda $agenda): Response
    {
        return $this->render('agenda/fitxa_agenda.html.twig', [
            'controller_name' => 'AgendaController',
            'agenda' => $agenda,
        ]);
    }
}
