INSERT INTO Kayttaja (nimi, salasana) VALUES ('sinappinappi', 'password123');
INSERT INTO Kayttaja (nimi, salasana) VALUES ('kokaiinikaniini', 'abcd6543');
INSERT INTO Kayttaja (id, nimi, salasana) VALUES (321, 'kokaiinikaniini', 'abcd6543');

INSERT INTO Raaka_aine (nimi, hinta, ravitsemustiedot) VALUES ('Sinappi', '4.20€/kg', 'terveellistä as fuck, paljon C-vitamiiniä');
INSERT INTO Raaka_aine (nimi, hinta, ravitsemustiedot) VALUES ('Omena', '1.30€/kg', 'lääkärit vihaavat tätä raaka-ainetta');
INSERT INTO Raaka_aine (id, kayttaja, nimi, hinta, ravitsemustiedot) VALUES (13, 321, 'Juusto', '5.00€/kg', 'cheeze');
INSERT INTO Raaka_aine (id, kayttaja, nimi, hinta, ravitsemustiedot) VALUES (14, 321, 'Suola', '2.20€/kg', 'PJSalt');

INSERT INTO Ruokalaji (id, kayttaja, nimi, ateriatyyppi, paaraaka_aine, vaikeustaso, valmistusaika, resepti) VALUES (45, 321, 'Sinappisiika', 'pääruoka', 'siika', 'helppo', '1h 45min', 'osta kaupasta ja laita mikroon');
INSERT INTO Ruokalaji (id, kayttaja, nimi, ateriatyyppi, paaraaka_aine, vaikeustaso, valmistusaika, resepti) VALUES (46, 321, 'Hyytelö', 'jälkiruoka', 'mansikka', 'helppo', '15min', 'just do it tm');
INSERT INTO Ruokalaji (id, kayttaja, nimi, ateriatyyppi, paaraaka_aine, vaikeustaso, valmistusaika, resepti) VALUES (47, 321, 'Kakku', 'jälkiruoka', 'suklaa', 'helppo', '30min', 'kukaan ei osaa tehdä kakkua');

INSERT INTO Ruokalajin_aines (ruokalaji, raaka_aine, maara) VALUES (45, 13, '200 g');
INSERT INTO Ruokalajin_aines (ruokalaji, raaka_aine, maara) VALUES (45, 14, '1/2 tl');

INSERT INTO Ostoslista (id, kayttaja) VALUES (30, 321);

INSERT INTO Ostos (ostoslista, raaka_aine) VALUES (30, 13);
INSERT INTO Ostos (ostoslista, raaka_aine) VALUES (30, 14);
