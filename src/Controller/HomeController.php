<?php

namespace MicroCMS\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use MicroCMS\Domain\Comment;
use MicroCMS\Form\Type\CommentType;

/**
 * Class HomeController
 * @package microCMS\Controller
 */
class HomeController
{

    /**
     * Home page controller.
     *
     * @param Application $app Silex application
     */
    public function indexAction(Application $app)
    {
        $articles = $app['dao.article']->findAll();
        $comments = $app['dao.comment']->findAll();
        return $app['twig']->render('index.html.twig', array('articles' => $articles, 'comments' => $comments));
    }

    /**
     * Article details controller.
     * @param integer $id Article id
     * @param Request $request Incoming request
     * @param Application $app Silex application
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function articleAction($id, Request $request, Application $app)
    {
        $article = $app['dao.article']->find($id);
        $commentFormView = null;
        $checkboxFormView = null;
        if ($app['security.authorization_checker']->isGranted('IS_AUTHENTICATED_FULLY')) {
            // A user is fully authenticated : he can add comments
            $comment = new Comment();
            $comment->setArticle($article);
            $user = $app['user'];
            $comment->setAuthor($user);
            $checkboxForm = $app['form.factory']->create(CheckboxType::class, $comment);
            $commentForm = $app['form.factory']->create(CommentType::class, $comment);
            $checkboxForm->handleRequest($request);
            $commentForm->handleRequest($request);

                if ($checkboxForm->isSubmitted() && $checkboxForm->isValid()) {
                $app['session']->getFlashBag()->add('success', 'Le commentaire va être modéré par l\'administrateur');

                }
                if ($commentForm->isSubmitted() && $commentForm->isValid()) {
                $app['dao.comment']->save($comment);
                $app['session']->getFlashBag()->add('success', 'Votre commentaire à bien été ajouté');
                return $app->redirect($request->getUri());


            }
            $commentFormView = $commentForm->createView();
            $checkboxFormView = $checkboxForm->createView();

        }
        $comments = $app['dao.comment']->findAllByArticle($id);
        return $app['twig']->render('article.html.twig', array('article' => $article, 'comments' => $comments, 'commentForm' => $commentFormView));

    }


    /**
     * User login controller.
     *
     * @param Request $request Incoming request
     * @param Application $app Silex application
     */
    public function loginAction(Request $request, Application $app)
    {
        return $app['twig']->render('login.html.twig', array('error' => $app['security.last_error']($request), 'last_username' => $app['session']->get('_security.last_username'),));
    }

    /**
     * Comment details controller.
     * @param integer $commentId Comment id
     * @param integer $articleId Article id
     *
     * @param Request $request Incoming request
     * @param Application $app Silex application
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function commentAction($commentId, $articleId, Request $request, Application $app)
    {
        $article = $app['dao.article']->find($articleId);
        $formerComment = $app['dao.comment']->find($commentId);
        $commentFormView = null;
        if ($app['security.authorization_checker']->isGranted('IS_AUTHENTICATED_FULLY')) {
            // A user is fully authenticated : he can add comments
            $comment = new Comment();
            $user = $app['user'];
            $comment->setAuthor($user);
            $commentForm = $app['form.factory']->create(CommentType::class, $comment);
            $commentForm->handleRequest($request);
            if ($commentForm->isSubmitted() && $commentForm->isValid()) {
                $app['dao.comment']->saveNestedComment($comment);
                $app['session']->getFlashBag()->add('success', 'Votre commentaire à bien été ajouté');
                return $app->redirect($request->getUri());


            }
            $commentFormView = $commentForm->createView();
        }
        $comments = $app['dao.comment']->findAllByArticle($articleId);
        return $app['twig']->render('comment.html.twig', array('article' => $article, 'comments' => $comments, 'comment' => $formerComment, 'commentForm' => $commentFormView));

    }
}
