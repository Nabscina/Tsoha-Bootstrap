<?php

$routes->get('/', function() {
    HelloWorldController::index();
});

$routes->get('/hiekkalaatikko', function() {
    HelloWorldController::sandbox();
});

$routes->get('/supersecret', function() {
    HelloWorldController::secret();
});

$routes->get('/recipes', function() {
    HelloWorldController::recipes_list();
});

$routes->get('/recipes/1', function() {
    HelloWorldController::recipe_show();
});

$routes->get('/recipes/1/edit', function() {
    HelloWorldController::recipe_edit();
});
