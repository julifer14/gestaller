<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\{Article, Categoria};
use App\Form\ArticleType;
use Symfony\Component\Validator\Validator\ValidatorInterface;



use Omines\DataTablesBundle\Adapter\ArrayAdapter;
use Omines\DataTablesBundle\Column\TextColumn;
use Omines\DataTablesBundle\DataTableFactory;
use Omines\DataTablesBundle\Adapter\Doctrine\ORMAdapter;

class ArticleController extends BaseController
{
    /**
     * @Route("/articles", name="llistar_articles")
     */
    public function llistar_articles(Request $request, DataTableFactory $dataTableFactory): Response
    {

        $articles = $this->getDoctrine()
            ->getRepository(Article::class)
            ->findAll();

        $table = $dataTableFactory->create()
            ->add('categoria', TextColumn::class, ['label' => 'Categoria', 'field' => 'categoria.nom'])
            ->add('nom', TextColumn::class, ['label' => 'Nom', 'searchable' => True])
            //->add('descripcio', TextColumn::class, ['label' => 'Descripció'])
            ->add('preu', TextColumn::class, ['label' => 'Preu'])
            //->add('iva', TextColumn::class, ['label' => 'IVA'])
            ->add('stock', TextColumn::class, ['label' => 'Stock'])

            ->add('id', TextColumn::class, ['label' => '', 'render' => function ($value, $context) {

                $action = "";
                $action = '
                        <div class="btn-group">
                            <a href="/articles/' . $value . '" class="badge badge-info p-2 m-1">Fitxa article</a>
                            <a href="/articles/modificar/' . $value . '" class="badge badge-secondary p-2 m-1">Modificar article</a>
                            <a href="/articles/esborrar/' . $value . '" class="badge badge-danger p-2 m-1">Esborrar article</a> 
                        </div>';

                return $action;
            }])
            ->createAdapter(ORMAdapter::class, [
                'entity' => Article::class,
            ])
            ->handleRequest($request);

        if ($table->isCallback()) {
            return $table->getResponse();
        }

        return $this->render('article/index.html.twig', ['datatable' => $table]);
    }

    /**
     * @Route("/articles/afegir",name="afegir_article")
     */
    public function createArticle(Request $request, ValidatorInterface $validator): Response
    {

        $article = new Article();

        $categories = $this->getDoctrine()
            ->getRepository(Categoria::class)
            ->findAll();


        $form = $this->createForm(ArticleType::class, $article, array('categories' => $categories));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->addFlash('success', 'article.success-add');
            $this->save($article);

            return $this->redirectToRoute('llistar_articles');
        }

        return $this->render('article/afegir.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/articles/esborrar/{id}", name="esborrar_article")
     */
    public function esborrarArticle(Article $article): Response
    {
        $resultat = $this->esborrar($article);


        if (!$resultat) {
            $this->addFlash('error', 'article.error-del');
        }
        $this->addFlash('success', 'article.success-del');
        return $this->redirectToRoute('llistar_articles');
    }

    /**
     * @Route("articles/{id}", name="fitxa_article")
     */
    public function show(Article $article): Response
    {
        return $this->render('article/fitxa_article.html.twig', [
            'controller_name' => 'ArticleController',
            'article' => $article,
        ]);
    }


    /**
     * @Route("articles/modificar/{id}", name="modificar_article")
     */
    public function modificarArticle(Request $request, Article $article): Response
    {


        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->addFlash('success', 'article.success-edit');
            $this->save($article);

            return $this->redirectToRoute('llistar_articles');
        }

        return $this->render('article/modificar_article.html.twig', ['form' => $form->createView(), 'article' => $article]);
    }
}
