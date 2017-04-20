<?php

class recipeIngredient extends BaseModel {

    public $ruokalaji, $raaka_aine, $nimi, $maara;

    public function __construct($attributes) {
        parent::__construct($attributes);
        $this->validators = array('validateAmount');
    }

    //etsitään tietokannasta sellaiset ruokalajin ainekset, joiden ruokalajina
    //on tämän id:n ruokalaji. Palautetaan taulukko recipeIngredient-olioita.
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

    //etsitään tietokannasta sellaiset ruokalajin ainekset, joihin liittyy tämän
    //id:n raaka-aine. Palautetaan,, jos löytyi, muuten palautetaan null.
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

    //etsitän tietokannasta kaikki raaka-aineet, jotka liittyvät tämän id:n
    //ruokalajiin. Palautetaan taulukko Ingredient-olioita.
    public static function findAllIngredientsByRecipe($id) {

        $query = DB::connection()->prepare('SELECT * FROM Raaka_aine WHERE Raaka_aine.id IN (SELECT raaka_aine FROM Ruokalajin_aines WHERE ruokalaji = :id)');
        $query->execute(array('id' => $id));

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

    //talletetaan tietokantaan ruokalajin aines.
    public function save() {

        $query = DB::connection()->prepare('INSERT INTO Ruokalajin_aines (ruokalaji, raaka_aine, nimi, maara) VALUES (:ruokalaji, :raaka_aine, :nimi, :maara)');
        $query->execute(array('ruokalaji' => $this->ruokalaji, 'raaka_aine' => $this->raaka_aine, 'nimi' => $this->nimi, 'maara' => $this->maara));
    }

    //muutetaan ruokalajin aineksen nimeä raaka-aineen id:n perusteella.
    public static function editName($id, $nimi) {

        $query = DB::connection()->prepare('UPDATE Ruokalajin_aines SET nimi = :nimi WHERE raaka_aine = :id');
        $query->execute(array('id' => $id, 'nimi' => $nimi));
    }

    //muutetaan nimeä ja määrää raaka-aineen id:n perusteella.
    public static function editByIngredient($id, $nimi, $maara) {

        $query = DB::connection()->prepare('UPDATE Ruokalajin_aines SET nimi = :nimi, maara = :maara WHERE raaka_aine = :id');
        $query->execute(array('id' => $id, 'nimi' => $nimi, 'maara' => $maara));
    }

    //poistetaan ruokalajin aines ruokalajin perusteella.
    public static function destroyByRecipe($ruokalaji) {

        $query = DB::connection()->prepare('DELETE FROM Ruokalajin_aines WHERE ruokalaji = :ruokalaji');
        $query->execute(array('ruokalaji' => $ruokalaji));
    }

    //poistetaan ruokalajin aines raaka-aineen perusteella.
    public static function destroyByIngredient($raaka_aine) {

        $query = DB::connection()->prepare('DELETE FROM Ruokalajin_aines WHERE raaka_aine = :raaka_aine');
        $query->execute(array('raaka_aine' => $raaka_aine));
    }

    //jos ilmenee, että määrässä on häikkää, palautetaan epätyhjä $errors
    //eikä tietokantaan voida tehdä muutoksia.
    public function validateAmount() {

        $errors = array();

        if (ltrim(rtrim($this->maara)) == '' || strlen(ltrim(rtrim($this->maara))) < 2 || strlen(ltrim(rtrim($this->maara))) > 20 || strlen($this->maara) > 20) {
            $errors[] = 'Anna arvio määrästä, joka raaka-ainetta tarvitaan ruoan valmistuksessa (2-20 merkkiä).';
        }

        return $errors;
    }

}
