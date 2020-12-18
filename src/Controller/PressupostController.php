<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\{Pressupost};
use App\Form\PressupostType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;

use Omines\DataTablesBundle\Adapter\ArrayAdapter;
use Omines\DataTablesBundle\Column\TextColumn;
use Omines\DataTablesBundle\DataTableFactory;
use Omines\DataTablesBundle\Adapter\Doctrine\ORMAdapter;




class PressupostController extends BaseController
{

    const IVA_ACTUAL = 0.21;
    /**
     * @Route("/pressupostos", name="llistar_pressupostos")
     */
    public function llistar_pressupostos(Request $request, DataTableFactory $dataTableFactory): Response
    {
        $pressupostos = $this->getDoctrine()
        ->getRepository(Pressupost::class)
        ->findAll();

        $table = $dataTableFactory->create()
        //->add('id', TextColumn::class, ['label' => 'Codi pressupost','searchable'=> True]) 
        ->add('vehicle', TextColumn::class, ['label' => 'Vehicle','searchable'=> True,'field'=>'vehicle.Matricula'])            
        ->add('client', TextColumn::class, ['label' => 'Client','searchable'=> True,'field'=>'vehicle.client.nom'])
        ->add('id', TextColumn::class, ['label' => '', 'render' => function($value, $context) {
                                        
           $action = "";
           $action = '
                        <div class="btn-group">
                            <a href="/pressupostos/'.$value.'" class="badge badge-secondary p-2 m-1">Veure pressupost</a>
                            <a href="/pressupostos/modificar/'.$value.'" class="badge badge-secondary p-2 m-1">Modificar pressupost</a>
                           <!-- <a href="/pressupostos/esborrar/'.$value.'" class="badge badge-danger p-2 m-1">Esborrar pressupost</a> -->
                        </div>';
           
            return $action;                   
        }])
        ->createAdapter(ORMAdapter::class, [
            'entity' => Pressupost::class,
        ])
        ->handleRequest($request);

        if ($table->isCallback()) {
            return $table->getResponse();
        }

        return $this->render('pressupost/index.html.twig', ['datatable' => $table]);
    }


     /**
     * @Route("/pressupostos/afegir",name="afegir_pressupost")
     */
    public function createPressupost(Request $request,ValidatorInterface $validator):Response
    {
        $entityManager = $this->obManager();

        $pressupost = new Pressupost();
       
        $pressupost->setAny(2020);
        $pressupost->setIVA(self::IVA_ACTUAL);
        $date = new \DateTime('@'.strtotime('now'));
        $pressupost->setData($date);
        
        $form = $this->createForm(PressupostType::class, $pressupost);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
           
           $entityManager->persist($pressupost);

           $entityManager->flush();
           
           return $this->redirectToRoute('llistar_pressupostos');
       }

       return $this->render('pressupost/afegir.html.twig', ['form' => $form->createView() ]);
       
    }
}
