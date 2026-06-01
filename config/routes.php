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
use App\Http\Index\Controller\HomeController;
use App\Http\Index\Controller\CategoryController;
use App\Http\Index\Controller\NewsController;
use App\Http\Index\Controller\ServiceController;
use Hyperf\HttpServer\Router\Router;

Router::get('/', [HomeController::class, 'index']);
Router::get('/about', [HomeController::class, 'about']);
Router::get('/contact', [HomeController::class, 'contact']);
Router::post('/contact', [HomeController::class, 'submitContact']);
Router::post('/contact/message', [HomeController::class, 'submitContact']);
Router::get('/news', [NewsController::class, 'index']);
Router::get('/news/{slug}', [NewsController::class, 'show']);
Router::get('/services', [ServiceController::class, 'index']);
Router::get('/services/{slug}', [ServiceController::class, 'show']);
Router::get('/favicon.ico', static function () {
    return '';
});
Router::get('/{categorySlug}', [CategoryController::class, 'index']);
Router::get('/{categorySlug}/{slug}', [CategoryController::class, 'show']);
