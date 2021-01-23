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
use Symfony\Contracts\Translation\TranslatorInterface;

use Omines\DataTablesBundle\Adapter\ArrayAdapter;
use Omines\DataTablesBundle\Column\TextColumn;
use Omines\DataTablesBundle\DataTableFactory;
use Omines\DataTablesBundle\Adapter\Doctrine\ORMAdapter;
use Doctrine\ORM\QueryBuilder;

class ClientController extends BaseController
{
    /**
     * @Route("/clients/afegir",name="afegir_client")
     */
    public function createClient(Request $request, ValidatorInterface $validator): Response
    {
        $client = new Client();

        $form = $this->createForm(ClientType::class, $client);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->save($client);
            $this->addFlash('success', 'client.success-add');
            return $this->redirectToRoute('llistar_clients');
        }

        return $this->render('client/afegir.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/clients/esborrar/{id}", name="esborrar_client")
     */
    public function esborrarClient(Client $client): Response
    {

        $resultat = $this->esborrar($client);

        if (!$resultat) {
            $this->addFlash('danger', 'client.error-del');
        }
        return $this->redirectToRoute('llistar_clients');
    }

    /**
     * @Route("/clients", name="llistar_clients")
     */
    public function llistarClients(Request $request, DataTableFactory $dataTableFactory, TranslatorInterface $translator): Response
    {
        $this->translator = $translator;


        $clients = $this->getDoctrine()
            ->getRepository(Client::class)
            ->findAll();

        $table = $dataTableFactory->create()
            ->add('nom', TextColumn::class, ['label' => 'Nom', 'searchable' => True])
            ->add('cognoms', TextColumn::class, ['label' => 'Cognoms'])
            ->add('adreca', TextColumn::class, ['label' => 'Adreça'])
            ->add('telefon', TextColumn::class, ['label' => 'Telefon'])
            ->add('email', TextColumn::class, ['label' => 'Email'])
            ->add('id', TextColumn::class, ['label' => '', 'render' => function ($value, $context) {

                $action = "";
                $action = '
                        <div class="btn-group">
                            <a href="/clients/' . $value . '" class="badge badge-info p-1 m-2">' . $this->translator->trans('client.fitxa') . '</a>
                            <a href="/clients/modificar/' . $value . '" class="badge badge-secondary p-1 m-2">' . $this->translator->trans('client.edit') . '</a>
                            <a href="/clients/esborrar/' . $value . '" class="badge badge-danger p-1 m-2">' . $this->translator->trans('client.del') . '</a> 
                            <a href="/vehicles/afegir/' . $value . '" class="badge badge-primary p-1 m-2">' . $this->translator->trans('client.addVehicle') . '</a> 
                        </div>';

                return $action;
            }])
            ->createAdapter(ORMAdapter::class, [
                'entity' => Client::class,
                'query' => function (QueryBuilder $builder) {
                    $builder
                        ->select('client')
                        ->from(Client::class, 'client')
                        ->orderBy('client.cognoms');
                }
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
    public function modificarClient(Request $request, Client $client): Response
    {

        $form = $this->createForm(ClientType::class, $client);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->addFlash('success', 'client.success-edit');
            $this->save($client);

            return $this->redirectToRoute('llistar_clients');
        }

        return $this->render('client/modificar_client.html.twig', ['form' => $form->createView(), 'client' => $client]);
    }

    /**
     * @Route("clients/{id}", name="client_show")
     */
    public function show(Client $client): Response
    {
        return $this->render('client/fitxa_client.html.twig', [
            'controller_name' => 'ClientController',
            'client' => $client,
        ]);
    }
}
