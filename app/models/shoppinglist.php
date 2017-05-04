<?php

class ShoppingList extends BaseModel {

    public $id, $kayttaja;

    public function __construct($attributes) {
        parent::__construct($attributes);
    }

    //etsitään kaikki tämän lista-id:n raaka-aineet, jotta voidaan listata
    //ne käyttäjälle. Palautetaan siis Ingredient-olioina.
    public static function findAll($listid) {

        $query = DB::connection()->prepare('SELECT * FROM Raaka_aine WHERE Raaka_aine.id IN (SELECT raaka_aine FROM Ostos WHERE ostoslista = :ostoslista)');
        $query->execute(array('ostoslista' => $listid));

        $rows = $query->fetchAll();

        $items = array();

        foreach ($rows as $row) {
            $items[] = new Ingredient(array(
                'id' => $row['id'],
                'kayttaja' => $row['kayttaja'],
                'nimi' => $row['nimi'],
                'hinta' => $row['hinta'],
                'ravitsemustiedot' => $row['ravitsemustiedot']
            ));
        }

        return $items;
    }

    //palautetaan se ostoslista, joka kuuluu tälle käyttäjä-id:lle.
    public static function find($userid) {

        $query = DB::connection()->prepare('SELECT * FROM Ostoslista WHERE kayttaja = :kayttaja LIMIT 1');
        $query->execute(array('kayttaja' => $userid));

        $row = $query->fetch();

        if ($row) {
            $shoppinglist = new ShoppingList(array(
                'id' => $row['id'],
                'kayttaja' => $row['kayttaja'],
            ));

            return $shoppinglist;
        }
        return null;
    }

    //etsitään Ostos-taulusta tällä lista-id:llä ja raaka-aineella,
    //ja palautetaan ListItem-olio, jos jotain löytyi. Tämä on sitä varten,
    //ettei samaa raaka-ainetta lisätä samalle listalle useasti.
    public static function findExisting($listid, $ingredientid) {

        $query = DB::connection()->prepare('SELECT * FROM Ostos WHERE ostoslista = :ostoslista AND raaka_aine = :raaka_aine LIMIT 1');
        $query->execute(array('ostoslista' => $listid, 'raaka_aine' => $ingredientid));

        $row = $query->fetch();

        if ($row) {
            $item = new ListItem(array(
                'ostoslista' => $row['ostoslista'],
                'raaka_aine' => $row['raaka_aine'],
            ));

            return $item;
        }
        return null;
    }

    //luodaan uusi ostoslista sille käyttäjälle, joka on lisätty tälle
    //ShoppingList-oliolle.
    public function save() {

        $query = DB::connection()->prepare('INSERT INTO Ostoslista (kayttaja) VALUES (:kayttaja) RETURNING id');
        $query->execute(array('kayttaja' => $this->kayttaja));

        $row = $query->fetch();

        $this->id = $row['id'];
    }

    //lisätään tämän id:n ostoslistalle tämän id:n raaka-aine. 
    public static function addToList($id, $listid) {

        $query = DB::connection()->prepare('INSERT INTO Ostos (ostoslista, raaka_aine) VALUES (:ostoslista, :raaka_aine)');
        $query->execute(array('ostoslista' => $listid, 'raaka_aine' => $id));
    }

}
