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

use App\Entity\{Article, Factura, LiniaFactura};



class FacturaManager extends BaseManager
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
    ) {
        parent::__construct($em, $session, $translator, $tokenStorage, $requestStack, $router, $dispatcher, $authorizationChecker, $params);

        $this->em = $em;
        $this->session = $session;
    }



    public function saveFactura(Factura $factura, $linies)
    {
        $this->save($factura);
        $this->totalAcum = 0;
        if (!empty($linies)) {
            foreach ($linies as $l) {
                $linia = new LiniaFactura();
                $article = $this->getRepository("App:Article")->findOneBy(['id' => $l['article']]);
                $linia->setArticle($article);
                $linia->setQuantitat($l['qtat']);
                if ($l['descompte']) {
                    $linia->setDescompte($l['descompte']);
                    $this->totalAcum = ($this->totalAcum + (($l['preu'] * $l['qtat']) -  (($l['preu'] * $l['qtat']) * ($l['descompte'] / 100) * 1)));
                } else {
                    $linia->setDescompte(0);
                    $this->totalAcum = ($this->totalAcum + ($l['preu'] * $l['qtat']) );
                }
                $linia->setPreu($l['preu']);
                $linia->setFactura($factura);
                $article->setPreu($l['preu']);
                $article->venda($l['qtat']);


                $this->save($linia);
            }
            $factura->setTotal($this->totalAcum);
            $this->save($factura);
        }
    }
}
