INSERT INTO fields_type (name) VALUES ('single_line');
INSERT INTO fields_type (name) VALUES ('single_line_list');
INSERT INTO fields_type (name) VALUES ('multi_line');

INSERT INTO status (name) VALUES ('edit');
INSERT INTO status (name) VALUES ('answering');
INSERT INTO status (name) VALUES ('closed');

INSERT INTO exercises (title, id_status) VALUES ('Exercise with Status 1', 1);
INSERT INTO exercises (title, id_status) VALUES ('Exercise with Status 2', 2);
INSERT INTO exercises (title, id_status) VALUES ('Exercise with Status 3', 3);

INSERT INTO fields (label, id_exercises, id_fields_type) VALUES ('Fields linked to exercise 1 with type 1', 1, 1);
INSERT INTO fields (label, id_exercises, id_fields_type) VALUES ('Fields linked to exercise 1 with type 2', 1, 2);
INSERT INTO fields (label, id_exercises, id_fields_type) VALUES ('Fields linked to exercise 1 with type 3', 1, 3);
INSERT INTO fields (label, id_exercises, id_fields_type) VALUES ('Fields linked to exercise 2 with type 2', 2, 2);
INSERT INTO fields (label, id_exercises, id_fields_type) VALUES ('Fields linked to exercise 3 with type 1', 3, 1);
