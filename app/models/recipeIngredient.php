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
    
    public function save() {
        
        $query = DB::connection()->prepare('INSERT INTO Ruokalajin_aines (ruokalaji, raaka_aine, nimi, maara) VALUES (:ruokalaji, :raaka_aine, :nimi, :maara)');
        $query->execute(array('ruokalaji' => $this->ruokalaji,'raaka_aine' => $this->raaka_aine, 'nimi'=> $this->nimi, 'maara' => $this->maara));
        
    }

}
