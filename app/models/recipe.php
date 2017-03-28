<?php

class Recipe extends BaseModel {
    
    public $id, $kayttaja, $nimi, $ateriatyyppi, $paaraaka_aine, $vaikeustaso, $valmistusaika, $resepti;
    
    public function __construct($attributes) {
        parent::__construct($attributes);
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
}

