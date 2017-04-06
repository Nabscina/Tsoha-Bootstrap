<?php

class IngredientController extends BaseController {

    public static function showIngredient($id) {

        $ingredient = Ingredient::find($id);

        View::make('ingredient/ingredient_show.html', array('ingredient' => $ingredient, 'id' => $id));
    }

    public static function editIngredient($id) {

        $ingredient = Ingredient::find($id);

        View::make('ingredient/ingredient_edit.html', array('ingredient' => $ingredient));
    }

    public static function addIngredient($id) {

        $recipe = Recipe::find($id);

        View::make('ingredient/ingredient_new.html', array('recipe' => $recipe));
    }

    public static function store($id) {

        $params = $_POST;

        $ingredient = new Ingredient(array(
            'nimi' => $params['nimi'],
            'hinta' => $params['hinta'],
            'ravitsemustiedot' => $params['ravitsemustiedot']
        ));

        $ingredient->save();

        $ingredientId = $ingredient->id;

        $recipeIngredient = new recipeIngredient(array(
            'ruokalaji' => $id,
            'raaka_aine' => $ingredientId,
            'nimi' => $params['nimi'],
            'maara' => $params['maara']
        ));

        $recipeIngredient->save();

        Redirect::to('/recipes/' . $id, array('message' => 'Raaka-aine lisätty!'));
    }

    public static function updateIngredientInfo($id) {
        
    }

    public static function destroyIngredient($id) {
        
        $ruokalajinaines = recipeIngredient::findByIngredient($id);

        recipeIngredient::destroyByIngredient($id);
        Ingredient::destroy($id);

        Redirect::to('/recipes/' . $ruokalajinaines->ruokalaji, array('message' => 'Raaka-aine poistettu.'));
    }

}
