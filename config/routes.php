<?php

$routes->get('/', function() {
    RecipeController::index();
});

$routes->post('/recipes', function() {
    RecipeController::store();
});

$routes->get('/recipes/newrecipe', function() {
    RecipeController::newRecipe();
});

$routes->post('/recipes/:id', function($id) {
    IngredientController::store($id);
});

$routes->get('/recipes/:id/addingredient', function($id) {
    IngredientController::addIngredient($id);
});

$routes->post('/recipes/instr/:id', function($id) {
    RecipeController::storeRecipe($id);
});

$routes->get('/recipes/:id/editinstructions', function($id) {
    RecipeController::editRecipeInstructions($id);
});

$routes->get('/recipes/:id', function($id) {
    RecipeController::showRecipe($id);
});

$routes->get('/recipes/:id/edit', function($id) {
    RecipeController::editRecipe($id);
});

$routes->get('/ingredients/:id', function($id) {
    IngredientController::showIngredient($id);
});

$routes->get('/ingredients/:id/edit', function($id) {
    IngredientController::editIngredient($id);
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

$routes->get('/login', function() {
    HelloWorldController::login();
});
