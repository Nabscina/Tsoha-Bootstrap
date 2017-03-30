INSERT INTO Kayttaja (nimi, salasana) VALUES ('sinappinappi', 'password123');
INSERT INTO Kayttaja (nimi, salasana) VALUES ('kokaiinikaniini', 'abcd6543');
INSERT INTO Kayttaja (id, nimi, salasana) VALUES (321, 'kokaiinikaniini', 'abcd6543');

INSERT INTO Raaka_aine (nimi, hinta, ravitsemustiedot) VALUES ('Sinappi', '4.20€/kg', 'terveellistä as fuck, paljon C-vitamiiniä');
INSERT INTO Raaka_aine (nimi, hinta, ravitsemustiedot) VALUES ('Omena', '1.30€/kg', 'lääkärit vihaavat tätä raaka-ainetta');
INSERT INTO Raaka_aine (id, kayttaja, nimi, hinta, ravitsemustiedot) VALUES (13, 321, 'Juusto', '5.00€/kg', 'cheeze');
INSERT INTO Raaka_aine (id, kayttaja, nimi, hinta, ravitsemustiedot) VALUES (14, 321, 'Suola', '2.20€/kg', 'PJSalt');
INSERT INTO Raaka_aine (id, kayttaja, nimi, hinta, ravitsemustiedot) VALUES (15, 321, 'Tumma suklaa', '1.20€/kg', 'aikas namiskuukkelia');
INSERT INTO Raaka_aine (id, kayttaja, nimi, hinta, ravitsemustiedot) VALUES (16, 321, 'Sokeri', '2.20€/kg', 'elimistö tarvitsee sokeria');
INSERT INTO Raaka_aine (id, kayttaja, nimi, hinta, ravitsemustiedot) VALUES (18, 321, 'Vehnäjauho', '4.00€/kg', 'saattaa sisältää pieniä määriä jauhoja');

INSERT INTO Ruokalaji (id, kayttaja, nimi, ateriatyyppi, paaraaka_aine, vaikeustaso, valmistusaika, resepti) VALUES (45, 321, 'Sinappisiika', 'pääruoka', 'siika', 'helppo', '1h 45min', 'osta kaupasta ja laita mikroon');
INSERT INTO Ruokalaji (id, kayttaja, nimi, ateriatyyppi, paaraaka_aine, vaikeustaso, valmistusaika, resepti) VALUES (46, 321, 'Hyytelö', 'jälkiruoka', 'mansikka', 'helppo', '15min', 'just do it tm');
INSERT INTO Ruokalaji (id, kayttaja, nimi, ateriatyyppi, paaraaka_aine, vaikeustaso, valmistusaika, resepti) VALUES (47, 321, 'Kakku', 'jälkiruoka', 'suklaa', 'helppo', '30min', 'kukaan ei osaa tehdä kakkua');

INSERT INTO Ruokalajin_aines (ruokalaji, raaka_aine, nimi, maara) VALUES (45, 13, 'Juusto', '200 g');
INSERT INTO Ruokalajin_aines (ruokalaji, raaka_aine, nimi, maara) VALUES (45, 14, 'Suola', '1/2 tl');
INSERT INTO Ruokalajin_aines (ruokalaji, raaka_aine, nimi, maara) VALUES (47, 15, 'Tumma suklaa', '100 g');
INSERT INTO Ruokalajin_aines (ruokalaji, raaka_aine, nimi, maara) VALUES (47, 16, 'Sokeri', '2 dl');
INSERT INTO Ruokalajin_aines (ruokalaji, raaka_aine, nimi, maara) VALUES (47, 18, 'Vehnäjauho', '6 dl');

INSERT INTO Ostoslista (id, kayttaja) VALUES (30, 321);

INSERT INTO Ostos (ostoslista, raaka_aine) VALUES (30, 13);
INSERT INTO Ostos (ostoslista, raaka_aine) VALUES (30, 14);
