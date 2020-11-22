<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Client;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;


class ClientController extends AbstractController
{
    /**
     * @Route("/client",name="create_client")
     */

     public function createClient(ValidatorInterface $validator):Response
     {
         $entityManager = $this->getDoctrine()->getManager();

         $client = new Client();
         $client->setNom("Julian");
         $client->setCognoms("Fernandez");
         $client->setAdreca("Carrer Aurora 10 17780 Garriguella");
         $client->setTelefon("655028669");
         $client->setEmail("juli.fer14@gmail.com");
         
         $errors = $validator->validate($client);
         if(count($errors)>0){
             return new Response((string) $errors,400);
         }
         $entityManager->persist($client);

         $entityManager->flush();

         return new Response('Guardat client amb id '.$client->getId());
     }

     /**
      * @Route("client/{id}", name="client_show")
      */
     public function show(int $id):Response
     {
         $client = $this->getDoctrine()
            ->getRepository(Client::class)
            ->find($id);

        if(!$client){
            throw $this->createNotFoundException(
                "No s'ha trobat client amb id ". $id
            );
        }
        return new Response('Hola '. $client->getNom()."!");
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
