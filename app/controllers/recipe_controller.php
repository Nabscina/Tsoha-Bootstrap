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
        $ingredients = recipeIngredient::findByRecipe($id);

        View::make('recipe/recipe_edit.html', array('recipe' => $recipe, 'ingredients' => $ingredients));
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

        $recipe = new Recipe(array(
            'nimi' => $params['nimi'],
            'ateriatyyppi' => $params['ateriatyyppi'],
            'paaraaka_aine' => $params['paaraaka_aine'],
            'vaikeustaso' => $params['vaikeustaso'],
            'valmistusaika' => $params['valmistusaika'],
            'resepti' => 'Reseptiä ei ole vielä kirjoitettu.'
        ));

        $recipe->save();

        Redirect::to('/recipes/' . $recipe->id, array('message' => 'Uusi ruokalaji lisätty! Jatka lisäämällä raaka-aineita ja kirjoittamalla resepti.'));
    }

    public static function storeRecipe($id) {

        $params = $_POST;

        $recipe = $params['resepti'];

        Recipe::saveRecipe($id, $recipe);

        Redirect::to('/recipes/' . $id, array('message' => 'Reseptiä päivitetty.'));
    }

}
