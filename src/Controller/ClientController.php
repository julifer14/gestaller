<?php

namespace App\Controller;

use App\Form\ClientType;
use App\Controller\BaseController;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Client;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

use Omines\DataTablesBundle\Adapter\ArrayAdapter;
use Omines\DataTablesBundle\Column\TextColumn;
use Omines\DataTablesBundle\DataTableFactory;
use Omines\DataTablesBundle\Adapter\Doctrine\ORMAdapter;

class ClientController extends BaseController
{
    /**
     * @Route("/clients/afegir",name="afegir_client")
     */
     public function createClient(Request $request,ValidatorInterface $validator):Response
     {
         $entityManager = $this->obManager();

         $client = new Client();

         $form = $this->createForm(ClientType::class, $client);
         $form->handleRequest($request);

         if ($form->isSubmitted() && $form->isValid()) {
            
            $entityManager->persist($client);

            $entityManager->flush();
            
            return $this->redirectToRoute('llistar_clients');
        }

        return $this->render('client/afegir.html.twig', ['form' => $form->createView() ]);
        
     }

    /**
     * @Route("/clients/esborrar/{id}", name="esborrar_client")
     */
     public function esborrarClient(Client $client):Response
     {


        $resultat = $this->esborrar($client);

        if(!$resultat){
            // fflash
        }
        return $this->redirectToRoute('llistar_clients');


     }

     /**
      * @Route("/clients", name="llistar_clients")
      */
      public function llistarClients(Request $request, DataTableFactory $dataTableFactory):Response
      { 

        
        $clients = $this->getDoctrine()
            ->getRepository(Client::class)
            ->findAll();

        $table = $dataTableFactory->create()
        ->add('nom', TextColumn::class, ['label' => 'Nom','searchable'=> True])
        ->add('cognoms', TextColumn::class, ['label' => 'Cognoms'])
        ->add('adreca', TextColumn::class, ['label' => 'Adreça'])
        ->add('telefon', TextColumn::class, ['label' => 'Telefon'])
        ->add('email', TextColumn::class, ['label' => 'Email'])
        ->add('id', TextColumn::class, ['label' => '', 'render' => function($value, $context) {
                                        
           $action = "";
           $action = '
                        <div class="btn-group">
                            <a href="/clients/'.$value.'" class="badge badge-info p-1 m-2">Fitxa client</a>
                            <a href="/clients/modificar/'.$value.'" class="badge badge-secondary p-1 m-2">Modificar client</a>
                            <a href="/clients/esborrar/'.$value.'" class="badge badge-danger p-1 m-2">Esborrar client</a> 
                        </div>';
           
            return $action;                   
        }])
        ->createAdapter(ORMAdapter::class, [
            'entity' => Client::class,
        ])
        ->handleRequest($request);

        if ($table->isCallback()) {
            return $table->getResponse();
        }

        return $this->render('client/index.html.twig', ['datatable' => $table]);

      }

      /**
       * @Route("clients/modificar/{id}", name="modificar_client")
       */
      public function modificarClient(Request $request,Client $client):Response
      {
        $entityManager = $this->obManager();

         
         $form = $this->createForm(ClientType::class, $client);
         $form->handleRequest($request);

         if ($form->isSubmitted() && $form->isValid()) {
            
            $entityManager->persist($client);

            $entityManager->flush();
            
            return $this->redirectToRoute('llistar_clients');
        }

        return $this->render('client/modificar_client.html.twig', ['form' => $form->createView(),'client'=>$client ]);





        /*return $this->render('client/modificar_client.html.twig', [
            'controller_name' => 'ClientController',
            'client' => $client,
        ]);*/
      }

     /**
      * @Route("clients/{id}", name="client_show")
      */
     public function show(Client $client):Response
     {
        return $this->render('client/fitxa_client.html.twig', [
            'controller_name' => 'ClientController',
            'client' => $client,
        ]);

       
     }




}


/*class ClientController extends AbstractController
{
    /**
     * @Route("/client", name="client")
     */
   /* public function index(): Response
    {
        return $this->render('client/index.html.twig', [
            'controller_name' => 'ClientController',
        ]);
    }
}*/
