<?php

class HelloWorldController extends BaseController {

    public static function index() {
        // make-metodi renderöi app/views-kansiossa sijaitsevia tiedostoja
        echo 'Tämä ei ole etusivu!';
    }

    public static function sandbox() {

        $ruoka = Recipe::find(45);
        $ruoat = Recipe::all();
        
        Kint::dump($ruoat);
        Kint::dump($ruoka);
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
