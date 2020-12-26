<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\Translation\TranslatorInterface;
use App\Entity\User;

use Omines\DataTablesBundle\Adapter\ArrayAdapter;
use Omines\DataTablesBundle\Column\TextColumn;
use Omines\DataTablesBundle\DataTableFactory;
use Omines\DataTablesBundle\Adapter\Doctrine\ORMAdapter;
use Doctrine\ORM\QueryBuilder;

class SecurityController extends BaseController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    /**
     * @Route("/llistarUsuaris",name="llistar_usuaris")
     */
    public function llistar_usuaris(Request $request, DataTableFactory $dataTableFactory, TranslatorInterface $translator):Response
    {
        $usuaris = $this->getDoctrine()
        ->getRepository(User::class)
        ->findAll();

    $table = $dataTableFactory->create()
    ->add('id', TextColumn::class, ['label' => 'id','searchable'=> True])
    ->add('email', TextColumn::class, ['label' => 'Nom','searchable'=> True])
    /*->add('roles', TextColumn::class, ['label' => 'Rols'])*/
    /*->add('id', TextColumn::class, ['label' => '', 'render' => function($value, $context) {
                                    
       $action = "";
       $action = '
                    <div class="btn-group">
                        <a href="/clients/'.$value.'" class="badge badge-info p-1 m-2">'.$this->translator->trans('client.fitxa').'</a>
                        <a href="/clients/modificar/'.$value.'" class="badge badge-secondary p-1 m-2">'.$this->translator->trans('client.edit').'</a>
                        <a href="/clients/esborrar/'.$value.'" class="badge badge-danger p-1 m-2">'.$this->translator->trans('client.del').'</a> 
                    </div>';
       
        return $action;                   
    }])*/
    ->createAdapter(ORMAdapter::class, [
        'entity' => User::class,
        /*'query' => function (QueryBuilder $builder){
            $builder
                ->select('user')
                ->from(User::class, 'user')
                ->orderBy('user.email')
            ;
        }*/
    ])
    ->handleRequest($request);

    if ($table->isCallback()) {
        return $table->getResponse();
    }

    return $this->render('security/llistar.html.twig', ['datatable' => $table]);

    }

 
}
