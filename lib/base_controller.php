<?php

class BaseController {

    //palauttaa kirjautuneen käyttäjän oliona, jos hän ylipäätään
    //on kirjautunut.
    public static function get_user_logged_in() {

        if (isset($_SESSION['user'])) {
            $id = $_SESSION['user'];

            $user = User::find($id);

            return $user;
        }
        return null;
    }

    //katsotaan, onko käyttäjä kirjautunut sisään. Käytetään tietenkin
    //niissä yhteyksissä, kun pitää olla kirjautunut.
    public static function check_logged_in() {

        if (!isset($_SESSION['user'])) {
            Redirect::to('/login', array('error' => 'Kirjaudu sisään.'));
        }
    }

    //katsotaan, onko kirjautuneella käyttäjällä oikeus johonkin
    //toimintoon. Parametrinä yleensä kohteen lisänneen käyttäjän
    //id. Jos ei oikeutta, viedään etusivulle. Napit ovat kuitenkin
    //piilossa, jos niitä ei saa käyttää, joten tämä on lähinnä sitä
    //varten, että käyttäjä sählää osoiterivillä.
    public static function legit_action_check($ownerid) {

        if ($ownerid != self::get_user_logged_in()->id) {
            $recipes = Recipe::all();
            Redirect::to('/recipes', array('recipes' => $recipes));
        }
    }

    //katsotaan, onko parametri $p numeerinen, jotta missään ei räjähdä
    //kun se liitetään osaksi tietokantakyselyä, jossa sen oletetaan
    //olevan numeerinen.
    public static function parameterIsNumeric($p) {

        if (!is_numeric($p)) {
            HelloWorldController::whoops();
        }
    }

    //kirjaa käyttäjän ulos.
    public static function logout() {

        $_SESSION['user'] = null;
        Redirect::to('/login', array('message' => 'Olet kirjautunut ulos.'));
    }

}
