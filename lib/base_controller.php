<?php

class BaseController {

    public static function get_user_logged_in() {

        if (isset($_SESSION['user'])) {
            $id = $_SESSION['user'];

            $user = User::find($id);

            return $user;
        }
        return null;
    }

    public static function check_logged_in() {

        if (!isset($_SESSION['user'])) {
            Redirect::to('/login', array('error' => 'Kirjaudu sisään.'));
        }
    }

    public static function legit_action_check($ownerid) {

        if ($ownerid != self::get_user_logged_in()->id) {
            $recipes = Recipe::all();
            Redirect::to('/recipes', array('recipes' => $recipes));
        }
    }

    public static function logout() {

        $_SESSION['user'] = null;
        Redirect::to('/login', array('message' => 'Olet kirjautunut ulos.'));
    }

}
