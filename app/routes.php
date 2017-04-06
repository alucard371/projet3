<?php

// Home page
$app->get('/', "microCMS\Controller\HomeController::indexAction")
    ->bind('home');

// Detailed info about an article
$app->match('/article/{id}', "microCMS\Controller\HomeController::articleAction")
    ->bind('article');

// Login form
$app->get('/login', "microCMS\Controller\HomeController::loginAction")
    ->bind('login');

// Admin zone
$app->get('/admin', "microCMS\Controller\AdminController::indexAction")
    ->bind('admin');

// Add a new article
$app->match('/admin/article/add', "microCMS\Controller\AdminController::addArticleAction")
    ->bind('admin_article_add');

// Edit an existing article
$app->match('/admin/article/{id}/edit', "microCMS\Controller\AdminController::editArticleAction")
    ->bind('admin_article_edit');

// Remove an article
$app->get('/admin/article/{id}/delete', "microCMS\Controller\AdminController::deleteArticleAction")
    ->bind('admin_article_delete');

// Edit an existing comment
$app->match('/admin/comment/{id}/edit', "microCMS\Controller\AdminController::editCommentAction")
    ->bind('admin_comment_edit');

// Remove a comment
$app->get('/admin/comment/{id}/delete', "microCMS\Controller\AdminController::deleteCommentAction")
    ->bind('admin_comment_delete');

// Add a user
$app->match('/admin/user/add', "microCMS\Controller\AdminController::addUserAction")
    ->bind('admin_user_add');

// Edit an existing user
$app->match('/admin/user/{id}/edit', "microCMS\Controller\AdminController::editUserAction")
    ->bind('admin_user_edit');

// Remove a user
$app->get('/admin/user/{id}/delete', "microCMS\Controller\AdminController::deleteUserAction")
    ->bind('admin_user_delete');

// API : get all articles
$app->get('/api/articles', "microCMS\Controller\ApiController::getArticlesAction")
    ->bind('api_articles');

// API : get an article
$app->get('/api/article/{id}', "microCMS\Controller\ApiController::getArticleAction")
    ->bind('api_article');

// API : create an article
$app->post('/api/article', "microCMS\Controller\ApiController::addArticleAction")
    ->bind('api_article_add');

// API : remove an article
$app->delete('/api/article/{id}', "microCMS\Controller\ApiController::deleteArticleAction")
    ->bind('api_article_delete');
