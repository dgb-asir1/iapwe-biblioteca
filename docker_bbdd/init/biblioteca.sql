USE `iapwe-biblioteca-bbdd`;

CREATE TABLE Clientes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    apellidos VARCHAR(150) NOT NULL,
    fecha_nacimiento DATE,
    localidad VARCHAR(100)
);

CREATE TABLE Autores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(150) NOT NULL,
    fecha_nacimiento DATE,
    lugar VARCHAR(100),
    fecha_defuncion DATE
);

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
);

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
);

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
);

CREATE TABLE Usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre_usuario VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(64) NOT NULL,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO Usuarios (nombre_usuario, password)
VALUES (
    'admin', SHA2('1234', 256)
);
