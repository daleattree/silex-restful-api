<?php
/**
 * Created by PhpStorm.
 * User: Dale Attree
 * Date: 2014/08/29
 * Time: 7:19 AM
 */

require_once __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

define('DEFAULT_USERNAME', 'afrihost');
define('DEFAULT_PASSWORD', 'CodingIsFun!');

$app = new Silex\Application();

$app->register(new Silex\Provider\SessionServiceProvider());

$app->post('/login', function (Request $request) use ($app) {
    $username = $request->get('username');
    $password = $request->get('password');

    $result = array('message' => 'Login successful');
    $code = 200;
    $app['session']->set('logged_in', true);
    if ($username != DEFAULT_USERNAME && $password != DEFAULT_PASSWORD) {
        $result = array('message' => 'Invalid login');
        $code = 401;
        $app['session']->set('logged_in', false);
    }

    return $app->json($result, $code);
});

$app->get('/logout', function () use ($app) {
    $app['session']->set('logged_in', false);
    $result = array('message' => 'Logged out');
    return $app->json($result, 200);
});

$app->get('/start', function () use ($app) {
    $result = array('message' => 'Hello World!');
    return $app->json($result, 200);
});

$app->get('/hello/{who}', function ($who) use ($app) {
    $result = array('message' => 'Hello ' . $who . '!');
    $code = 200;

    if (!$app['session']->get('logged_in')) {
        $result = array('message' => 'Access denied');
        $code = 401;
    }

    return $app->json($result, $code);
});

$app->error(function (\Exception $e, $code) use ($app) {
    $result = array('message' => $e->getMessage());
    return $app->json($result, $code);
});

$app->after(function (Request $request, Response $response) {
    $response->headers->set('Access-Control-Allow-Origin', '*');
});

$app->run();
