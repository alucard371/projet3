<?php

// Home page
$app->get('/', "Projet3\Controller\HomeController::indexAction")
    ->bind('home');

// Detailed info about an article
$app->match('/article/{id}', "Projet3\Controller\HomeController::articleAction")
    ->bind('article');

// Detailed info about an comment
$app->match('/article/{articleId}/comment/{commentId}', "Projet3\Controller\HomeController::commentAction")
    ->bind('comment');

// Moderate comment
$app->match('/article/{articleId}/comment/{commentId}/moderation', "Projet3\Controller\HomeController::commentModerationAction")
    ->bind('moderation');

// Accept comment
$app->match('/article/comment/{commentId}/accepter', "Projet3\Controller\HomeController::commentAcceptationAction")
    ->bind('accept');

// Login form
$app->get('/login', "Projet3\Controller\HomeController::loginAction")
    ->bind('login');

// Admin zone
$app->get('/admin', "Projet3\Controller\AdminController::indexAction")
    ->bind('admin');

// Add a new article
$app->match('/admin/article/ajouter', "Projet3\Controller\AdminController::addArticleAction")
    ->bind('admin_article_add');

// Edit an existing article
$app->match('/admin/article/{id}/editer', "Projet3\Controller\AdminController::editArticleAction")
    ->bind('admin_article_edit');

// Remove an article
$app->get('/admin/article/{id}/supprimer', "Projet3\Controller\AdminController::deleteArticleAction")
    ->bind('admin_article_delete');

// Edit an existing comment
$app->match('/admin/comment/{id}/editer', "Projet3\Controller\AdminController::editCommentAction")
    ->bind('admin_comment_edit');

// Remove a comment
$app->get('/admin/comment/{id}/supprimer', "Projet3\Controller\AdminController::deleteCommentAction")
    ->bind('admin_comment_delete');

// Add a user
$app->match('/admin/user/ajouter', "Projet3\Controller\AdminController::addUserAction")
    ->bind('admin_user_add');

// Edit an existing user
$app->match('/admin/user/{id}/editez', "Projet3\Controller\AdminController::editUserAction")
    ->bind('admin_user_edit');

// Remove a user
$app->get('/admin/user/{id}/supprimer', "Projet3\Controller\AdminController::deleteUserAction")
    ->bind('admin_user_delete');
