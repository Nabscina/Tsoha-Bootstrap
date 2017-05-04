<?php

class User extends BaseModel {

    public $id, $nimi, $salasana;

    public function __construct($attributes) {
        parent::__construct($attributes);
        $this->validators = array('validateName', 'validatePassword');
    }

//etsitään tietokannasta käyttäjä tällä nimellä ja salasanalla ja jos löytyy,
//kirjautuminen hyväksytään ja palautetaan User-olio, muuten null.
    public static function authenticate($username, $password) {

        $query = DB::connection()->prepare('SELECT * FROM Kayttaja WHERE nimi = :nimi AND salasana = :salasana LIMIT 1');
        $query->execute(array('nimi' => $username, 'salasana' => $password));
        $row = $query->fetch();

        if ($row) {
            $user = new User(array('id' => $row['id'],
                'nimi' => $row['nimi'],
                'salasana' => $row['salasana']
            ));

            return $user;
        }
        return null;
    }

//etsitään käyttäjää tällä id:llä. Jos löytyy, palautetaan User-olio,
//muuten null.
    public static function find($id) {

        $query = DB::connection()->prepare('SELECT * FROM Kayttaja WHERE id = :id LIMIT 1');
        $query->execute(array('id' => $id));
        $row = $query->fetch();

        if ($row) {
            $user = new User(array('id' => $row['id'],
                'nimi' => $row['nimi'],
                'salasana' => $row['salasana']
            ));

            return $user;
        }

        return null;
    }

    //etsitään käyttäjä tällä nimellä. Tämä on sitä varten, ettei
    //käyttäjä pääse rekisteröimään käytössä olevaa tunnusta.
    public static function findByUsername($username) {

        $query = DB::connection()->prepare('SELECT * FROM Kayttaja WHERE nimi = :nimi LIMIT 1');
        $query->execute(array('nimi' => $username));
        $row = $query->fetch();

        if ($row) {
            $user = new User(array('id' => $row['id'],
                'nimi' => $row['nimi'],
                'salasana' => $row['salasana']
            ));

            return $user;
        }

        return null;
    }

    //lisätään uusi käyttäjä tietokantaan.
    public function save() {

        $query = DB::connection()->prepare('INSERT INTO Kayttaja (nimi, salasana) VALUES (:nimi, :salasana) RETURNING id');
        $query->execute(array('nimi' => $this->nimi, 'salasana' => $this->salasana));

        $row = $query->fetch();

        $this->id = $row['id'];
    }

    //katsotaan, että käyttäjän valitsema nimi on pituudeltaan sopiva
    //ja että se ei jo ole toisen käyttäjän käytössä.
    public function validateName() {

        $errors = array();

        if (User::findByUsername($this->nimi)) {
            $errors[] = 'Valitsemasi tunnus on jo käytössä!';
        }

        if (ltrim(rtrim($this->nimi)) == '' || $this->nimi == null || strlen(ltrim(rtrim($this->nimi))) < 3 || strlen($this->nimi) > 50 || strlen(ltrim(rtrim($this->nimi))) > 50) {
            $errors[] = 'Tunnuksesi on oltava 3-50 merkkiä pitkä.';
        }

        return $errors;
    }

    //katsotaan, että salasana vastaa pituusvaatimuksia.
    public function validatePassword() {

        $errors = array();

        if ($this->salasana == null || strlen($this->salasana) < 8 || strlen($this->salasana) > 50) {
            $errors[] = 'Salasanasi on oltava 8-50 merkkiä pitkä.';
        }

        return $errors;
    }

}
