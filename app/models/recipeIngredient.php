<?php

class recipeIngredient extends BaseModel {

    public $ruokalaji, $raaka_aine, $nimi, $maara;

    public function __construct($attributes) {
        parent::__construct($attributes);
    }

    public static function findByRecipe($id) {

        $query = DB::connection()->prepare('SELECT * FROM Ruokalajin_aines WHERE ruokalaji = :id');
        $query->execute(array('id' => $id));

        $rows = $query->fetchAll();

        $ingredients = array();

        foreach ($rows as $row) {
            $ingredients[] = new recipeIngredient(array(
                'ruokalaji' => $row['ruokalaji'],
                'raaka_aine' => $row['raaka_aine'],
                'nimi' => $row['nimi'],
                'maara' => $row['maara']
            ));
        }

        return $ingredients;
    }

    public static function findByIngredient($id) {

        $query = DB::connection()->prepare('SELECT * FROM Ruokalajin_aines WHERE raaka_aine = :id LIMIT 1');
        $query->execute(array('id' => $id));

        $row = $query->fetch();

        if ($row) {
            $recipeIngredient = new recipeIngredient(array(
                'ruokalaji' => $row['ruokalaji'],
                'raaka_aine' => $row['raaka_aine'],
                'nimi' => $row['nimi'],
                'maara' => $row['maara']
            ));

            return $recipeIngredient;
        }
        return null;
    }

    public function save() {

        $query = DB::connection()->prepare('INSERT INTO Ruokalajin_aines (ruokalaji, raaka_aine, nimi, maara) VALUES (:ruokalaji, :raaka_aine, :nimi, :maara)');
        $query->execute(array('ruokalaji' => $this->ruokalaji, 'raaka_aine' => $this->raaka_aine, 'nimi' => $this->nimi, 'maara' => $this->maara));
    }

    public static function destroyByRecipe($ruokalaji) {

        $query = DB::connection()->prepare('DELETE FROM Ruokalajin_aines WHERE ruokalaji = :ruokalaji');
        $query->execute(array('ruokalaji' => $ruokalaji));
    }

    public static function destroyByIngredient($raaka_aine) {

        $query = DB::connection()->prepare('DELETE FROM Ruokalajin_aines WHERE raaka_aine = :raaka_aine');
        $query->execute(array('raaka_aine' => $raaka_aine));
    }

}
