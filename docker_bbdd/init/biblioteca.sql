USE `iapwe-biblioteca-bbdd`;

-- CREAR TABLAS

CREATE TABLE Clientes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    apellidos VARCHAR(150) NOT NULL,
    fecha_nacimiento DATE,
    localidad VARCHAR(100)
) ENGINE=InnoDB;

CREATE TABLE Autores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(150) NOT NULL,
    fecha_nacimiento DATE,
    lugar VARCHAR(100),
    fecha_defuncion DATE
) ENGINE=InnoDB;

CREATE TABLE Libros (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(200) NOT NULL,
    autor_id INT NOT NULL,
    genero VARCHAR(100),
    editorial VARCHAR(100),
    paginas INT,
    anio INT,
    precio DECIMAL(6,2),
    CONSTRAINT fk_libros_autor
        FOREIGN KEY (autor_id) REFERENCES Autores(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE Peliculas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(200) NOT NULL,
    anio_estreno INT,
    director VARCHAR(150),
    actores TEXT,
    genero VARCHAR(100),
    tipo_adaptacion VARCHAR(100),
    adaptacion_id INT,
    CONSTRAINT fk_peliculas_libros
        FOREIGN KEY (adaptacion_id) REFERENCES Libros(id)
        ON DELETE SET NULL
        ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE Reservas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    idCliente INT NOT NULL,
    idLibro INT NOT NULL,
    fecha_reserva DATE NOT NULL,
    CONSTRAINT fk_reservas_cliente
        FOREIGN KEY (idCliente) REFERENCES Clientes(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    CONSTRAINT fk_reservas_libro
        FOREIGN KEY (idLibro) REFERENCES Libros(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE Usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre_usuario VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(64) NOT NULL,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;


-- IMPORTAR CSV

LOAD DATA INFILE '/docker-entrypoint-initdb.d/Clientes.csv'
INTO TABLE Clientes
CHARACTER SET utf8mb4
FIELDS TERMINATED BY ';'
LINES TERMINATED BY '\n'
IGNORE 1 LINES
(id, nombre, apellidos, fecha_nacimiento, localidad);


LOAD DATA INFILE '/docker-entrypoint-initdb.d/Autores.csv'
INTO TABLE Autores
CHARACTER SET utf8mb4
FIELDS TERMINATED BY ';'
LINES TERMINATED BY '\n'
IGNORE 1 LINES
(id, nombre, fecha_nacimiento, lugar, fecha_defuncion);


LOAD DATA INFILE '/docker-entrypoint-initdb.d/Libros.csv'
INTO TABLE Libros
CHARACTER SET utf8mb4
FIELDS TERMINATED BY ';'
LINES TERMINATED BY '\n'
IGNORE 1 LINES
(id, titulo, autor_id, genero, editorial, paginas, anio, precio);

LOAD DATA INFILE '/docker-entrypoint-initdb.d/Peliculas.csv'
INTO TABLE Peliculas
CHARACTER SET utf8mb4
FIELDS TERMINATED BY ';'
LINES TERMINATED BY '\n'
IGNORE 1 LINES
(id, titulo, anio_estreno, director, actores, genero, tipo_adaptacion, adaptacion_id);

LOAD DATA INFILE '/docker-entrypoint-initdb.d/Reservas.csv'
INTO TABLE Reservas
CHARACTER SET utf8mb4
FIELDS TERMINATED BY ';'
LINES TERMINATED BY '\n'
IGNORE 1 LINES
(id, idCliente, idLibro, fecha_reserva);


