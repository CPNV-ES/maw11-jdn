DROP TABLE IF EXISTS exercises;
DROP TABLE IF EXISTS fields;
DROP TABLE IF EXISTS fields_type;
DROP TABLE IF EXISTS status;

CREATE TABLE fields_type (
    id_fields_type INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL
);

CREATE TABLE status (
    id_status INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL
);

CREATE TABLE exercises (
    id_exercises INTEGER PRIMARY KEY AUTOINCREMENT,
    title TEXT NOT NULL,
    id_status INTEGER,
    FOREIGN KEY (id_status) REFERENCES status(id_status)
);

CREATE TABLE fields (
    id_fields INTEGER PRIMARY KEY AUTOINCREMENT,
    label TEXT NOT NULL,
    id_exercises INTEGER,
    id_fields_type INTEGER,
    FOREIGN KEY (id_exercises) REFERENCES exercises(id_exercises),
    FOREIGN KEY (id_fields_type) REFERENCES fields_type(id_fields_type)
);
