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
INSERT INTO fields (label, id_exercises, id_fields_type) VALUES ('Fields linked to exercise 2 with type 1', 2, 1);
INSERT INTO fields (label, id_exercises, id_fields_type) VALUES ('Fields linked to exercise 2 with type 2', 2, 2);
INSERT INTO fields (label, id_exercises, id_fields_type) VALUES ('Fields linked to exercise 2 with type 3', 2, 3);
INSERT INTO fields (label, id_exercises, id_fields_type) VALUES ('Fields linked to exercise 2 with type 1', 3, 1);
INSERT INTO fields (label, id_exercises, id_fields_type) VALUES ('Fields linked to exercise 2 with type 2', 3, 2);
INSERT INTO fields (label, id_exercises, id_fields_type) VALUES ('Fields linked to exercise 2 with type 3', 3, 3);

INSERT INTO answers (value, id_fields) VALUES ('Answer for field 1', 1);
INSERT INTO answers (value, id_fields) VALUES ('Answer for field 2', 2);
INSERT INTO answers (value, id_fields) VALUES ('Answer for field 3', 3);
INSERT INTO answers (value, id_fields) VALUES ('Answer for field 4', 4);
INSERT INTO answers (value, id_fields) VALUES ('Answer for field 5', 5);
INSERT INTO answers (value, id_fields) VALUES ('Answer for field 6', 6);
INSERT INTO answers (value, id_fields) VALUES ('Answer for field 7', 7);
INSERT INTO answers (value, id_fields) VALUES ('Answer for field 8', 8);
INSERT INTO answers (value, id_fields) VALUES ('Answer for field 9', 9);
