<?php

class RecipeController extends BaseController {

    //näyttää etusivun, eli reseptilistauksen
    public static function index() {

        $recipes = Recipe::all();

        View::make('recipe/recipes_list.html', array('recipes' => $recipes));
    }

    //näyttää tämän id:n ruokalajin esittelysivun
    public static function showRecipe($id) {

        $recipe = Recipe::find($id);
        $ingredients = recipeIngredient::findByRecipe($id);

        View::make('recipe/recipe_show.html', array('recipe' => $recipe, 'ingredients' => $ingredients));
    }

    //näyttää tämän id:n ruokalajin muokkaussivun
    public static function editRecipe($id) {

        self::check_logged_in();

        $recipe = Recipe::find($id);

        View::make('recipe/recipe_edit.html', array('recipe' => $recipe));
    }

    //näyttää tämän id:n ruokalajin reseptin (valmistusohjeiden)
    //muokkaussivun
    public static function editRecipeInstructions($id) {

        self::check_logged_in();

        $recipe = Recipe::find($id);
        $ingredients = recipeIngredient::findByRecipe($id);
        $instructions = $recipe->resepti;

        View::make('recipe/recipe_instructions_edit.html', array('recipe' => $recipe, 'ingredients' => $ingredients, 'instructions' => $instructions));
    }

    //näyttää uuden ruokalajin lisäyssivun
    public static function newRecipe() {

        self::check_logged_in();

        View::make('recipe/recipe_new.html');
    }

    //jos käyttäjä on antanut lisättävälle ruokalajille virheettömät
    //parametrit JA tietokannasta ei jo löydy ruokalajia, jolla on samat
    //tiedot (lisää-painiketta ei ole spämmitty), lisätään ruokalaji
    //tietokantaan, muussa tapauksessa näytetään listaussivu lisäämättä
    //mitään tai lisäyssivu, jolla virheilmoitukset
    public static function store() {

        self::check_logged_in();

        $params = $_POST;

        $attributes = array(
            'nimi' => $params['nimi'],
            'ateriatyyppi' => $params['ateriatyyppi'],
            'paaraaka_aine' => $params['paaraaka_aine'],
            'vaikeustaso' => $params['vaikeustaso'],
            'valmistusaika' => $params['valmistusaika'],
            'resepti' => 'Reseptiä ei ole vielä kirjoitettu.'
        );

        $test = Recipe::existingRecipeCheck($attributes);

        $recipe = new Recipe($attributes);

        $errors = $recipe->errors();

        if (count($errors) == 0 && !$test) {
            $recipe->save();
            Redirect::to('/recipes/' . $recipe->id, array('message' => 'Uusi ruokalaji lisätty! Jatka lisäämällä raaka-aineita ja kirjoittamalla resepti.'));
        } else if (count($errors) == 0 && $test) {
            Redirect::to('/recipes/' . $test->id, array('message' => 'Uusi ruokalaji lisätty! Jatka lisäämällä raaka-aineita ja kirjoittamalla resepti.'));
        } else {
            View::make('recipe/recipe_new.html', array('errors' => $errors, 'attributes' => $attributes));
        }
    }

    //jos käyttäjä on antanut virheettömät parametrit, muokataan tämän id:n
    //ruokalajin tietoja, muuten renderöidään muokkaussivu virheilmoituksineen.
    public static function updateRecipeInfo($id) {

        self::check_logged_in();

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

    //jos reseptissä ei ole mitään häikkää (ei ole liian pitkä), muutetaan
    //tämän id:n ruokalajin reseptiä ja viedään takaisin sen sivulle, muuten
    //renderöidään muokkausnäkymä virheilmoituksien kanssa.
    public static function storeRecipe($id) {

        self::check_logged_in();

        $params = $_POST;

        $instructions = $params['resepti'];
        $recipe = new Recipe(array('nimi' => 'kek',
            'paaraaka_aine' => 'lel',
            'valmistusaika' => '1000h',
            'resepti' => $instructions));
        $errors = $recipe->errors();

        if (count($errors) == 0) {
            Recipe::saveRecipe($id, $instructions);
            Redirect::to('/recipes/' . $id, array('message' => 'Reseptiä päivitetty.'));
        } else {
            $recipe = Recipe::find($id);
            $ingredients = recipeIngredient::findByRecipe($id);
            View::make('recipe/recipe_instructions_edit.html', array('errors' => $errors, 'recipe' => $recipe, 'ingredients' => $ingredients, 'instructions' => $instructions));
        }
    }

    //näytetään tämän id:n ruokalajin poistonvahvistussivu
    public static function confirmDeletion($id) {

        self::check_logged_in();

        $recipe = Recipe::find($id);

        View::make('recipe/recipe_confirm_deletion.html', array('recipe' => $recipe));
    }

    //poistetaan ensin ruokalajin aines, johon tämä ruokalaji liittyy, sitten
    //tämä ruokalaji, ja lopuksi tälle ruokalajille lisätyt raaka-aineet.
    //Uudelleenohjataan ruokalajin esittelysivulle.
    public static function destroyRecipe($id) {

        self::check_logged_in();

        recipeIngredient::destroyByRecipe($id);
        Recipe::destroy($id);
        Ingredient::destroyByRecipe();

        Redirect::to('/recipes', array('message' => 'Ruokalaji poistettu.'));
    }

}
