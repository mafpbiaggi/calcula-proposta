CREATE TABLE IF NOT EXISTS registros (   
    id INT AUTO_INCREMENT NOT NULL,
    nome VARCHAR(255) NULL,
    documento VARCHAR(255) NULL,
    valorBem FLOAT NULL,
    percPago INT NULL,
    encrGrupo DATE NULL,
    resultado VARCHAR(255) NULL,
    created DATETIME NULL,
    PRIMARY KEY (id)
);