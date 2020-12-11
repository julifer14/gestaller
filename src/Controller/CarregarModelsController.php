<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\{Model,Marca};

class CarregarModelsController extends BaseController
{
    /**
     * @-Route("/carregarmodels", name="carregar_models")
     */
    public function index(): Response
    {
        echo "Models ja carregats";
        /*$entityManager = $this->obManager();
        $row = 1;
        if (($handle = fopen(__DIR__."/models.csv", "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                $num = count($data);
                echo "<p> $num fields in line $row: <br /></p>\n";
                $row++;
                //echo $data[0]. " ". $data[1]."<br>";

                $marca = $this->getDoctrine()
                ->getRepository(Marca::class)
                ->findOneBy(['nom'=>$data[0]]);
                //echo $marca->getNom();
                
                $model = new Model();
                $model->setNom($data[1]);
                $model->setMarca($marca);
                $entityManager->persist($model);

                $entityManager->flush();

                
            }
        fclose($handle);
        }*/
        


        return $this->render('carregar_models/index.html.twig', [
            'controller_name' => 'CarregarModelsController',
        ]);
    }
}
