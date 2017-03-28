CREATE TABLE Kayttaja(id SERIAL PRIMARY KEY, nimi varchar(50) NOT NULL, salasana varchar(50) NOT NULL);

CREATE TABLE Ostoslista(id SERIAL PRIMARY KEY, kayttaja INTEGER REFERENCES Kayttaja(id));

CREATE TABLE Raaka_aine(id SERIAL PRIMARY KEY, kayttaja INTEGER REFERENCES Kayttaja(id), nimi varchar(50) NOT NULL, hinta varchar(20), ravitsemustiedot varchar(400));

CREATE TABLE Ruokalaji(id SERIAL PRIMARY KEY, kayttaja INTEGER REFERENCES Kayttaja(id), nimi varchar(50) NOT NULL, ateriatyyppi varchar(20), paaraaka_aine varchar(50), vaikeustaso varchar(20), valmistusaika varchar(20), resepti varchar(4000));

CREATE TABLE Ruokalajin_aines(ruokalaji INTEGER REFERENCES Ruokalaji(id), raaka_aine INTEGER REFERENCES Raaka_aine(id), maara varchar(20));

CREATE TABLE Ostos(ostoslista INTEGER REFERENCES Ostoslista(id), raaka_aine INTEGER REFERENCES Raaka_aine(id));
