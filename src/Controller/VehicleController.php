<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\{Vehicle,Client};
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Form\VehicleType;

use Omines\DataTablesBundle\Adapter\ArrayAdapter;
use Omines\DataTablesBundle\Column\TextColumn;
use Omines\DataTablesBundle\DataTableFactory;
use Omines\DataTablesBundle\Adapter\Doctrine\ORMAdapter;


class VehicleController extends BaseController
{
    /**
     * @Route("/vehicles", name="llistar_vehicles")
     */
    public function llistar_vehicles(Request $request, DataTableFactory $dataTableFactory): Response
    {
        
        $vehicle = $this->getDoctrine()
            ->getRepository(Vehicle::class)
            ->findAll();

        $table = $dataTableFactory->create()
        ->add('Matricula', TextColumn::class, ['label' => 'Matricula','searchable'=> True])
        ->add('Marca', TextColumn::class, ['label' => 'Marca'])
        ->add('Model', TextColumn::class, ['label' => 'Model'])
        ->add('Kilometres', TextColumn::class, ['label' => 'Quilòmetres'])
        ->add('client', TextColumn::class, ['label' => 'Client ID','field'=>'client.id'])
        ->add('id', TextColumn::class, ['label' => 'id', 'render' => function($value, $context) {
                                        
           $action = "";
           $action = '
                        <div class="btn-group">
                            <a href="/vehicles/'.$value.'" class="badge badge-info p-1 m-2">Fitxa vehicle</a>
                            <a href="/vehicles/modificar/'.$value.'" class="badge badge-secondary p-1 m-2">Modificar vehicle</a>
                            <a href="/vehicles/esborrar/'.$value.'" class="badge badge-danger p-1 m-2">Esborrar vehicle</a> 
                        </div>';
           
            return $action;                   
        }])
        ->createAdapter(ORMAdapter::class, [
            'entity' => Vehicle::class,
        ])
        ->handleRequest($request);

        if ($table->isCallback()) {
            return $table->getResponse();
        }

        return $this->render('vehicle/index.html.twig', ['datatable' => $table]);

    }


    /**
     * @Route("/vehicles/afegir",name="afegir_vehicle")
     */
    public function createVehicle(Request $request,ValidatorInterface $validator):Response
    {
        $entityManager = $this->obManager();

        $vehicle = new Vehicle();
       
        $clients = $this->getDoctrine()
            ->getRepository(Client::class)
            ->findAll();

       //print_r($clients);
        
        $form = $this->createForm(VehicleType::class, $vehicle, array('clients' => $clients) );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
           
           $entityManager->persist($vehicle);

           $entityManager->flush();
           
           return $this->redirectToRoute('llistar_vehicles');
       }

       return $this->render('vehicle/afegir.html.twig', ['form' => $form->createView() ]);
       
    }

    /**
     * @Route("/vehicles/esborrar/{id}", name="esborrar_vehicle")
     */
    public function esborrar_vehicle(Vehicle $vehicle):Response
    {
        $resultat = $this->esborrar($vehicle);

        if(!$resultat){
            // fflash
        }
        return $this->redirectToRoute('llistar_vehicles');
    }
}
