<?php

$routes->get('/', function() {
    RecipeController::index();
});

$routes->post('/recipes', function() {
    RecipeController::store();
});

$routes->post('/recipes/:id', function($id) {
    IngredientController::store($id);
});

$routes->get('/recipes/newrecipe', function() {
    RecipeController::newRecipe();
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

$routes->get('/recipes/:id/confirm_deletion', function($id) {
    RecipeController::confirmDeletion($id);
});

$routes->post('/recipes/:id/destroy', function($id) {
    RecipeController::destroyRecipe($id);
});

$routes->post('/recipes/:id/edit', function($id) {
    RecipeController::updateRecipeInfo($id);
});

$routes->get('/recipes/:id/edit', function($id) {
    RecipeController::editRecipe($id);
});

$routes->get('/ingredients/:id', function($id) {
    IngredientController::showIngredient($id);
});

$routes->post('/ingredients/:id/edit', function($id) {
    IngredientController::updateIngredientInfo($id);
});

$routes->get('/ingredients/:id/editamount', function($id) {
    IngredientController::editIngredientAmount($id);
});

$routes->post('/ingredients/:id/editamount', function($id) {
    IngredientController::updateIngredientNameAndAmount($id);
});

$routes->post('/ingredients/:id/destroy', function($id) {
    IngredientController::destroyIngredient($id);
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
    UserController::login();
});

$routes->post('/login', function() {
    UserController::handle_login();
});

$routes->post('/logout', function() {
    UserController::logout();
});
