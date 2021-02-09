<?php

namespace App\Controller;

use App\Entity\{OrdreReparacio, Pressupost};
use App\Form\OrdreReparacioType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;

use Omines\DataTablesBundle\Adapter\ArrayAdapter;
use Omines\DataTablesBundle\Column\TextColumn;
use Omines\DataTablesBundle\DataTableFactory;
use Omines\DataTablesBundle\Adapter\Doctrine\ORMAdapter;
use Symfony\Component\Security\Core\User\UserInterface;

class OrdreReparacioController extends BaseController
{
    /**
     * @Route("/ordres", name="llistar_ordres")
     */
    public function llistar_ordres(Request $request, DataTableFactory $dataTableFactory): Response
    {
        $ordres = $this->getDoctrine()->getRepository(OrdreReparacio::class)->findAll();
        $table = $dataTableFactory->create()

            ->add('vehicle', TextColumn::class, ['label' => 'Vehicle', 'searchable' => True, 'field' => 'vehicle.Matricula'])
            ->add('client', TextColumn::class, ['label' => 'Client', 'searchable' => True, 'field' => 'vehicle.client'])
            ->add('pressupost', TextColumn::class, ['label' => 'Pressupost'])
            ->createAdapter(ORMAdapter::class, [
                'entity' => OrdreReparacio::class,
            ])
            ->add('id', TextColumn::class, ['label' => 'ID'])
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
    }

    /**
     * @Route("ordres/{id}", name="ordre_show")
     */
    public function show(OrdreReparacio $ordre): Response
    {
        return $this->render('ordre_reparacio/fitxa_ordre.html.twig', [
            'controller_name' => 'OrdreReparacioController',
            'ordre' => $ordre,
        ]);
    }
}
