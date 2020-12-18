<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;


class BaseController extends AbstractController
{

    public function esborrar($entity){
        $this->getDoctrine()->getManager()->remove($entity);
        $this->getDoctrine()->getManager()->flush();

        return !$this->getDoctrine()->getManager()->contains($entity);
    }

    public function obManager(){
        return $this->getDoctrine()->getManager();
    }

    public function save($entity){
    	$this->obManager()->persist($entity);
    	$this->obManager()->flush();
    }
}