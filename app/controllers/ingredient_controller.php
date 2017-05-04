<?php

class IngredientController extends BaseController {

    //näytetään tämän id:n raaka-aineen esittelysivu
    //numeerisuustarkistus siltä varalta, että käyttäjä kirjoittaa osoiteriville
    //id:n kohdalle jotain, joka ei sovi tietokantakyselyyn
    //näytetään "virhesivu", jos jokin menee pieleen
    public static function showIngredient($id) {

        self::parameterIsNumeric($id);

        $ingredient = Ingredient::find($id);
        $ri = recipeIngredient::findByIngredient($id);
        if ($ri) {
            View::make('ingredient/ingredient_show.html', array('ingredient' => $ingredient, 'id' => $id, 'recipe' => $ri->ruokalaji));
        } else {
            HelloWorldController::whoops();
        }
    }

    //näytetään tämän id:n raaka-aineen muokkaussivu, tarkistuksiin lukeutuu
    //numeerisuustarkistuksen lisäksi kirjautumistarkistus ja katsotaan myös,
    //saako kirjautunut käyttäjä muokata juuri tätä kohdetta, eli onko
    //hän sen lisääjä. Näitä on muissakin funktioissa
    public static function editIngredient($id) {

        self::check_logged_in();
        self::parameterIsNumeric($id);

        $ingredient = Ingredient::find($id);

        if ($ingredient) {
            self::legit_action_check($ingredient->kayttaja);
            View::make('ingredient/ingredient_edit.html', array('ingredient' => $ingredient));
        } else {
            HelloWorldController::whoops();
        }
    }

    //näytetään tämän id:n raaka-aineen muokkaussivu, jolla voi muokata nimeä ja määrää
    public static function editIngredientAmount($id) {

        self::check_logged_in();
        self::parameterIsNumeric($id);

        $ingredient = Ingredient::find($id);

        if ($ingredient) {
            self::legit_action_check($ingredient->kayttaja);
            $recipeingredient = recipeIngredient::findByIngredient($id);
            View::make('ingredient/ingredient_edit_amount.html', array('ingredient' => $ingredient, 'recipeingredient' => $recipeingredient));
        } else {
            HelloWorldController::whoops();
        }
    }

    //näytetään raaka-aineen lisäyssivu, raaka-aine lisätään tämän id:n ruokalajille
    //vaatii kirjautumisen, kuten kaikki check_logged_in()-funktiota kutsuvat
    public static function addIngredient($id) {

        self::check_logged_in();
        self::parameterIsNumeric($id);

        $recipe = Recipe::find($id);

        if ($recipe) {
            self::legit_action_check($recipe->kayttaja);
            View::make('ingredient/ingredient_new.html', array('recipe' => $recipe));
        } else {
            HelloWorldController::whoops();
        }
    }

    //jos käyttäjä antanut virheettömät syötteet JA tietokannasta ei löydy toista
    //samankaltaista raaka-ainetta, joka on lisätty samalle ruokalajille (ei voi spämmätä
    //lisää-painiketta), lisätään raaka-aine tietokantaan, muuten joko viedään
    //samalle sivulle muttei lisätä mitään tai renderöidään lisäyssivu virheilmoitusten
    //kera
    public static function store($id) {

        self::check_logged_in();

        $params = $_POST;

        $attributes = array(
            'nimi' => $params['nimi'],
            'hinta' => $params['hinta'],
            'ravitsemustiedot' => $params['ravitsemustiedot']
        );

        $ingredient = new Ingredient($attributes);

        $recipeingredient = new recipeIngredient(array(
            'ruokalaji' => $id,
            'nimi' => $params['nimi'],
            'maara' => $params['maara']
        ));

        $errors = array_merge($ingredient->errors(), $recipeingredient->errors());

        $test = self::recipeIngredientCheck($id, $attributes);

        if (!$test && count($errors) == 0) {
            $ingredient->save();
            $recipeingredient->raaka_aine = $ingredient->id;
            $recipeingredient->save();
            Redirect::to('/recipes/' . $id, array('message' => 'Raaka-aine lisätty!'));
        } else if (count($errors) == 0) {
            Redirect::to('/recipes/' . $id, array('message' => 'Raaka-aine lisätty!'));
        } else {
            $recipe = Recipe::find($id);
            View::make('ingredient/ingredient_new.html', array('errors' => $errors, 'ingredient' => $ingredient, 'recipeingredient' => $recipeingredient, 'recipe' => $recipe));
        }
    }

    //jos virheettömät parametrit, muokataan tämän raaka-aineen tietoja sekä
    //sitä ruokalajin ainesta, johon tämä raaka-aine liittyy, muuten
    //näytetään muokkaussivu virheilmoituksineen
    public static function updateIngredientInfo($id) {

        self::check_logged_in();

        $params = $_POST;

        $attributes = array(
            'nimi' => $params['nimi'],
            'hinta' => $params['hinta'],
            'ravitsemustiedot' => $params['ravitsemustiedot']
        );

        $ingredient = new Ingredient($attributes);
        $errors = $ingredient->errors();

        if (count($errors) == 0) {
            recipeIngredient::editName($id, $attributes['nimi']);
            Ingredient::edit($id, $attributes['nimi'], $attributes['hinta'], $attributes['ravitsemustiedot']);
            Redirect::to('/ingredients/' . $id, array('message' => 'Muutokset tallennettu.'));
        } else {
            $ingredient = Ingredient::find($id);
            View::make('ingredient/ingredient_edit.html', array('errors' => $errors, 'ingredient' => $ingredient));
        }
    }

    //jos virheettömät parametrit, muutetaan raaka-aineen nimeä ja määrää,
    //muutetaan siis sekä raaka-ainetta että ruokalajin ainesta
    //muussa tapauksessa näytetään muokkaussivu virheilmoituksineen
    public static function updateIngredientNameAndAmount($id) {

        self::check_logged_in();

        $params = $_POST;

        $riattributes = array('nimi' => $params['nimi'],
            'maara' => $params['maara']);

        $iattributes = array('nimi' => $params['nimi'],
            'hinta' => '1000e');

        $ri = new recipeIngredient($riattributes);
        $i = new Ingredient($iattributes);
        
        $errors = array_merge($ri->errors(), $i->errors());

        $recipeingredient = recipeIngredient::findByIngredient($id);

        if (count($errors) == 0) {
            recipeIngredient::editByIngredient($id, $params['nimi'], $params['maara']);
            Ingredient::editName($id, $params['nimi']);
            Redirect::to('/recipes/' . $recipeingredient->ruokalaji, array('message' => 'Muutokset tallennettu.'));
        } else {
            $ingredient = new Ingredient(array('id' => $id, 'nimi' => $params['nimi']));
            $recipeingredient = new RecipeIngredient(array('maara' => $params['maara']));
            View::make('ingredient/ingredient_edit_amount.html', array('errors' => $errors, 'ingredient' => $ingredient, 'recipeingredient' => $recipeingredient));
        }
    }

    //etsitään ruokalajin aines, johon tämä raaka-aine liittyy, ja poistetaan ensin se,
    //ja sitten mahdollisesti käyttämättömiksi jääneet raaka-aineet.
    //Uudelleenohjataan ruokalajin esittelysivulle.
    public static function destroyIngredient($id) {

        self::check_logged_in();

        $recipeingredient = recipeIngredient::findByIngredient($id);
        $idred = $recipeingredient->ruokalaji;

        recipeIngredient::destroyByIngredient($id);
        Ingredient::destroyUseless();

        $recipe = Recipe::find($idred);

        Redirect::to('/recipes/' . $idred, array('message' => 'Raaka-aine poistettu.', 'recipe' => $recipe));
    }

    //tarkistetaan, onko tietokannassa raaka-aine, joka on lisätty samalle ruokalajille
    //ja jolla on sama nimi ja käyttäjä. Jos on, palautetaan sen ilmentymä, muuten
    //null. Estää sen, että spämmäyslisääminen lisäisi tietokantaan useasti.
    public static function recipeIngredientCheck($id, $attributes) {

        $ret = null;

        $ingredients = recipeIngredient::findAllIngredientsByRecipe($id);

        foreach ($ingredients as $ingredient) {
            if ($ingredient->kayttaja == self::get_user_logged_in()->id) {
                if ($ingredient->nimi === $attributes['nimi']) {
                    $ret = $ingredient;
                }
            }
        }
        return $ret;
    }

}
