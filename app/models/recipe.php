<?php

class Recipe extends BaseModel {

    public $id, $kayttaja, $nimi, $ateriatyyppi, $paaraaka_aine, $vaikeustaso, $valmistusaika, $resepti;

    public function __construct($attributes) {
        parent::__construct($attributes);
        $this->validators = array('validateName');
    }

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

    public function save() {

        $query = DB::connection()->prepare('INSERT INTO Ruokalaji (nimi, ateriatyyppi, paaraaka_aine, vaikeustaso, valmistusaika, resepti) VALUES (:nimi, :ateriatyyppi, :paaraaka_aine, :vaikeustaso, :valmistusaika, :resepti) RETURNING id');
        $query->execute(array('nimi' => $this->nimi, 'ateriatyyppi' => $this->ateriatyyppi, 'paaraaka_aine' => $this->paaraaka_aine, 'vaikeustaso' => $this->vaikeustaso, 'valmistusaika' => $this->valmistusaika, 'resepti' => $this->resepti));

        $row = $query->fetch();

        $this->id = $row['id'];
    }

    public static function saveRecipe($id, $recipe) {

        $query = DB::connection()->prepare('UPDATE Ruokalaji SET resepti = :resepti WHERE id = :id');
        $query->execute(array('resepti' => $recipe, 'id' => $id));
    }

    public static function updateInfo($id, $recipe) {

        $query = DB::connection()->prepare('UPDATE Ruokalaji SET nimi = :nimi, ateriatyyppi = :ateriatyyppi, paaraaka_aine = :paaraaka_aine, vaikeustaso = :vaikeustaso, valmistusaika = :valmistusaika WHERE id = :id');
        $query->execute(array('id' => $id, 'nimi' => $recipe['nimi'], 'ateriatyyppi' => $recipe['ateriatyyppi'], 'paaraaka_aine' => $recipe['paaraaka_aine'], 'vaikeustaso' => $recipe['vaikeustaso'], 'valmistusaika' => $recipe['valmistusaika']));
    }

    public static function destroy($id) {

        $query = DB::connection()->prepare('DELETE FROM Ruokalaji WHERE id = :id');
        $query->execute(array('id' => $id));
    }

    public function validateName() {

        $errors = array();

        if ($this->nimi == '' || $this->nimi == null || strlen($this->nimi) < 3) {
            $errors[] = 'Anna ruokalajillesi vähintään kolmen merkin pituinen nimi.';
        }

        if (is_numeric($this->nimi)) {
            $errors[] = 'Ruokalajin nimi ei saa olla täysin numeerinen.';
        }

        return $errors;
    }

}
