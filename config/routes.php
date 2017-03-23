<?php

$routes->get('/', function() {
    HelloWorldController::recipes_list();
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

$routes->get('/login', function() {
    HelloWorldController::login();
});

$routes->get('/fullcourses', function() {
    HelloWorldController::full_courses();
});

$routes->get('/fullcourses/1', function() {
    HelloWorldController::full_course_show();
});

$routes->get('/fullcourses/1/edit', function() {
    HelloWorldController::full_course_edit();
});
