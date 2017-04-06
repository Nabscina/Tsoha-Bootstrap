<?php

class RecipeController extends BaseController {

    public static function index() {

        $recipes = Recipe::all();

        View::make('recipe/recipes_list.html', array('recipes' => $recipes));
    }

    public static function showRecipe($id) {

        $recipe = Recipe::find($id);
        $ingredients = recipeIngredient::findByRecipe($id);

        View::make('recipe/recipe_show.html', array('recipe' => $recipe, 'ingredients' => $ingredients));
    }

    public static function editRecipe($id) {

        $recipe = Recipe::find($id);

        View::make('recipe/recipe_edit.html', array('recipe' => $recipe));
    }

    public static function editRecipeInstructions($id) {

        $recipe = Recipe::find($id);
        $ingredients = recipeIngredient::findByRecipe($id);

        View::make('recipe/recipe_instructions_edit.html', array('recipe' => $recipe, 'ingredients' => $ingredients));
    }

    public static function newRecipe() {

        View::make('recipe/recipe_new.html');
    }

    public static function store() {

        $params = $_POST;

        $attributes = array(
            'nimi' => $params['nimi'],
            'ateriatyyppi' => $params['ateriatyyppi'],
            'paaraaka_aine' => $params['paaraaka_aine'],
            'vaikeustaso' => $params['vaikeustaso'],
            'valmistusaika' => $params['valmistusaika'],
            'resepti' => 'Reseptiä ei ole vielä kirjoitettu.'
        );

        $recipe = new Recipe($attributes);

        $errors = $recipe->errors();

        if (count($errors) == 0) {
            $recipe->save();
            Redirect::to('/recipes/' . $recipe->id, array('message' => 'Uusi ruokalaji lisätty! Jatka lisäämällä raaka-aineita ja kirjoittamalla resepti.'));
        } else {
            View::make('recipe/recipe_new.html', array('errors' => $errors, 'attributes' => $attributes));
        }
    }

    public static function updateRecipeInfo($id) {

        $params = $_POST;

        $recipe = array(
            'nimi' => $params['nimi'],
            'ateriatyyppi' => $params['ateriatyyppi'],
            'paaraaka_aine' => $params['paaraaka_aine'],
            'vaikeustaso' => $params['vaikeustaso'],
            'valmistusaika' => $params['valmistusaika']
        );

        $r = new Recipe($recipe);

        $errors = $r->errors();

        $r->destroy($r->id);

        if (count($errors) == 0) {
            Recipe::updateInfo($id, $recipe);
            Redirect::to('/recipes/' . $id, array('message' => 'Muutokset tallennettu.'));
        } else {
            $recipe['id'] = $id;
            View::make('recipe/recipe_edit.html', array('errors' => $errors, 'recipe' => $recipe));
        }
    }

    public static function storeRecipe($id) {

        $params = $_POST;

        $recipe = $params['resepti'];

        Recipe::saveRecipe($id, $recipe);

        Redirect::to('/recipes/' . $id, array('message' => 'Reseptiä päivitetty.'));
    }

    public static function confirmDeletion($id) {

        $recipe = Recipe::find($id);

        View::make('recipe/recipe_confirm_deletion.html', array('recipe' => $recipe));
    }

    public static function destroyRecipe($id) {

        recipeIngredient::destroyByRecipe($id);
        Recipe::destroy($id);
        Ingredient::destroyByRecipe();

        Redirect::to('/recipes', array('message' => 'Ruokalaji poistettu.'));
    }

}
