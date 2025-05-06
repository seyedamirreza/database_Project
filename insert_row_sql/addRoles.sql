SET IDENTITY_INSERT roles ON;

INSERT INTO roles (id, name) VALUES
(1, 'user'),
(2, 'admin');

SET IDENTITY_INSERT roles OFF;
