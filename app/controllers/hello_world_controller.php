<?php

class HelloWorldController extends BaseController {

    public static function index() {
        // make-metodi renderöi app/views-kansiossa sijaitsevia tiedostoja
        echo 'Tämä ei ole etusivu!';
    }

    public static function sandbox() {

        View::make('helloworld.html');
    }

    public static function secret() {

        echo 'You found a secret!';
    }

    public static function recipes_list() {
        View::make('suunnitelmat/recipes_list.html');
    }

    public static function recipe_show() {
        View::make('suunnitelmat/recipe_show.html');
    }

    public static function recipe_edit() {
        View::make('suunnitelmat/recipe_edit.html');
    }

    public static function login() {
        View::make('suunnitelmat/login.html');
    }

    public static function full_courses() {
        View::make('suunnitelmat/full_courses.html');
    }

    public static function full_course_show() {
        View::make('suunnitelmat/full_course_show.html');
    }

    public static function full_course_edit() {
        View::make('suunnitelmat/full_course_edit.html');
    }

}
