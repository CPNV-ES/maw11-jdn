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

INSERT INTO answers (value, id_fields, id_exercise_answer) VALUES ('Answer for field 1', 1, 1);
INSERT INTO answers (value, id_fields, id_exercise_answer) VALUES ('Answer for field 2', 2, 1);
INSERT INTO answers (value, id_fields, id_exercise_answer) VALUES ('Answer for field 3', 3, 1);
INSERT INTO answers (value, id_fields, id_exercise_answer) VALUES ('Answer for field 4', 4, 2);
INSERT INTO answers (value, id_fields, id_exercise_answer) VALUES ('Answer for field 5', 5, 2);
INSERT INTO answers (value, id_fields, id_exercise_answer) VALUES ('Answer for field 6', 6, 2);
INSERT INTO answers (value, id_fields, id_exercise_answer) VALUES ('Answer for field 7', 7, 3);
INSERT INTO answers (value, id_fields, id_exercise_answer) VALUES ('Answer for field 8', 8, 3);
INSERT INTO answers (value, id_fields, id_exercise_answer) VALUES ('Answer for field 9', 9, 3);

INSERT INTO exercise_answer (id_exercise_answer, id_exercises, created_at) VALUES (1, 1, '2024-11-05 22:41:32 UTC');
INSERT INTO exercise_answer (id_exercise_answer, id_exercises, created_at) VALUES (2, 2, '2024-12-07 14:35:07 UTC');
INSERT INTO exercise_answer (id_exercise_answer, id_exercises, created_at) VALUES (3, 3, '2024-02-10 00:01:50 UTC');


