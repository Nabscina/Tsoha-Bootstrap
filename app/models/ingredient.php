<?php

class Ingredient extends BaseModel {

    public $id, $nimi, $hinta, $ravitsemustiedot;

    public function __construct($attributes) {
        parent::__construct($attributes);
        $this->validators = array('validateName');
    }

    public static function all() {

        $query = DB::connection()->prepare('SELECT * FROM Raaka_aine');
        $query->execute();

        $rows = $query->fetchAll();

        $ingredients = array();

        foreach ($rows as $row) {
            $ingredients[] = new Ingredient(array(
                'id' => $row['id'],
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
                'nimi' => $row['nimi'],
                'hinta' => $row['hinta'],
                'ravitsemustiedot' => $row['ravitsemustiedot']
            ));

            return $ingredient;
        }
        return null;
    }

    public function save() {

        $query = DB::connection()->prepare('INSERT INTO Raaka_aine (nimi, hinta, ravitsemustiedot) VALUES (:nimi, :hinta, :ravitsemustiedot) RETURNING id');
        $query->execute(array('nimi' => $this->nimi, 'hinta' => $this->hinta, 'ravitsemustiedot' => $this->ravitsemustiedot));

        $row = $query->fetch();

        $this->id = $row['id'];
    }

    public static function destroyByRecipe() {

        $query = DB::connection()->prepare('DELETE FROM Raaka_aine WHERE Raaka_aine.id NOT IN (SELECT raaka_aine FROM Ruokalajin_aines)');
        $query->execute();
    }

    public static function destroy($id) {

        $query = DB::connection()->prepare('DELETE FROM Raaka_aine WHERE id = :id');
        $query->execute(array('id' => $id));
    }

    public function validateName() {

        $errors = array();

        if ($this->nimi == '' || $this->nimi == null || strlen($this->nimi) < 3) {
            $errors[] = 'Anna raaka-aineelle vähintään kolmen merkin pituinen nimi.';
        }

        if (is_numeric($this->nimi)) {
            $errors[] = 'Raaka-aineen nimi ei saa olla täysin numeerinen.';
        }

        return $errors;
    }

}
