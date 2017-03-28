<?php

class RecipeController extends BaseController {
    
    public static function index() {
        
        $recipes = Recipe::all();
        
        View::make('recipe/recipes_list.html', array('recipes' => $recipes));
    }
}

