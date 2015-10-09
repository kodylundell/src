<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Silex\Application;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

$app = new Application();

$app['debug'] = true;

$notes = [
    '12345' => [
        'name' => 'Oswaldo',
        'body' => 'Medium',
        'tags' => 'outside, climbing, summer'
    ],

    '1' => [
        'name' => 'Juan',
        'body' => 'Large',
        'tags' => 'games, sports, movies'
    ]

];

$app->get('/', function () {
    return new Response('<h1>ReST API Candy Demo</h1>', 200);
});


$app->delete('/notes/{id}', function (Application $app, $id) use ($notes) {
    if (!isset($notes[$id])) {
        $app->abort(404, 'Note with ID {$id} does not exist.');
    }

    unset($notes{$id});

    return new Response(null, 204);
});


$app->put('/notes/{id}', function (Application $app, Request $request, $id) use ($notes) {
    $contentTypeValid = in_array(
        'application/json',
        $request->getAcceptableContentTypes()
    );

    if (!$contentTypeValid) {
        $app->abort(406, 'Client must accept content type of "application/json"');
    }

    $content = json_decode($request->getContent(), true);

    if(!isset($notes[$id])){
        $app->abort(404, 'id does not exist"');
    }
    $notes[$id] = [
        'name' => $content->name,
        'body' => $content->body,
        'tags' => $content->tags
    ];

    return new Response(
        json_encode(['result'=> 'success', 'status' => 'updated']),
        201,
        [
            'Content-Type' => 'application/json',
            'Location' => 'http://localhost:8888/notes/' . $id
        ]
    );
});

//create method
$app->post('/notes', function (Application $app, Request $request, $id) use ($notes) {
    $contentTypeValid = in_array(
        'application/json',
        $request->getAcceptableContentTypes()
    );

    if (!$contentTypeValid) {
        $app->abort(406, 'Client must accept content type of "application/json"');
    }

    $newId = uniqid();
    $content = json_decode($request->getContent(), true);

    $notes[$newId] = [
        'name' => $content->name,
        'body' => $content->body,
        'tags' => $content->tags
    ];

    return new Response(
        json_encode($notes),
        201,
        ['Location' => 'http://localhost:8888/notes/' . $newId]
    );
});

$app->get('/notes', function (Application $app) use ($notes){


	return new Response(
        json_encode($notes),
        200,
        ['content-type' => 'application/json']
    );
});

$app->get('/notes/{id}', function (Application $app, $id) use ($notes){
    if(!isset($notes[$id])){
        $app->abort(404, 'id does not exist"');
    }

    return new Response(
        json_encode($notes[$id]),
        200,
        ['content-type' => 'application/json']
    );
});

$user = [
    '1212' => [
        'firstname' => 'Bob',
        'lastname' => 'Child',
        'username' => 'travelman234',
        'password' => 'incorrect'
    ],

    '3434' => [
        'firstname' => 'Brett',
        'lastname' => 'Griffin',
        'username' => 'Codeman',
        'password' => 'coderiffic'
    ]

];

$app->delete('/user/{id}', function (Application $app, $id) use ($user) {
    if (!isset($user[$id])) {
        $app->abort(404, 'User with ID {$id} does not exist.');
    }

    unset($user{$id});

    return new Response(null, 204);
});


$app->put('/user/{id}', function (Application $app, Request $request, $id) use ($user) {
    $contentTypeValid = in_array(
        'application/json',
        $request->getAcceptableContentTypes()
    );

    if (!$contentTypeValid) {
        $app->abort(406, 'Client must accept content type of "application/json"');
    }

    $content = json_decode($request->getContent(), true);

    if(!isset($user[$id])){
        $app->abort(404, 'id does not exist"');
    }
    $user[$id] = [
        'firstname' => $content->firstname,
        'lastname' => $content->lastname,
        'username' => $content->username,
        'password' => $content->password
    ];

    return new Response(
        json_encode(['result'=> 'success', 'status' => 'updated']),
        201,
        [
            'Content-Type' => 'application/json',
            'Location' => 'http://localhost:8888/user/' . $id
        ]
    );
});

//create method
$app->post('/user', function (Application $app, Request $request, $id) use ($user) {
    $contentTypeValid = in_array(
        'application/json',
        $request->getAcceptableContentTypes()
    );

    if (!$contentTypeValid) {
        $app->abort(406, 'Client must accept content type of "application/json"');
    }

    $newId = uniqid();
    $content = json_decode($request->getContent(), true);

    $user[$newId] = [
        'firstname' => $content->firstname,
        'lastname' => $content->lastname,
        'username' => $content->username,
        'password' => $content->password
    ];

    return new Response(
        json_encode($user),
        201,
        ['Location' => 'http://localhost:8888/notes/' . $newId]
    );
});

$app->get('/user/{id}', function (Application $app, $id) use ($user){
    if(!isset($user[$id])){
        $app->abort(404, 'id does not exist"');
    }

    return new Response(
        json_encode($user[$id]),
        200,
        ['content-type' => 'application/json']
    );
});

$app->run();


