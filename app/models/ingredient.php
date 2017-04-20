<?php

class Ingredient extends BaseModel {

    public $id, $kayttaja, $nimi, $hinta, $ravitsemustiedot;

    public function __construct($attributes) {
        parent::__construct($attributes);
        $this->validators = array('validateName', 'validatePrice', 'validateInfo');
    }

    public static function all() {

        $query = DB::connection()->prepare('SELECT * FROM Raaka_aine');
        $query->execute();

        $rows = $query->fetchAll();

        $ingredients = array();

        foreach ($rows as $row) {
            $ingredients[] = new Ingredient(array(
                'id' => $row['id'],
                'kayttaja' => $row['kayttaja'],
                'nimi' => $row['nimi'],
                'hinta' => $row['hinta'],
                'ravitsemustiedot' => $row['ravitsemustiedot']
            ));
        }

        return $ingredients;
    }

    public static function find($id) {

        $query = DB::connection()->prepare('SELECT * FROM Raaka_aine WHERE id = :id LIMIT 1');
        $query->execute(array('id' => $id));

        $row = $query->fetch();

        if ($row) {
            $ingredient = new Ingredient(array(
                'id' => $row['id'],
                'kayttaja' => $row['kayttaja'],
                'nimi' => $row['nimi'],
                'hinta' => $row['hinta'],
                'ravitsemustiedot' => $row['ravitsemustiedot']
            ));

            return $ingredient;
        }
        return null;
    }

    public function save() {

        $query = DB::connection()->prepare('INSERT INTO Raaka_aine (kayttaja, nimi, hinta, ravitsemustiedot) VALUES (:kayttaja, :nimi, :hinta, :ravitsemustiedot) RETURNING id');
        $query->execute(array('kayttaja' => BaseController::get_user_logged_in()->id, 'nimi' => $this->nimi, 'hinta' => $this->hinta, 'ravitsemustiedot' => $this->ravitsemustiedot));

        $row = $query->fetch();

        $this->id = $row['id'];
    }

    public static function edit($id, $nimi, $hinta, $ravitsemustiedot) {

        $query = DB::connection()->prepare('UPDATE Raaka_aine SET nimi = :nimi, hinta = :hinta, ravitsemustiedot = :ravitsemustiedot WHERE id = :id');
        $query->execute(array('id' => $id, 'nimi' => $nimi, 'hinta' => $hinta, 'ravitsemustiedot' => $ravitsemustiedot));
    }

    public static function editName($id, $nimi) {

        $query = DB::connection()->prepare('UPDATE Raaka_aine SET nimi = :nimi WHERE id = :id');
        $query->execute(array('id' => $id, 'nimi' => $nimi));
    }

    public static function destroyByRecipe() {

        $query = DB::connection()->prepare('DELETE FROM Raaka_aine WHERE Raaka_aine.id NOT IN (SELECT raaka_aine FROM Ruokalajin_aines)');
        $query->execute();
    }

    public static function destroy($id) {

        $query = DB::connection()->prepare('DELETE FROM Raaka_aine WHERE id = :id');
        $query->execute(array('id' => $id));
    }

    public static function existingIngredientCheck($attributes) {

        $query = DB::connection()->prepare('SELECT * FROM Raaka_aine WHERE kayttaja = :kayttaja AND nimi = :nimi AND hinta = :hinta AND ravitsemustiedot = :ravitsemustiedot LIMIT 1');
        $query->execute(array('kayttaja' => BaseController::get_user_logged_in()->id, 'nimi' => $attributes['nimi'], 'hinta' => $attributes['hinta'], 'ravitsemustiedot' => $attributes['ravitsemustiedot']));

        $row = $query->fetch();

        if ($row) {
            $ingredient = new Ingredient(array(
                'id' => $row['id'],
                'kayttaja' => $row['kayttaja'],
                'nimi' => $row['nimi'],
                'hinta' => $row['hinta'],
                'ravitsemustiedot' => $row['ravitsemustiedot']
            ));

            return $ingredient;
        }
        return null;
    }

    public function validateName() {

        $errors = array();

        if (ltrim(rtrim($this->nimi)) == '' || $this->nimi == null || strlen(ltrim(rtrim($this->nimi))) < 3 || strlen($this->nimi) > 50 || strlen(ltrim(rtrim($this->nimi))) > 50) {
            $errors[] = 'Nimen minimipituus on 3 merkkiä ja maksimipituus 50 merkkiä.';
        }

        if (is_numeric($this->nimi)) {
            $errors[] = 'Raaka-aineen nimi ei saa olla täysin numeerinen.';
        }

        return $errors;
    }
    
    public function validatePrice() {
        
        $errors = array();
        
        if (strlen($this->hinta) > 20) {
            $errors[] = 'Käytä hinnan määrittelyssä enintään 20 merkkiä.';
        }
        
        return $errors;
    }
    
    public function validateInfo() {
        
        $errors = array();
        
        if (strlen($this->ravitsemustiedot) > 400) {
            $errors[] = 'Kerro raaka-aineen ravitsemustiedoista lyhyemmin (käytä max. 400 merkkiä).';
        }
        
        return $errors;
    }

}
