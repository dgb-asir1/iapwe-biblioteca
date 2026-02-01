USE `iapwe-biblioteca-bbdd`;


CREATE TABLE Clientes (
    id INT NOT NULL,
    nombre VARCHAR(100),
    apellidos VARCHAR(100),
    fecha_nacimiento DATE,
    localidad VARCHAR(100),
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE Autores (
    id INT NOT NULL,
    autor VARCHAR(100),
    fecha_nacimiento DATE,
    lugar_nacimiento VARCHAR(100),
    fecha_defuncion DATE NULL,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE Libros (
    id INT NOT NULL,
    titulo VARCHAR(200),
    autor_id INT,
    genero VARCHAR(100),
    editorial VARCHAR(100),
    paginas INT,
    fecha_publicacion DATE,
    precio DECIMAL(10,2),
    PRIMARY KEY (id),
    FOREIGN KEY (autor_id) REFERENCES Autores(id)
);

CREATE TABLE Peliculas (
    id INT NOT NULL,
    titulo VARCHAR(200),
    fecha_estreno DATE,
    director VARCHAR(100),
    actores TEXT,
    genero VARCHAR(50),
    tipo_adaptacion VARCHAR(50),
    adaptacion_id INT,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE Reservas (
    id INT NOT NULL,
    cliente_id INT,
    libro_id INT,
    fecha DATE,
    PRIMARY KEY (id),
    FOREIGN KEY (cliente_id) REFERENCES Clientes(id),
    FOREIGN KEY (libro_id) REFERENCES Libros(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE Usuarios (
    id INT NOT NULL,
    usuario VARCHAR(50),
    password VARCHAR(255),
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


LOAD DATA INFILE '/var/lib/mysql-files/Clientes.csv'
INTO TABLE Clientes
CHARACTER SET utf8mb4
FIELDS TERMINATED BY ';'
LINES TERMINATED BY '\r\n'
IGNORE 1 LINES
(id, nombre, apellidos, fecha_nacimiento, localidad);

LOAD DATA INFILE '/var/lib/mysql-files/Autores.csv'
INTO TABLE Autores
CHARACTER SET utf8mb4
FIELDS TERMINATED BY ';'
LINES TERMINATED BY '\r\n'
IGNORE 1 LINES
(id, autor, fecha_nacimiento, lugar_nacimiento, @fecha_defuncion)
SET fecha_defuncion = NULLIF(@fecha_defuncion, 'NULL');

LOAD DATA INFILE '/var/lib/mysql-files/Libros.csv'
INTO TABLE Libros
FIELDS TERMINATED BY ';'
LINES TERMINATED BY '\r\n'
IGNORE 1 LINES
(id, titulo, autor_id, genero, editorial, paginas, fecha_publicacion, precio);

LOAD DATA INFILE '/var/lib/mysql-files/Peliculas.csv'
INTO TABLE Peliculas
CHARACTER SET utf8mb4
FIELDS TERMINATED BY ';'
LINES TERMINATED BY '\r\n'
IGNORE 1 LINES
(id, titulo, fecha_estreno, director, actores, genero, tipo_adaptacion, @adaptacion_id)
SET adaptacion_id = NULLIF(@adaptacion_id, '');

LOAD DATA INFILE '/var/lib/mysql-files/Reservas.csv'
INTO TABLE Reservas
CHARACTER SET utf8mb4
FIELDS TERMINATED BY ';'
LINES TERMINATED BY '\r\n'
IGNORE 1 LINES
(id, cliente_id, libro_id, fecha);

LOAD DATA INFILE '/var/lib/mysql-files/Usuarios.csv'
INTO TABLE Usuarios
CHARACTER SET utf8mb4
FIELDS TERMINATED BY ';'
LINES TERMINATED BY '\r\n'
IGNORE 1 LINES
(id, usuario, password, @nocargar, @nocargar);


