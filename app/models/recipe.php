<?php

class Recipe extends BaseModel {

    public $id, $kayttaja, $nimi, $ateriatyyppi, $paaraaka_aine, $vaikeustaso, $valmistusaika, $resepti;

    public function __construct($attributes) {
        parent::__construct($attributes);
        $this->validators = array('validateName', 'validateMainIngredient', 'validateTime', 'validateRecipe');
    }

    //listataan kaikki tietokannan ruokalajit, palautetaan taulukkona
    //Ruokalaji-olioita.
    public static function all() {

        $query = DB::connection()->prepare('SELECT * FROM Ruokalaji');
        $query->execute();

        $rows = $query->fetchAll();

        $recipes = array();

        foreach ($rows as $row) {
            $recipes[] = new Recipe(array(
                'id' => $row['id'],
                'kayttaja' => $row['kayttaja'],
                'nimi' => $row['nimi'],
                'ateriatyyppi' => $row['ateriatyyppi'],
                'paaraaka_aine' => $row['paaraaka_aine'],
                'vaikeustaso' => $row['vaikeustaso'],
                'valmistusaika' => $row['valmistusaika'],
                'resepti' => $row['resepti']
            ));
        }

        return $recipes;
    }

    //listataan kaikki kirjautuneen käyttäjän omat reseptit
    public static function userAll() {

        $query = DB::connection()->prepare('SELECT * FROM Ruokalaji WHERE kayttaja = :kayttaja');
        $query->execute(array('kayttaja' => BaseController::get_user_logged_in()->id));

        $rows = $query->fetchAll();

        $recipes = array();

        foreach ($rows as $row) {
            $recipes[] = new Recipe(array(
                'id' => $row['id'],
                'kayttaja' => $row['kayttaja'],
                'nimi' => $row['nimi'],
                'ateriatyyppi' => $row['ateriatyyppi'],
                'paaraaka_aine' => $row['paaraaka_aine'],
                'vaikeustaso' => $row['vaikeustaso'],
                'valmistusaika' => $row['valmistusaika'],
                'resepti' => $row['resepti']
            ));
        }

        return $recipes;
    }

    //etsitään tietokannasta ruokalaji tällä id:llä ja palautetaan se, jos se
    //löytyi. Muuten palautetaan null.
    public static function find($id) {

        $query = DB::connection()->prepare('SELECT * FROM Ruokalaji WHERE id = :id LIMIT 1');
        $query->execute(array('id' => $id));

        $row = $query->fetch();

        if ($row) {
            $recipe = new Recipe(array(
                'id' => $row['id'],
                'kayttaja' => $row['kayttaja'],
                'nimi' => $row['nimi'],
                'ateriatyyppi' => $row['ateriatyyppi'],
                'paaraaka_aine' => $row['paaraaka_aine'],
                'vaikeustaso' => $row['vaikeustaso'],
                'valmistusaika' => $row['valmistusaika'],
                'resepti' => $row['resepti']
            ));

            return $recipe;
        }
        return null;
    }

    //talletetaan tietokantaan ruokalaji.
    public function save() {

        $query = DB::connection()->prepare('INSERT INTO Ruokalaji (kayttaja, nimi, ateriatyyppi, paaraaka_aine, vaikeustaso, valmistusaika, resepti) VALUES (:kayttaja, :nimi, :ateriatyyppi, :paaraaka_aine, :vaikeustaso, :valmistusaika, :resepti) RETURNING id');
        $query->execute(array('kayttaja' => BaseController::get_user_logged_in()->id, 'nimi' => $this->nimi, 'ateriatyyppi' => $this->ateriatyyppi, 'paaraaka_aine' => $this->paaraaka_aine, 'vaikeustaso' => $this->vaikeustaso, 'valmistusaika' => $this->valmistusaika, 'resepti' => $this->resepti));

        $row = $query->fetch();

        $this->id = $row['id'];
    }

    //muokataan ruokalajin reseptiä käyttäjän kirjoittamaksi.
    public static function saveRecipe($id, $recipe) {

        $query = DB::connection()->prepare('UPDATE Ruokalaji SET resepti = :resepti WHERE id = :id');
        $query->execute(array('resepti' => $recipe, 'id' => $id));
    }

    //muokataan ruokalajin tiedoiksi käyttäjän antamat tiedot.
    public static function updateInfo($id, $recipe) {

        $query = DB::connection()->prepare('UPDATE Ruokalaji SET nimi = :nimi, ateriatyyppi = :ateriatyyppi, paaraaka_aine = :paaraaka_aine, vaikeustaso = :vaikeustaso, valmistusaika = :valmistusaika WHERE id = :id');
        $query->execute(array('id' => $id, 'nimi' => $recipe['nimi'], 'ateriatyyppi' => $recipe['ateriatyyppi'], 'paaraaka_aine' => $recipe['paaraaka_aine'], 'vaikeustaso' => $recipe['vaikeustaso'], 'valmistusaika' => $recipe['valmistusaika']));
    }

    //poistetaan tämän id:n ruokalaji tietokannasta.
    public static function destroy($id) {

        $query = DB::connection()->prepare('DELETE FROM Ruokalaji WHERE id = :id');
        $query->execute(array('id' => $id));
    }

    //jos nimessä on häikkää, $errorsia ei palauteta tyhjänä eikä
    //ruokalajia voida lisätä tietokantaan tai sitä ei voi muokata.
    public function validateName() {

        $errors = array();

        if (ltrim(rtrim($this->nimi)) == '' || $this->nimi == null || strlen(ltrim(rtrim($this->nimi))) < 3 || strlen($this->nimi) > 50 || strlen(ltrim(rtrim($this->nimi))) > 50) {
            $errors[] = 'Nimen minimipituus on 3 merkkiä ja maksimipituus 50 merkkiä.';
        }

        if (is_numeric($this->nimi)) {
            $errors[] = 'Ruokalajin nimi ei saa olla täysin numeerinen.';
        }

        return $errors;
    }

    //samoin jos pääraaka-aineessa on häikkää, lisäys- tai muokkaustoimintoja
    // ei viedä loppuun.
    public function validateMainIngredient() {

        $errors = array();

        if (ltrim(rtrim($this->paaraaka_aine)) == '' || $this->paaraaka_aine == null || strlen(ltrim(rtrim($this->paaraaka_aine))) > 50 || strlen($this->paaraaka_aine) > 50 || strlen(ltrim(rtrim($this->paaraaka_aine))) < 3) {
            $errors[] = 'Määrittele ruokalajin pääraaka-aine(et) (3-50 merkkiä).';
        }

        return $errors;
    }

    public function validateTime() {

        $errors = array();

        if (ltrim(rtrim($this->valmistusaika)) == '' || strlen(ltrim(rtrim($this->valmistusaika))) < 2 || $this->valmistusaika == null || strlen(ltrim(rtrim($this->valmistusaika))) > 20 || strlen($this->valmistusaika) > 20) {
            $errors[] = 'Anna arvio ruokalajin valmistuksessa kuluvasta ajasta (2-20 merkkiä).';
        }

        return $errors;
    }

    public function validateRecipe() {

        $errors = array();

        if (strlen($this->resepti) > 4000) {
            $errors[] = 'Reseptin maksimipituus on 4000 merkkiä.';
        }

        return $errors;
    }

    //jos tietokannasta löytyy ruokalaji käyttäjän id:llä ja $attributes-
    //taulukon arvoilla, palautetaan se, jolloin ruokalajin kontrollerissa
    //jätetään uusi ruokalaji lisäämättä (ei voi spämmilisätä).
    //muuten palautetaan null, ja lisäys voidaan tehdä.
    public static function existingRecipeCheck($attributes) {

        $query = DB::connection()->prepare('SELECT * FROM Ruokalaji WHERE kayttaja = :kayttaja AND nimi = :nimi AND ateriatyyppi = :ateriatyyppi AND paaraaka_aine = :paaraaka_aine AND vaikeustaso = :vaikeustaso AND valmistusaika = :valmistusaika LIMIT 1');
        $query->execute(array('kayttaja' => BaseController::get_user_logged_in()->id, 'nimi' => $attributes['nimi'], 'ateriatyyppi' => $attributes['ateriatyyppi'], 'paaraaka_aine' => $attributes['paaraaka_aine'], 'vaikeustaso' => $attributes['vaikeustaso'], 'valmistusaika' => $attributes['valmistusaika']));

        $row = $query->fetch();

        if ($row) {
            $recipe = new Recipe(array(
                'id' => $row['id'],
                'kayttaja' => $row['kayttaja'],
                'nimi' => $row['nimi'],
                'ateriatyyppi' => $row['ateriatyyppi'],
                'paaraaka_aine' => $row['paaraaka_aine'],
                'vaikeustaso' => $row['vaikeustaso'],
                'valmistusaika' => $row['valmistusaika'],
                'resepti' => $row['resepti']
            ));

            return $recipe;
        }
        return null;
    }

}
