<?php

namespace App\Manager;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

use App\Entity\{Article, Pressupost, LiniaPressupost};



class PressupostManager extends BaseManager
{

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
        parent::__construct($em, $session, $translator, $tokenStorage, $requestStack, $router, $dispatcher, $authorizationChecker, $params); 

        $this->em = $em;
        $this->session = $session;
    }
    

    
    public function savePressupost(Pressupost $pressupost, $linies){
        $this->save($pressupost);
        if(!empty($linies)){
            foreach($linies as $l){
                $linia = new LiniaPressupost();
                $article = $this->getRepository("App:Article")->findOneById($l['article']);
                $linia->setArticle($article);
                $linia->setQuantitat($l['qtat']);
                $linia->setPreu($article->getPreu()*$l['qtat']);
                $linia->setPressupost($pressupost);
                $this->save($linia);
            }
        } 
    }
}
