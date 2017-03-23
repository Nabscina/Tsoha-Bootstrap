INSERT INTO Kayttaja (nimi, salasana) VALUES ('sinappinappi', 'password123');
INSERT INTO Kayttaja (nimi, salasana) VALUES ('kokaiinikaniini', 'abcd6543');
INSERT INTO Kayttaja (id, nimi, salasana) VALUES (321, 'kokaiinikaniini', 'abcd6543');

INSERT INTO Raaka_aine (nimi, yksikkohinta, ravitsemustiedot) VALUES ('Sinappi', 10.5, 'terveellistä as fuck, paljon C-vitamiiniä');
INSERT INTO Raaka_aine (nimi, yksikkohinta, ravitsemustiedot) VALUES ('Omena', 3.4, 'lääkärit vihaavat tätä raaka-ainetta');

INSERT INTO Ruokalaji (id, kayttaja, nimi, ateriatyyppi, paaraaka_aine, vaikeustaso, valmistusaika, resepti) VALUES (45, 321, 'Sinappisiika', 'pääruoka', 'siika', 'helppo', '1h 45min', 'osta kaupasta ja laita mikroon');
INSERT INTO Ruokalaji (id, kayttaja, nimi, ateriatyyppi, paaraaka_aine, vaikeustaso, valmistusaika, resepti) VALUES (46, 321, 'Hyytelö', 'jälkiruoka', 'mansikka', 'helppo', '15min', 'just do it tm');

INSERT INTO Ateriakokonaisuus (id, kayttaja, nimi, teema, juomat, ohjeet) VALUES (12, 321, 'Kinkkukiusauskekkerit', 'vappu', 'maito, mustikkakeitto', 'informatiivinen ohje');

INSERT INTO Ateria (ruokalaji, kokonaisuus) VALUES (45, 12);
INSERT INTO Ateria (ruokalaji, kokonaisuus) VALUES (46, 12);