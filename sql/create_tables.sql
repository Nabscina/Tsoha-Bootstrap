CREATE TABLE Kayttaja(id SERIAL PRIMARY KEY, nimi varchar(50) NOT NULL, salasana varchar(50) NOT NULL);

CREATE TABLE Ostoslista(id SERIAL PRIMARY KEY, kayttaja INTEGER REFERENCES Kayttaja(id));

CREATE TABLE Raaka_aine(id SERIAL PRIMARY KEY, kayttaja INTEGER REFERENCES Kayttaja(id), nimi varchar(50) NOT NULL, yksikkohinta DECIMAL, ravitsemustiedot varchar(400));

CREATE TABLE Ateriakokonaisuus(id SERIAL PRIMARY KEY, kayttaja INTEGER REFERENCES Kayttaja(id), nimi varchar(50) NOT NULL, teema varchar(50), juomat varchar(50), ohjeet varchar(1000));

CREATE TABLE Ruokalaji(id SERIAL PRIMARY KEY, kayttaja INTEGER REFERENCES Kayttaja(id), nimi varchar(50) NOT NULL, ateriatyyppi varchar(20), paaraaka_aine varchar(50), vaikeustaso varchar(20), valmistusaika varchar(20), resepti varchar(4000));

CREATE TABLE Kuva(id SERIAL PRIMARY KEY, ruokalaji INTEGER REFERENCES Ruokalaji(id), tiedostonimi varchar(1000) NOT NULL);

CREATE TABLE Ateria(ruokalaji INTEGER REFERENCES Ruokalaji(id), kokonaisuus INTEGER REFERENCES Ateriakokonaisuus(id));

CREATE TABLE Ruokalajin_aines(ruokalaji INTEGER REFERENCES Ruokalaji(id), raakaaine INTEGER REFERENCES Raaka_aine(id));
