<?php

$routes->get('/', function() {
    RecipeController::index();
});

$routes->get('/hiekkalaatikko', function() {
    HelloWorldController::sandbox();
});

$routes->get('/supersecret', function() {
    HelloWorldController::secret();
});

$routes->get('/recipes', function() {
    RecipeController::index();
});

$routes->get('/recipes/1', function() {
    HelloWorldController::recipe_show();
});

$routes->get('/recipes/1/edit', function() {
    HelloWorldController::recipe_edit();
});

$routes->get('/login', function() {
    HelloWorldController::login();
});
