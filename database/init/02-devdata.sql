-- Populate schema with example data

INSERT INTO users (username, password_hash, email, token) VALUES
('alice', 'hashed_password_1', 'alice@mail.com', 'placeholdertoken'),
('bob', 'hashed_password_2', 'bob@mail.com', 'placeholdertoken2'),
('charlie', 'hashed_password_3', 'charlie@mail.com', 'placeholdertoken3');

INSERT INTO contacts (owner_id, first_name, last_name, email, phone) VALUES
(1, 'John', 'Doe', 'jd@gmail.com', '1234567890'),
(1, 'Jane', 'Smith', 'js@gmail.com', '0987654321'),
(2, 'Emily', 'Davis', 'ed@gmail.com', '5555555555');