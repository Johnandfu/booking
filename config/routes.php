<?php

declare(strict_types=1);
/**
 * This file is part of MineAdmin.
 *
 * @link     https://www.mineadmin.com
 * @document https://doc.mineadmin.com
 * @contact  root@imoi.cn
 * @license  https://github.com/mineadmin/MineAdmin/blob/master/LICENSE
 */
use App\Http\Index\Controller\ArticleController;
use App\Http\Index\Controller\HomeController;
use App\Http\Index\Controller\PageController;
use Hyperf\HttpServer\Router\Router;

Router::get('/', [HomeController::class, 'index']);
Router::get('/about', [HomeController::class, 'about']);
Router::get('/contact', [HomeController::class, 'contact']);
Router::post('/contact', [HomeController::class, 'submitContact']);
Router::post('/contact/message', [HomeController::class, 'submitContact']);
Router::get('/news', [ArticleController::class, 'news']);
Router::get('/service', [ArticleController::class, 'service']);
Router::get('/category/{slug}', [ArticleController::class, 'category']);
Router::get('/article/{slug}', [ArticleController::class, 'show']);
Router::get('/page/{slug}', [PageController::class, 'show']);

Router::get('/favicon.ico', static function () {
    return '';
});
