<?php

class HelloWorldController extends BaseController {

    public static function index() {
        // make-metodi renderöi app/views-kansiossa sijaitsevia tiedostoja
        echo 'Tämä ei ole etusivu!';
    }

    //sivu, jolle päädytään, kun käyttäjä yrittää tehdä jotain hassua
    //osoiterivillä. Ei kovin informatiivinen, koska itse olen päässyt
    //tälle sivulle ainoastaan osoiterivin kautta hassuilla parametreillä.
    public static function whoops() {

        View::make('helloworld.html');
    }

    public static function sandbox() {

        $recipe = new Recipe(array(
            'nimi' => '1234',
            'ateriatyyppi' => 'joo',
            'paaraaka_aine' => 'ei',
            'vaikeustaso' => 'aika vaikee',
            'valmistusaika' => '55 min',
            'resepti' => 'kek'
        ));
        $errors = $recipe->errors();

        Kint::dump($errors);
    }

    public static function secret() {

        echo 'You found a secret!';
    }

    public static function recipes_list() {
        View::make('recipe/recipes_list.html');
    }

    public static function recipe_show() {
        View::make('recipe/recipe_show.html');
    }

    public static function recipe_edit() {
        View::make('recipe/recipe_edit.html');
    }

    public static function login() {
        View::make('login.html');
    }

}
