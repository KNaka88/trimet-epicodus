CREATE DATABASE trimet;
USE trimet;
CREATE TABLE locations (id serial PRIMARY KEY, latitude FLOAT, longitude FLOAT, description VARCHAR(255));
CREATE TABLE legs (id serial PRIMARY KEY , mode VARCHAR(255), route_number VARCHAR(255), route_name VARCHAR(255), `order` VARCHAR(255), start_time DATETIME, end_time DATETIME, distance FLOAT, stop_sequence INT, from_id BIGINT, to_id BIGINT);
CREATE TABLE itineraries (id serial PRIMARY KEY, distance FLOAT, start_time DATETIME, end_time DATETIME);



# POPULATE
USE trimet;
INSERT INTO locations (latitude, longitude, description) VALUES (45.58757, -122.5931, "Portland Int'l Airport MAX Station");
INSERT INTO locations (latitude, longitude, description) VALUES (45.519125, -122.678982, "Pioneer Square North MAX Station");
INSERT INTO locations (latitude, longitude, description) VALUES (45.435653, -122.567867, "Clackamas Town Center TC MAX Station");
