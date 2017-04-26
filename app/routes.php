<?php

// Home page
$app->get('/', "MicroCMS\Controller\HomeController::indexAction")
    ->bind('home');

// Detailed info about an article
$app->match('/article/{id}', "MicroCMS\Controller\HomeController::articleAction")
    ->bind('article');

// Detailed info about an comment
$app->match('/article/{articleId}/comment/{commentId}', "MicroCMS\Controller\HomeController::commentAction")
    ->bind('comment');

// Moderate comment
$app->match('/article/{articleId}/comment/{commentId}/moderation', "MicroCMS\Controller\HomeController::commentModerationAction")
    ->bind('moderation');

// Accept comment
$app->match('/article/comment/{commentId}/acceptez', "MicroCMS\Controller\HomeController::commentAcceptationAction")
    ->bind('accept');

// Login form
$app->get('/login', "MicroCMS\Controller\HomeController::loginAction")
    ->bind('login');

// Admin zone
$app->get('/admin', "MicroCMS\Controller\AdminController::indexAction")
    ->bind('admin');

// Add a new article
$app->match('/admin/article/ajouter', "MicroCMS\Controller\AdminController::addArticleAction")
    ->bind('admin_article_add');

// Edit an existing article
$app->match('/admin/article/{id}/editez', "MicroCMS\Controller\AdminController::editArticleAction")
    ->bind('admin_article_edit');

// Remove an article
$app->get('/admin/article/{id}/supprimer', "MicroCMS\Controller\AdminController::deleteArticleAction")
    ->bind('admin_article_delete');

// Edit an existing comment
$app->match('/admin/comment/{id}/editez', "MicroCMS\Controller\AdminController::editCommentAction")
    ->bind('admin_comment_edit');

// Remove a comment
$app->get('/admin/comment/{id}/supprimer', "MicroCMS\Controller\AdminController::deleteCommentAction")
    ->bind('admin_comment_delete');

// Add a user
$app->match('/admin/user/ajouter', "MicroCMS\Controller\AdminController::addUserAction")
    ->bind('admin_user_add');

// Edit an existing user
$app->match('/admin/user/{id}/editez', "MicroCMS\Controller\AdminController::editUserAction")
    ->bind('admin_user_edit');

// Remove a user
$app->get('/admin/user/{id}/supprimer', "MicroCMS\Controller\AdminController::deleteUserAction")
    ->bind('admin_user_delete');

// API : get all articles
$app->get('/api/articles', "MicroCMS\Controller\ApiController::getArticlesAction")
    ->bind('api_articles');

// API : get an article
$app->get('/api/article/{id}', "MicroCMS\Controller\ApiController::getArticleAction")
    ->bind('api_article');

// API : create an article
$app->post('/api/article', "MicroCMS\Controller\ApiController::addArticleAction")
    ->bind('api_article_add');

// API : remove an article
$app->delete('/api/article/{id}', "MicroCMS\Controller\ApiController::deleteArticleAction")
    ->bind('api_article_delete');
