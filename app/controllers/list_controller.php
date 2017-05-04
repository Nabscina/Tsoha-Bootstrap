<?php

class ListController extends BaseController {

    public static function shoppingList() {

        self::check_logged_in();
        $shoppinglist = ShoppingList::find(self::get_user_logged_in()->id);
        $items = ShoppingList::findAll($shoppinglist->id);

        View::make('list/shoppinglist.html', array('items' => $items));
    }

    public static function add($id) {

        self::check_logged_in();

        $shoppinglist = ShoppingList::find(self::get_user_logged_in()->id);

        if (!ShoppingList::findExisting($shoppinglist->id, $id)) {
            ShoppingList::addToList($id, $shoppinglist->id);
        }

        $recipe = recipeIngredient::findByIngredient($id)->ruokalaji;

        Redirect::to('/ingredients/' . $id, array('message' => 'Raaka-aine lisätty ostoslistalle!', 'recipe' => $recipe));
    }

    public static function remove($id) {

        self::check_logged_in();
        
        $shoppinglist = ShoppingList::find(self::get_user_logged_in()->id);
        ListItem::destroy($shoppinglist->id, $id);
        Ingredient::destroyByRecipe();
        
        $items = ShoppingList::findAll($shoppinglist->id);
        Redirect::to('/shoppinglist', array('message' => 'Raaka-aine poistettu ostoslistalta.', 'items' => $items));
    }

    public static function createNewList($userid) {

        $shoppinglist = new ShoppingList(array('kayttaja' => $userid));
        $shoppinglist->save();
    }

}
