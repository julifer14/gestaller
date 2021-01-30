<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\{Pressupost, Article, LiniaPressupost, Empresa};
use App\Form\{PressupostType, LiniaPressupostType};
use App\Manager\PressupostManager;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;

use Omines\DataTablesBundle\Adapter\ArrayAdapter;
use Omines\DataTablesBundle\Column\TextColumn;
use Omines\DataTablesBundle\DataTableFactory;
use Omines\DataTablesBundle\Adapter\Doctrine\ORMAdapter;

use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use Knp\Snappy\Pdf;

class PressupostController extends BaseController
{
    /**
     * @Route("pressupostos/{id}/pdf", name="pressupost_pdf")
     */
    public function pdfAction(Pdf $pdf, Pressupost $pressupost)
    {
        $empresa = $this->getDoctrine()
            ->getRepository(Empresa::class)
            ->findOneBy(['id' => 1]);

        $html = $this->renderView('pressupost/fitxa_pressupost_pdf.html.twig', [
            'controller_name' => 'PressupostController',
            'pressupost' => $pressupost,
            'empresa' => $empresa,
        ]);
        $nomFitxer = "pressupost" . $pressupost->getId();
        return new PdfResponse(
            $pdf->getOutputFromHtml($html),
            $nomFitxer
        );
    }

    /**
     * @Route("/pressupostos", name="llistar_pressupostos")
     */
    public function llistar_pressupostos(Request $request, DataTableFactory $dataTableFactory): Response
    {
        $pressupostos = $this->getDoctrine()
            ->getRepository(Pressupost::class)
            ->findAll();

        $table = $dataTableFactory->create()
            ->add('estat', TextColumn::class, ['label' => 'Estat', 'render' => function ($value, $context) {
                $action = "";
                if ($value) {
                    $action = '<span class="dot-green"></span>';
                     $action=$action.' <a href="/pressupostos/' . $context . '/rebutjat" class="badge badge-danger p-2 m-1">Rebutjar</a>';
                } else {
                    $action = '<span class="dot-red"></span>';
                    $action=  $action. '<a href="/pressupostos/' . $context . '/acceptat" class="badge badge-success p-2 m-1">Acceptar</a>';
                    
                }
                return $action;
            }])
            //->add('id', TextColumn::class, ['label' => 'Codi pressupost','searchable'=> True]) 
            ->add('vehicle', TextColumn::class, ['label' => 'Vehicle', 'searchable' => True, 'field' => 'vehicle.Matricula'])
            ->add('client', TextColumn::class, ['label' => 'Client', 'searchable' => True, 'field' => 'vehicle.client'])
            ->add('treballador', TextColumn::class, ['label' => 'Treballador', 'field' => 'treballador.nom'])

            ->add('id', TextColumn::class, ['label' => '', 'render' => function ($value, $context) {


                $action = "";
                if ($value < 10) {
                    $action = '0';
                } 
                $action = $action.$value.' 
                        <div class="btn-group">
                            <a href="/pressupostos/' . $value . '" class="badge badge-secondary p-2 m-1">Veure pressupost</a>
                            <a href="/pressupostos/modificar/' . $value . '" class="badge badge-secondary p-2 m-1">Modificar pressupost</a>
                            <a href="/pressupostos/' . $value . '/pdf" class="badge badge-success p-2 m-1">Generar pdf</a>
                            <!--<a href="/pressupostos/' . $value . '/acceptat" class="badge badge-light p-2 m-1">✅</a>
                            <a href="/pressupostos/' . $value . '/rebutjat" class="badge badge-light p-2 m-1">❌</a>-->
                        </div>';
                if ($context->getEstat()) {
                    //Pressupost esta acceptat
                   // $action = $action . ' <a href="/pressupostos/' . $context . '/rebutjat" class="badge badge-danger p-2 m-1">Rebutjar pressupost</a>';
                     $action = $action.'<a href="/pressupostos/' . $context . '/ordrerep" class="badge badge-info p-2 m-1">Crear Ordre Reparació</a>';
                } else {
                    //Pressupost esta rebutjat
                    //$action = $action .  '<a href="/pressupostos/' . $context . '/acceptat" class="badge badge-success p-2 m-1">Acceptar pressupost</a>';
                }

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
     * @Route("/pressupostos/afegirLinia",name="afegir_linia_pressupost")
     */
    public function createLinia(Request $request): Response
    {

        $id_pressupost = $request->request->get('id_pressupost');
        $total_linia = $request->request->get('total_linia');
        $pressupost = $this->getDoctrine()->getRepository(Pressupost::class)->findOneBy(['id' => $id_pressupost]);
        //Comprovacions de pressupost correcte

        $articles =  $this->getDoctrine()->getRepository(Article::class)->findAll();
        return $this->render('pressupost/parcial/formLinia.html.twig', ['total_linia' => $total_linia, 'articles' => $articles]);
    }




    /**
     * @Route("/pressupostos/afegir",name="afegir_pressupost")
     */
    public function createPressupost(Request $request, ValidatorInterface $validator, PressupostManager $pressupostManager): Response
    {
        $entityManager = $this->obManager();
        $articles = $this->getDoctrine()
            ->getRepository(Article::class)
            ->findAll();

        $pressupost = new Pressupost();


        //Treure!
        $pressupost->setAny(2020);
        $date = new \DateTime('@' . strtotime('now'));
        $pressupost->setData($date);
        $pressupost->setTotal(0);

        $form = $this->createForm(PressupostType::class, $pressupost);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $noves_linies = $request->request->get('new_linia');
            $pressupostManager->savePressupost($pressupost, $noves_linies);
            $this->addFlash('success', 'pressupost.success-add');

            return $this->redirectToRoute('llistar_pressupostos', array("id" => $pressupost->getId()));
        }

        return $this->render('pressupost/afegir.html.twig', ['form' => $form->createView(), 'articles' => $articles]);
    }

    /**
     * @Route("pressupostos/{id}/acceptat", name="acceptar_pressupost")
     */
    public function acceptarPressupost(Request $request, Pressupost $pressupost): Response
    {
        $pressupost->setEstat(1);
        $this->addFlash('success', 'pressupost.canviEstat');
        $this->save($pressupost);

        return $this->redirectToRoute('llistar_pressupostos');
    }

    /**
     * @Route("pressupostos/{id}/rebutjat", name="rebutjat_pressupost")
     */
    public function rebutjarPressupost(Request $request, Pressupost $pressupost): Response
    {
        $pressupost->setEstat(0);
        $this->addFlash('success', 'pressupost.canviEstat');
        $this->save($pressupost);

        return $this->redirectToRoute('llistar_pressupostos');
    }

    /**
     * @Route("pressupostos/modificar/{id}", name="modificar_pressupost")
     */
    public function modificarPressupost(Request $request, Pressupost $pressupost, PressupostManager $pressupostManager): Response
    {

        $form = $this->createForm(PressupostType::class, $pressupost);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $noves_linies = $request->request->get('new_linia');
            $pressupostManager->savePressupost($pressupost, $noves_linies);
            $this->addFlash('success', 'pressupost.success-edit');
            return $this->redirectToRoute('llistar_pressupostos');
        }

        return $this->render('pressupost/modificar_pressupost.html.twig', ['form' => $form->createView(), 'pressupost' => $pressupost]);
    }

    /**
     * @Route("pressupostos/{id}", name="pressupost_show")
     */
    public function show(Pressupost $pressupost): Response
    {
        return $this->render('pressupost/fitxa_pressupost.html.twig', [
            'controller_name' => 'PressupostController',
            'pressupost' => $pressupost,
        ]);
    }
}
