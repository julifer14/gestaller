<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\{Vehicle,Client, Model};
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Form\VehicleType;

use Omines\DataTablesBundle\Adapter\ArrayAdapter;
use Omines\DataTablesBundle\Column\TextColumn;
use Omines\DataTablesBundle\DataTableFactory;
use Omines\DataTablesBundle\Adapter\Doctrine\ORMAdapter;
use Doctrine\ORM\QueryBuilder;

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
        ->add('Marca', TextColumn::class, ['label' => 'Marca','field'=>'marca.nom'])
        ->add('Model', TextColumn::class, ['label' => 'Model','field'=>'model.nom'])
        ->add('Kilometres', TextColumn::class, ['label' => 'Quilòmetres', 'render' => function($value, $context) {
            return $value. " kms";
        }])

        ->add('client', TextColumn::class, ['label' => 'Client', 'field'=>'client.cognoms', 'render' => function($value, $context) {
            if(empty($context->getClient())) return '';
            return $context->getClient()->__toString();
        }])

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
            'query' => function (QueryBuilder $builder){
                $builder
                    ->select('vehicle', 'marca', 'model','client')
                    ->from(Vehicle::class, 'vehicle')
                    ->leftJoin('vehicle.Model','model')
                    ->leftJoin('model.Marca', 'marca')
                    ->leftJoin('vehicle.client', 'client')
                    ->orderBy('vehicle.Matricula')
                ;
            }
        ])
        ->handleRequest($request);

        if ($table->isCallback()) {
            return $table->getResponse();
        }

        return $this->render('vehicle/index.html.twig', ['datatable' => $table]);

    }


    /**
     * @Route("/vehicles/afegir",name="afegir_vehicle")
     * @Route("/vehicles/afegir/{client}",name="afegir_vehicle_client")
     */
    public function createVehicle(Request $request,ValidatorInterface $validator, Client $client=null):Response
    {
        

        $vehicle = new Vehicle();
        if($client){
            $vehicle->setClient($client);
            
        }
        $clients = $this->getDoctrine()
            ->getRepository(Client::class)
            ->findAll();
        $models = $this->getDoctrine()
        ->getRepository(Model::class)
        ->findAll();
        
        
        $form = $this->createForm(VehicleType::class, $vehicle, array('clients' => $clients, 'models' => $models) );
        $form->handleRequest($request);
        

        if ($form->isSubmitted() && $form->isValid()) {
            $this->addFlash('success', 'vehicle.success-add');
            $this->save($vehicle);           
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
            $this->addFlash('error', 'vehicle.error-del');
        }
        $this->addFlash('success', 'vehicle.success-del');
        return $this->redirectToRoute('llistar_vehicles');
    }

    /**
     * @Route("/vehicles/{id}",name="fitxa_vehicle")
     */
    public function show(Vehicle $vehicle):Response
     {
        return $this->render('vehicle/fitxa_vehicle.html.twig', [
            'controller_name' => 'VehicleController',
            'vehicle' => $vehicle,
        ]);

       
     }



     /**
       * @Route("vehicles/modificar/{id}", name="modificar_vehicle")
       */
      public function modificarVehicle(Request $request,Vehicle $vehicle):Response
      {

         
         $form = $this->createForm(VehicleType::class, $vehicle);
         $form->handleRequest($request);

         if ($form->isSubmitted() && $form->isValid()) {
            $this->addFlash('success', 'vehicle.success-edit');
            $this->save($vehicle);
            
            
            return $this->redirectToRoute('llistar_vehicles');
        }

        return $this->render('vehicle/modificar_vehicle.html.twig', ['form' => $form->createView(),'vehicle'=>$vehicle ]);

      }

}
