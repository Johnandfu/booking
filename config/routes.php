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
use Hyperf\HttpServer\Router\Router;

Router::get('/', [HomeController::class, 'index']);
Router::get('/about', [HomeController::class, 'about']);
Router::get('/contact', [HomeController::class, 'contact']);
Router::post('/contact', [HomeController::class, 'submitContact']);
Router::post('/contact/message', [HomeController::class, 'submitContact']);
Router::get('/favicon.ico', static function () {
    return '';
});
Router::get('/{categorySlug}', [CategoryController::class, 'index']);
Router::get('/{categorySlug}/{id}', [CategoryController::class, 'show']);
