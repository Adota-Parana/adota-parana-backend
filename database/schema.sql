SET foreign_key_checks = 0;

DROP TABLE IF EXISTS users;
DROP TABLE IF EXISTS species;
DROP TABLE IF EXISTS pets;
DROP TABLE IF EXISTS pet_images;
DROP TABLE IF EXISTS interesting;
DROP TABLE IF EXISTS reports;
DROP TABLE IF EXISTS moderation;

CREATE TABLE users (
  id INT NOT NULL AUTO_INCREMENT,
  name varchar(70) NOT NULL,
  email varchar(50) NOT NULL,
  encrypted_password varchar(64) NOT NULL,
  phone varchar(20) DEFAULT NULL,
  role varchar(10) NOT NULL DEFAULT 'user',
  PRIMARY KEY (id),
  UNIQUE KEY email (email)
);

CREATE TABLE species (
  id INT NOT NULL AUTO_INCREMENT,
  name varchar(50) NOT NULL,
  PRIMARY KEY (id)
);

CREATE TABLE pets (
  id INT NOT NULL AUTO_INCREMENT,
  specie_id INT NOT NULL,
  user_id INT NOT NULL,
  name varchar(25) NOT NULL,
  birth_date date DEFAULT NULL,
  sex char(1) DEFAULT NULL,
  is_vaccinated boolean DEFAULT NULL,
  is_neutered boolean DEFAULT NULL,
  description varchar(300) DEFAULT NULL,
  status varchar(25) NOT NULL,
  post_date datetime NOT NULL,
  PRIMARY KEY (id),
  FOREIGN KEY (specie_id) REFERENCES species (id),
  FOREIGN KEY (user_id) REFERENCES users (id)
);

CREATE TABLE pet_images (
  id INT NOT NULL AUTO_INCREMENT,
  pet_id INT NOT NULL,
  image MEDIUMBLOB,
  PRIMARY KEY (id),
  FOREIGN KEY (pet_id) REFERENCES pets (id)
);

CREATE TABLE interesting (
  user_id INT NOT NULL,
  pet_id INT NOT NULL,
  interested_date datetime NOT NULL,
  status varchar(20) NOT NULL,
  message varchar(300) DEFAULT NULL,
  PRIMARY KEY (user_id, pet_id),
  FOREIGN KEY (user_id) REFERENCES users (id),
  FOREIGN KEY (pet_id) REFERENCES pets (id)
);

CREATE TABLE reports (
  id INT NOT NULL AUTO_INCREMENT,
  pet_id INT NOT NULL,
  report_date datetime NOT NULL,
  PRIMARY KEY (id),
  FOREIGN KEY (pet_id) REFERENCES pets (id)
);

CREATE TABLE moderation (
  id INT NOT NULL AUTO_INCREMENT,
  report_id INT NOT NULL,
  admin_id INT NOT NULL,
  justify varchar(300) NOT NULL,
  date_aval datetime NOT NULL,
  pet_removed boolean NOT NULL,
  PRIMARY KEY (id),
  FOREIGN KEY (report_id) REFERENCES reports (id),
  FOREIGN KEY (admin_id) REFERENCES users (id)
);

SET foreign_key_checks = 1;
