<?php

class UserController extends BaseController {

    //näytetään kirjautumissivu.
    public static function login() {

        View::make('user/login.html');
    }

    public static function register() {

        if (self::get_user_logged_in()) {
            RecipeController::index();
        }

        View::make('user/register.html');
    }

    public static function handleRegistration() {

        $params = $_POST;
        $user = new User(array('nimi' => $params['username'],
            'salasana' => $params['password']
        ));

        $errors = $user->errors();

        if (count($errors) == 0) {
            $user->save();
            Redirect::to('/login', array('message' => 'Rekisteröityminen onnistui, voit nyt kirjautua sisään.'));
        } else {
            View::make('user/register.html', array('errors' => $errors, 'username' => $user->nimi));
        }
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
            Redirect::to('/recipes', array('message' => 'Hei ' . $user->nimi . '!'));
        }
    }

}
