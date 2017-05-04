<?php

class ListController extends BaseController {

    //näytetään kirjautuneen käyttäjän ostoslista
    public static function shoppingList() {

        self::check_logged_in();
        $shoppinglist = ShoppingList::find(self::get_user_logged_in()->id);
        $items = ShoppingList::findAll($shoppinglist->id);

        View::make('list/shoppinglist.html', array('items' => $items));
    }

    //lisätään tämän id:n raaka-aine ostoslistalle, mikäli se ei siellä jo ole
    public static function add($id) {

        self::check_logged_in();

        $shoppinglist = ShoppingList::find(self::get_user_logged_in()->id);

        if (!ShoppingList::findExisting($shoppinglist->id, $id)) {
            ShoppingList::addToList($id, $shoppinglist->id);
        }

        $recipe = recipeIngredient::findByIngredient($id)->ruokalaji;

        Redirect::to('/ingredients/' . $id, array('message' => 'Raaka-aine lisätty ostoslistalle!', 'recipe' => $recipe));
    }

    //poistetaan tämän id:n raaka-aine ostoslistalta ja sitten edelleen kaikki
    //sellaiset raaka-aineet, joita mikään kohde ei sitten enää käytä.
    //destroyuseless on vitun paras
    public static function remove($id) {

        self::check_logged_in();
        
        $shoppinglist = ShoppingList::find(self::get_user_logged_in()->id);
        ListItem::destroy($shoppinglist->id, $id);
        Ingredient::destroyUseless();
        
        $items = ShoppingList::findAll($shoppinglist->id);
        Redirect::to('/shoppinglist', array('message' => 'Raaka-aine poistettu ostoslistalta.', 'items' => $items));
    }

    //luodaan uusi ostoslista tälle käyttäjä-id:lle. Jokainen saa oman
    //ostoslistan rekisteröitymisen yhteydessä.
    public static function createNewList($userid) {

        $shoppinglist = new ShoppingList(array('kayttaja' => $userid));
        $shoppinglist->save();
    }

}
