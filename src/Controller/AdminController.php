<?php

namespace Projet3\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Projet3\Domain\Article;
use Projet3\Domain\User;
use Projet3\Form\Type\ArticleType;
use Projet3\Form\Type\CommentType;
use Projet3\Form\Type\UserType;

class AdminController {

    /**
     * Admin home page controller.
     *
     * @param Application $app Silex application
     */
    public function indexAction(Application $app) {
        $articles = $app['dao.article']->findAll();
        $approvedComments = $app['dao.comment']->findApprovedComments();
        $moderateComments = $app['dao.comment']->findModerateComments();
        $users = $app['dao.user']->findAll();
        return $app['twig']->render('admin.html.twig', array(
            'articles' => $articles,
            'approvedComments' => $approvedComments,
            'moderations' => $moderateComments,
            'users' => $users));
    }

    /**
     *
     *
     * Add article controller.
     *
     * @param Request $request Incoming request
     * @param Application $app Silex application
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function addArticleAction(Request $request, Application $app) {
        $article = new Article();
        $articleForm = $app['form.factory']->create(ArticleType::class, $article);
        $articleForm->handleRequest($request);
        if ($articleForm->isSubmitted() && $articleForm->isValid()) {
            $app['dao.article']->save($article);
            $app['session']->getFlashBag()->add('success', 'Article crée');
            return $app->redirect('/admin');

        }
        return $app['twig']->render('article_form.html.twig', array(
            'title' => 'Nouvel article',
            'articleForm' => $articleForm->createView()));
    }

    /**
     * Edit article controller.
     *
     * @param integer $id Article id
     * @param Request $request Incoming request
     * @param Application $app Silex application
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function editArticleAction($id, Request $request, Application $app) {
        $article = $app['dao.article']->find($id);
        $articleForm = $app['form.factory']->create(ArticleType::class, $article);
        $articleForm->handleRequest($request);
        if ($articleForm->isSubmitted() && $articleForm->isValid()) {
            $app['dao.article']->save($article);
            $app['session']->getFlashBag()->add('success', 'Article mis à jour.');
            return $app->redirect('/admin');
        }
        return $app['twig']->render('article_form.html.twig', array(
            'title' => 'Editez l\'article',
            'articleForm' => $articleForm->createView()));
    }

    /**
     * Delete article controller.
     *
     * @param integer $id Article id
     * @param Application $app Silex application
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteArticleAction($id, Application $app) {
        // Delete all associated comments
        $app['dao.comment']->deleteAllByArticle($id);
        // Delete the article
        $app['dao.article']->delete($id);
        $app['session']->getFlashBag()->add('success', 'L\'article à bien été supprimé.');
        // Redirect to admin home page
        return $app->redirect($app['url_generator']->generate('admin'));
    }

    /**
     * Edit comment controller.
     *
     * @param integer $id Comment id
     * @param Request $request Incoming request
     * @param Application $app Silex application
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function editCommentAction($id, Request $request, Application $app) {
        $comment = $app['dao.comment']->find($id);
        $commentForm = $app['form.factory']->create(CommentType::class, $comment);
        $commentForm->handleRequest($request);
        if ($commentForm->isSubmitted() && $commentForm->isValid()) {
            $app['dao.comment']->save($comment);
            $app['session']->getFlashBag()->add('success', 'Le commentaire à bien été mis à jour');
            return $app->redirect('/admin');
        }
        return $app['twig']->render('comment_form.html.twig', array(
            'title' => 'Editez le commentaire',
            'commentForm' => $commentForm->createView()));
    }

    /**
     * Delete comment controller.
     *
     * @param integer $id Comment id
     * @param Application $app Silex application
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteCommentAction($id, Application $app) {
        // Delete all associated comments
        $app['dao.comment']->deleteAllByComment($id);
        // Delete parent comment
        $app['dao.comment']->delete($id);
        $app['session']->getFlashBag()->add('success', 'Le commentaire à bien été supprimé');
        // Redirect to admin home page
        return $app->redirect($app['url_generator']->generate('admin'));
    }

    /**
     * Add user controller.
     *
     * @param Request $request Incoming request
     * @param Application $app Silex application
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function addUserAction(Request $request, Application $app) {
        $user = new User();
        $userForm = $app['form.factory']->create(UserType::class, $user);
        $userForm->handleRequest($request);
        if ($userForm->isSubmitted() && $userForm->isValid()) {
            // generate a random salt value
            $salt = substr(md5(time()), 0, 23);
            $user->setSalt($salt);
            $plainPassword = $user->getPassword();
            // find the default encoder
            $encoder = $app['security.encoder.bcrypt'];
            // compute the encoded password
            $password = $encoder->encodePassword($plainPassword, $user->getSalt());
            $user->setPassword($password);
            $app['dao.user']->save($user);
            $app['session']->getFlashBag()->add('success', 'L\'utilisateur à bien été crée');
            return $app->redirect('/admin');
        }
        return $app['twig']->render('user_form.html.twig', array(
            'title' => 'Nouvel utilisateur',
            'userForm' => $userForm->createView()));
    }

    /**
     * Edit user controller.
     *
     * @param integer $id User id
     * @param Request $request Incoming request
     * @param Application $app Silex application
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function editUserAction($id, Request $request, Application $app) {
        $user = $app['dao.user']->find($id);
        $userForm = $app['form.factory']->create(UserType::class, $user);
        $userForm->handleRequest($request);
        if ($userForm->isSubmitted() && $userForm->isValid()) {
            $plainPassword = $user->getPassword();
            // find the encoder for the user
            $encoder = $app['security.encoder_factory']->getEncoder($user);
            // compute the encoded password
            $password = $encoder->encodePassword($plainPassword, $user->getSalt());
            $user->setPassword($password);
            $app['dao.user']->save($user);
            $app['session']->getFlashBag()->add('success', 'L\'utilisateur à bien été mis à jour');
            return $app->redirect('/admin');
        }
        return $app['twig']->render('user_form.html.twig', array(
            'title' => 'Editez l\'utilisateur',
            'userForm' => $userForm->createView()));
    }

    /**
     * Delete user controller.
     *
     * @param integer $id User id
     * @param Application $app Silex application
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteUserAction($id, Application $app) {
        // Delete all associated comments
        $app['dao.comment']->deleteAllByUser($id);
        // Delete the user
        $app['dao.user']->delete($id);
        $app['session']->getFlashBag()->add('success', 'L\'utilisateur à bien été supprimé');
        // Redirect to admin home page
        return $app->redirect($app['url_generator']->generate('admin'));
    }
}
