
drop database if exists hospital_sampaka;
create database hospital_sampaka;
use hospital_sampaka;


-- Tabla: personal
CREATE TABLE personal (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    codigo_personal VARCHAR(50) UNIQUE,
    nombre VARCHAR(100),
    apellidos VARCHAR(100),
    fecha_nacimiento DATE,
    residencia VARCHAR(150),
    nivel_educacional VARCHAR(100),
    cargo VARCHAR(100),
    telefono VARCHAR(20),
    correo VARCHAR(100),
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Tabla: usuarios_hospital (renombrada para evitar conflicto)
CREATE TABLE usuarios_hospital (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nombre_usuario VARCHAR(50) UNIQUE,
    password VARCHAR(255),
    rol VARCHAR(50),
    id_personal INT UNSIGNED,
    estado TINYINT DEFAULT 1,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_personal) REFERENCES personal(id)
) ENGINE=InnoDB;

-- Tabla: pacientes
CREATE TABLE pacientes (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    codigo_paciente VARCHAR(50) UNIQUE,
    nombre VARCHAR(100),
    apellidos VARCHAR(100),
    fecha_nacimiento DATE,
    telefono VARCHAR(20),
    residencia VARCHAR(150),
    profesion VARCHAR(100),
    ocupacion VARCHAR(100),
    nacionalidad VARCHAR(50),
    tutor VARCHAR(100),
    telefono_tutor VARCHAR(20),
    id_usuario INT UNSIGNED,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_usuario) REFERENCES usuarios_hospital(id)
) ENGINE=InnoDB;

-- Tabla: consulta
CREATE TABLE consulta (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    motivo_consulta TEXT,
    temperatura DECIMAL(4,1),
    frecuencia_cardiaca INT,
    frecuencia_respiratoria INT,
    tension_arterial VARCHAR(20),
    pulso INT,
    saturacion_oxigeno DECIMAL(4,1),
    peso DECIMAL(5,2),
    masa_indice_corporal DECIMAL(5,2),
    id_usuario INT UNSIGNED,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    id_paciente INT UNSIGNED,
    codigo_paciente VARCHAR(50),
    FOREIGN KEY (id_usuario) REFERENCES usuarios_hospital(id),
    FOREIGN KEY (id_paciente) REFERENCES pacientes(id)
) ENGINE=InnoDB;

-- Tabla: detalle_consulta
CREATE TABLE detalle_consulta (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    id_consulta INT UNSIGNED,
    id_usuario INT UNSIGNED,
    operacion BOOLEAN,
    orina BOOLEAN,
    defeca BOOLEAN,
    intervalo_defecacion_dias INT,
    duerme_bien BOOLEAN,
    horas_sueno INT,
    antecedentes_patologicos TEXT,
    alergico TEXT,
    antecedentes_patologicos_familiares TEXT,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_consulta) REFERENCES consulta(id),
    FOREIGN KEY (id_usuario) REFERENCES usuarios_hospital(id)
) ENGINE=InnoDB;

-- Tabla: receta
CREATE TABLE receta (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    descripcion TEXT,
    id_usuario INT UNSIGNED,
    observaciones TEXT,
    id_consulta INT UNSIGNED,
    id_paciente INT UNSIGNED,
    codigo_paciente VARCHAR(50),
    FOREIGN KEY (id_usuario) REFERENCES usuarios_hospital(id),
    FOREIGN KEY (id_consulta) REFERENCES consulta(id),
    FOREIGN KEY (id_paciente) REFERENCES pacientes(id)
) ENGINE=InnoDB;

-- Tabla: pruebas_hospital
CREATE TABLE pruebas_hospital (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nombre_prueba VARCHAR(100),
    precio DECIMAL(10,2),
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Tabla: analiticas
CREATE TABLE analiticas (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    resultado TEXT,
    estado VARCHAR(50),
    id_prueba INT UNSIGNED,
    id_consulta INT UNSIGNED,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    id_usuario INT UNSIGNED,
    id_paciente INT UNSIGNED,
    codigo_paciente VARCHAR(50),
    pagado BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (id_prueba) REFERENCES pruebas_hospital(id),
    FOREIGN KEY (id_consulta) REFERENCES consulta(id),
    FOREIGN KEY (id_usuario) REFERENCES usuarios_hospital(id),
    FOREIGN KEY (id_paciente) REFERENCES pacientes(id)
) ENGINE=InnoDB;

-- Tabla: salas_ingreso
CREATE TABLE salas_ingreso (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nombre_sala VARCHAR(100),
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    id_usuario INT UNSIGNED,
    FOREIGN KEY (id_usuario) REFERENCES usuarios_hospital(id)
) ENGINE=InnoDB;

-- Tabla: ingresos
CREATE TABLE ingresos (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    id_paciente INT UNSIGNED,
    codigo_paciente VARCHAR(50),
    id_usuario INT UNSIGNED,
    id_consulta INT UNSIGNED,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_ingreso DATE,
    id_sala INT UNSIGNED,
    numero_cama VARCHAR(20),
    fecha_alta DATE,
    token VARCHAR(100),
    FOREIGN KEY (id_paciente) REFERENCES pacientes(id),
    FOREIGN KEY (id_usuario) REFERENCES usuarios_hospital(id),
    FOREIGN KEY (id_consulta) REFERENCES consulta(id),
    FOREIGN KEY (id_sala) REFERENCES salas_ingreso(id)
) ENGINE=InnoDB;
