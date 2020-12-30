<?php

namespace App\Manager;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\PersistentCollection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use DateTime;
use IntlDateFormatter;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;


class BaseManager
{
	protected $em;
	protected $session;
	protected $translator;
	protected $tokenStorage;
	protected $requestStack;
	protected $router;
	protected $templating;
	protected $dispatcher;
	protected $authorizationChecker;
	protected $tmp;
	protected $params;

	public function __construct(
		EntityManagerInterface $em, 
		SessionInterface $session, 
		TranslatorInterface $translator, 
		TokenStorageInterface $tokenStorage, 
		RequestStack $requestStack,
		RouterInterface $router,
		EventDispatcherInterface $dispatcher,
		AuthorizationCheckerInterface $authorizationChecker,
		ParameterBagInterface $params

		)
	{
		$this->em = $em;
		$this->session = $session;
		$this->translator = $translator;
		$this->tokenStorage = $tokenStorage;
		$this->requestStack = $requestStack;
		$this->router = $router;
		$this->dispatcher = $dispatcher;
		$this->authorizationChecker = $authorizationChecker;
		$this->params = $params;
	}


	public function save($entity, $flashMessages = null)
	{
		$this->em->persist($entity);
		$this->em->flush();
        if(!is_null($flashMessages))
        {
            $this->addFlash('success', $flashMessages = null);
        }
	}


    /**
     * @param object $entity        The entity to update
     * @param string $flashMessages Optional flashbag message
     */
    public function update($entity = null, $flashMessages = null)
	{
		$this->em->flush();
        if(!is_null($flashMessages))
        {
            $this->addFlash('success', $flashMessages = null);
        }
	}

	public function remove($entity, $flashMessages = null)
	{
		$this->em->remove($entity);
		$this->em->flush();
        if(!is_null($flashMessages))
        {
            $this->addFlash('success', $flashMessages = null);
        }
	}


	public function getUser()
	{
		$token = $this->tokenStorage->getToken();

		return is_null($token) ? null : $token->getUser();
	}

	public function getRepository($entityName)
	{
		return $this->em->getRepository($entityName);
	}

	public function persist($entity)
	{
		$this->em->persist($entity);
		$this->em->flush();
	}

    public function getErrorMessages(FormInterface $form)
    {
        if ($form->isValid()) return [];
        $errors = [];
        foreach ($form->getErrors() as $key => $error) {
            $errors[] = $error->getMessage();
        }

        foreach ($form->all() as $child) {
            if (!$child->isValid()) {
                $errors[$child->getName()] = $this->getErrorMessages($child);
            }
        }

        return $errors;
    }


	protected function isGranted($attributes, $object = null)
	{
		return $this->authorizationChecker->isGranted($attributes, $object);
	}

	public function getEntityManager()
	{
		return $this->em;
	}


 	protected function addFlash($type, $message)
    {
		$flashbag = $this->session->getFlashBag();
		$flashbag->add($type, $message);
    }

    	public function render($template, array $params = array())
	{
		return $this->templating->render($template, $params);
	}

}
