<?php

namespace App\Controller;

use App\Entity\OrdreReparacio;
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
            ->add('id', TextColumn::class, ['label' => 'ID'])
            ->add('vehicle', TextColumn::class, ['label' => 'Vehicle', 'searchable' => True, 'field' => 'vehicle.Matricula'])
            ->add('client', TextColumn::class, ['label' => 'Client', 'searchable' => True, 'field' => 'vehicle.client'])
            ->add('pressupost', TextColumn::class, ['label' => 'Pressupost'])
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
     * @Route("ordres/afegir", name="afegir_ordre")
     */
    public function createOrdre(Request $request, ValidatorInterface $validator, UserInterface $user): Response
    {
        //ini_set( 'date.timezone', 'Europe/Berlin' ); 
        date_default_timezone_set('Europe/Madrid');
        $ordre = new OrdreReparacio();
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
}
