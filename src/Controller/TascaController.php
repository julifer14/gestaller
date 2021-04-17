<?php

namespace App\Controller;


use App\Controller\BaseController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\{Tasca, Article, Factura, Empresa, OrdreReparacio};
use App\Form\TascaType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;


use Omines\DataTablesBundle\Column\TextColumn;
use Omines\DataTablesBundle\DataTableFactory;
use Omines\DataTablesBundle\Adapter\Doctrine\ORMAdapter;

class TascaController extends BaseController
{
    

    /**
     * @Route("/tasques", name="llistar_tasques")
     */
    public function llistar_tasques(Request $request, DataTableFactory $dataTableFactory): Response
    {
        $table = $dataTableFactory->create()

            ->add('nom', TextColumn::class, ['label' => 'nom', 'searchable' => True,])
            ->add('descripcio', TextColumn::class, ['label' => 'Descripció', 'searchable' => True, ])
            ->add('temps', TextColumn::class, ['label' => 'Durada', ])
           
            ->add('id', TextColumn::class, ['label' => '', 'searchable' => True, 'orderable' => True, 'render' => function ($value, $context) {


                $action = "";
                if ($value < 10) {
                    $action = '0';
                }
                $action = $action . $value . ' 
                        <div class="btn-group">
                            
                            <a href="/tasques/modificar/' . $value . '" class="badge badge-secondary p-2 m-1">Modificar tasca</a>
                            
                        </div>';
                

                return $action;
            }])
            //->addOrderBy(5, 'desc')
            ->createAdapter(ORMAdapter::class, [
                'entity' => Tasca::class,
            ])
            ->handleRequest($request);

        if ($table->isCallback()) {
            return $table->getResponse();
        }

        return $this->render('tasca/index.html.twig', ['datatable' => $table]);
    }


     /**
     * @Route("/tasques/afegir",name="afegir_tasques")
     */
    public function createTasca(Request $request, ValidatorInterface $validator): Response
    {
        $tasca = new Tasca();

        $form = $this->createForm(TascaType::class, $tasca);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->save($tasca);
            $this->addFlash('success', 'tasca.success-add');
            return $this->redirectToRoute('llistar_tasques');
        }

        return $this->render('tasca/afegir.html.twig', ['form' => $form->createView()]);
    }
}
