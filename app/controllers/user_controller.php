<?php

class UserController extends BaseController {

    //näytetään kirjautumissivu.
    public static function login() {

        View::make('user/login.html');
    }

    //katsotaan, löytyykö tietokannasta käyttäjä näillä parametreillä - jos ei,
    //näytetään kirjautumissivu virheilmoituksen kanssa, muuten aloitetaan
    //käyttäjän sessio ja viedään etusivulle eli reseptilistaussivulle.
    public static function handle_login() {

        $params = $_POST;

        $user = User::authenticate($params['username'], $params['password']);

        if (!$user) {
            View::make('user/login.html', array('error' => 'Väärä käyttäjätunnus tai salasana!', 'username' => $params['username']));
        } else {
            $_SESSION['user'] = $user->id;
            Redirect::to('/recipes', array('message' => 'Tervetuloa takaisin, ' . $user->nimi . '!'));
        }
    }

}
