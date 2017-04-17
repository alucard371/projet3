<?php
/**
 * Created by PhpStorm.
 * User: moi
 * Date: 13/04/2017
 * Time: 10:46
 */

public function commentAction($id, Request $request, Application $app) {
    $comment = $app['dao.comment']->find($id);
    $commentFormView = null;

    if ($app['security.authorization_checker']->isGranted('IS_AUTHENTICATED_FULLY')) {
        // A user is fully authenticated : he can add comments
        $comment = new Comment();
        $comment->setComment($comment);
        $user = $app['user'];
        $comment->setAuthor[$user];
        $commentForm = $app['form.factory']->create(CommentType::class, $comment);
        $commentForm->handleRequest($request);
        if ($commentForm->isSubmitted() && $commentForm->isValid()) {
            $app['dao.comment']->save($comment);
            $app['session']->getFlashBag()->add('success', 'Votre commentaire à bien été ajouté');
    }
        $commentFormView = $commentForm->createView();
    }

    $comments = $app['dao.comment']->findAllByComment($id);

    return $app['twig']->render('comment.html.twig', array(
        'comment' => $comment,
        'comments' => $comments,
        'commentForm' => $commentFormView
    ));

};

/**
 * @param \microCMS\Domain\Comment
 */
public function saveComment(Comment $comment) {
    $commentData = array(
        'parent_id' => $comment->getComment()->getId(),
        'usr_id' => $comment->getAuthor()->getId(),
        'com_content' => $comment->getContent()
    );
    if ($comment->getId()) {
        // The comment has already been saved : update it
        $this->getDb()->update('t_comment', $commentData, array('com_id' => $comment->getId()));
    } else {
        // The comment has never been saved : insert it
        $this->getDb()->insert('t_comment', $commentData);
        // Get the id of the newly created comment and set it on the entity.
        $id = $this->getDb()->lastInsertId();
        $comment->setId($id);
    }
}

public function findAllByComment($commentId) {
    // the associated comment is retrieved only once
    $comment = $this->commentDAO->find($commentId);

    // art_id is not selected by the SQL query
    // The article won't be retrieved during domain objet construction
    $sql = "select com_id, com_content, usr_id from t_comment where art_id=? order by com_id";
    $result = $this->getDb()->fetchAll($sql, array($commentId));

    // Convert query result to an array of domain objects
    $comments = array();
    foreach ($result as $row) {
        $comId = $row['com_id'];
        $comment = $this->buildDomainObject($row);
        // The associated comment is defined for the constructed comment
        $comment->setComment($commentId);
        $comments[$comId] = $comment;
    }
    return $comments;
}

$commentForm = $app['form.factory']->create(CommentType::class, $comment);
$checkboxForm = $app['form.factory']->create(CheckboxType::class, $checkbox);
$commentForm->handleRequest($request);
$checkboxForm->handleRequest($request);
if ($commentForm->isSubmitted() && $commentForm->isValid()) {
    $app['dao.comment']->save($comment);
    $app['session']->getFlashBag()->add('success', 'Votre commentaire à bien été ajouté');
    return $app->redirect($request->getUri());}

if ($checkboxForm->isSubmitted() && $checkboxForm->isValid()) {
    $app['dao.comment']->moderate($checkbox);
    $app['session']->getFlashBag()->add('success', 'Votre commentaire à bien été ajouté');
    return $app->redirect($request->getUri());}
}
$checkboxFormView = $checkboxForm->createView();

'checkboxForm' => $checkboxFormView));

/**
 * Comment details controller.
 * @param integer $id Comment id
 * @param integer $id Article id
 *
 * @param Request $request Incoming request
 * @param Application $app Silex application
 * @return \Symfony\Component\HttpFoundation\RedirectResponse
 */
    public function testAction($articleId, $commentId, Request $request, Application $app) {
    $article = $app['dao.article']->find($articleId);
    $comment = $app['dao.comment']->find($commentId);

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

    $comments = $app['dao.comment']->findAllByArticle($commentId);


    return $app['twig']->render('comment.html.twig', array(
        'article' => $article,
        'comments' => $comments,
        'comment' => $comment,
        'commentForm' => $commentFormView
    ));

}

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
