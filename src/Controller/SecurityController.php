<?php

namespace App\Controller;



use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\SwiftmailerBundle;
use App\Services\Mailer;
use App\Form\Inscription\{ClientProInscriptionType, ClientParticulierInscriptionType};
use App\Manager\ClientManager;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\Translation\TranslatorInterface;
use App\Entity\User;
use App\Form\{UserType, ForgotPassType, ResetPassType};
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Bundle\SwiftmailerBundle\Swift_Message;

use Omines\DataTablesBundle\Adapter\ArrayAdapter;
use Omines\DataTablesBundle\Column\TextColumn;
use Omines\DataTablesBundle\DataTableFactory;
use Omines\DataTablesBundle\Adapter\Doctrine\ORMAdapter;
use Doctrine\ORM\QueryBuilder;
use Swift_Mailer;
use Swift_SmtpTransport;

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
    public function llistar_usuaris(Request $request, DataTableFactory $dataTableFactory, TranslatorInterface $translator): Response
    {

        $this->translator = $translator;

        $usuaris = $this->getDoctrine()
            ->getRepository(User::class)
            ->findAll();

        $table = $dataTableFactory->create()
            //->add('id', TextColumn::class, ['label' => 'id','searchable'=> True])
            ->add('email', TextColumn::class, ['label' => 'Nom', 'searchable' => True])
            /*->add('roles', TextColumn::class, ['label' => 'Rols'])*/
            ->add('id', TextColumn::class, ['label' => '', 'render' => function ($value, $context) {

                $action = "";
                $action = '
                    <div class="btn-group">
                        <a href="/user/' . $value . '" class="badge badge-secondary p-1 m-2">' . $this->translator->trans('user.fitxa') . '</a>
                    </div>';

                return $action;
            }])
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

    /**
     * @Route("user/{id}", name="fitxa_user")
     */
    public function show(User $user): Response
    {
        return $this->render('security/fitxa_user.html.twig', [
            'controller_name' => 'SecurityController',
            'user' => $user,
        ]);
    }


    /**
     * @Route("user/modificar/{id}", name="modificar_user")
     */
    public function modificarUser(Request $request, User $user): Response
    {

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->addFlash('success', 'user.success-edit');
            $this->save($user);

            return $this->redirectToRoute('llistar_usuaris');
        }

        return $this->render('security/modificar_user.html.twig', ['form' => $form->createView(), 'usuari' => $user]);
    }

    /**
     * @Route("/forgot-password", name="forgot_password", defaults={"email=null"})
     */
    public function forgotpass(Request $request,\Swift_Mailer $mailer)
    {
        $form = $this->createForm(ForgotPassType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $email = $form->getData();
            $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(array('email' => $email['email']));

            if (!empty($user)) {
                $identifier = substr(md5(random_bytes(10)), 15);

                $user->setResetCode($identifier);
                $this->save($user);

                $url = $this->generateUrl('reset_password', array('email' => $email['email'], 'identifier' => $identifier));


                $to = $user->getEmail();
                $subject = "Restaurar contrasenya -  Gestaller";

                $defaultMessage = $this->renderView('email/regenerar_acces.html.twig', [
                    'url' => $url,
                ]);

                /*$transport = (new Swift_SmtpTransport('ssl0.ovh.net',465))
                    ->setUsername('gestaller@fernandezjulian.com')
                    ->setPassword('N2usiCPdrknUmrL');
                $mailer = new Swift_Mailer($transport);*/



                $message = (new \Swift_Message($subject))
                    ->setFrom('gestaller@fernandezjulian.com')
                    ->setTo($to)
                    ->setBody($defaultMessage, 'text/html');
                

                $failures = "";

                
                

                $mailer->send($message);
            }

            $this->addFlash("success", "S'ha enviat un correu electronic per a resetar la contrasenya.");

            return $this->redirectToRoute('app_login');
        }

        return $this->render('/security/forgot_password.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/reset-password/{email}/{identifier}", name="reset_password", defaults={"email=null"})
     */
    public function resetPassword(Request $request, UserPasswordEncoderInterface $passwordEncoder, $email, $identifier)
    {

        $user = $this->getDoctrine()->getRepository(User::class)->findOneByEmail($email);

        if ($identifier != $user->getResetCode()) {
            return $this->redirect($this->generateUrl('app_client_login'));
        }

        $form = $this->createForm(ResetPassType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $p1 = $form->get('plainPassword')->getData();
            $p2 = $form->get('plainPassword2')->getData();

            if ($p1 == $p2) {

                $user->setPassword(
                    $passwordEncoder->encodePassword(
                        $user,
                        $form->get('plainPassword')->getData()
                    )
                );

                $user->setResetCode(null);
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($user);
                $entityManager->flush();
                $this->addFlash("success", "Contrasenya modificada correctament");

                return $this->redirect($this->generateUrl('app_login'));
            } else {
                $form->get('plainPassword')->addError(new FormError('Les contrasenyes eren iguals'));
            }
        }

        return $this->render('security/reset_password.html.twig', ['form' => $form->createView()]);
    }
}
