CREATE TABLE IF NOT EXISTS Contact (
    Id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    Straat VARCHAR(50) NOT NULL,
    Huisnummer INT UNSIGNED NOT NULL,
    Postcode VARCHAR(10) NOT NULL,
    Stad VARCHAR(50) NOT NULL,
    IsActief BIT NOT NULL DEFAULT b'1',
    Opmerking VARCHAR(250) NULL,
    DatumAangemaakt DATETIME(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
    DatumGewijzigd DATETIME(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6)
) ENGINE=InnoDB;

INSERT INTO Contact (Id, Straat, Huisnummer, Postcode, Stad) VALUES
    (1, 'Van Gilslaan', 34, '1045CB', 'Hilvarenbeek'),
    (2, 'Den Dolderpad', 2, '1067RC', 'Utrecht'),
    (3, 'Fredo Raalteweg', 257, '1236OP', 'Nijmegen'),
    (4, 'Bertrand Russellhof', 21, '2034AP', 'Den Haag'),
    (5, 'Leon van Bonstraat', 213, '145XC', 'Lunteren'),
    (6, 'Bea van Lingenlaan', 234, '2197FG', 'Sint Pancras')
ON DUPLICATE KEY UPDATE Id = Contact.Id;
