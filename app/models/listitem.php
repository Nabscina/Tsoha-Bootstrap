<?php

class ListItem extends BaseModel {

    public $ostoslista, $raaka_aine;

    public function __construct($attributes) {
        parent::__construct($attributes);
    }

    //etsitään Ostos-taulusta tällä raaka-aineen id:llä. Palautetaan, jos
    //sellainen on.
    public static function findByIngredient($ingredient) {

        $query = DB::connection()->prepare('SELECT * FROM Ostos WHERE raaka_aine = :raaka_aine LIMIT 1');
        $query->execute(array('raaka_aine' => $ingredient));

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

    //poistetaan Ostos-taulusta tällä ostoslistan ja raaka-aineen id:llä.
    public static function destroy($listid, $ingredient) {

        $query = DB::connection()->prepare('DELETE FROM Ostos WHERE ostoslista = :ostoslista AND raaka_aine = :raaka_aine');
        $query->execute(array('ostoslista' => $listid, 'raaka_aine' => $ingredient));
    }

}
