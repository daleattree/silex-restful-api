<?php
/**
 * Created by PhpStorm.
 * User: Dale Attree
 * Date: 2014/08/29
 * Time: 7:19 AM
 */

require_once __DIR__.'/../vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;

define('DEFAULT_USERNAME', 'joburgphp');
define('DEFAULT_PASSWORD', '@hackathon');

$app = new Silex\Application();

$app->post('/login', function (Request $request) use ($app) {
    $username = $request->get('username');
    $password = $request->get('password');

    $result = array('message' => 'Login successful');
    $code = 200;
    if($username != DEFAULT_USERNAME && $password != DEFAULT_PASSWORD){
        $result = array('message' => 'Invalid login');
        $code = 404;
    }

    return $app->json($result, $code);
});

$app->get('/start', function() use ($app){
   $result = array('message' => 'Hello World!');
   return $app->json($result, 200);
});

$app->get('/hello/{who}', function($who) use ($app){
   $result = array('message' => 'Hello ' . $who . '!');
   return $app->json($result, 200);
});

$app->error(function (\Exception $e, $code) use ($app) {
    $result = array('message' => $e->getMessage());
    return $app->json($result, $code);
});

$app->run();