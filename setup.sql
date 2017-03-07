CREATE DATABASE trimet;
USE trimet;
CREATE TABLE locations (id serial PRIMARY KEY, latitude DECIMAL(8, 5), longitude DECIMAL(8, 5), description VARCHAR(255), stop_id INT);
CREATE TABLE legs (id serial PRIMARY KEY, itinerary_id BIGINT, leg_number INT, mode VARCHAR(255), route_number VARCHAR(255), route_name VARCHAR(255), `order` VARCHAR(255), start_time DATETIME, end_time DATETIME, distance DECIMAL(5, 2), stop_sequence INT, from_id BIGINT, to_id BIGINT);
CREATE TABLE itineraries (id serial PRIMARY KEY, distance DECIMAL(5, 2), start_time DATETIME, end_time DATETIME);


# POPULATE
USE trimet;
INSERT INTO locations (latitude, longitude, description, stop_id) VALUES (45.58757, -122.5931, "Portland Int'l Airport MAX Station", 10579);
INSERT INTO locations (latitude, longitude, description, stop_id) VALUES (45.519125, -122.678982, "Pioneer Square North MAX Station", 8383);
INSERT INTO locations (latitude, longitude, description, stop_id) VALUES (45.435653, -122.567867, "Clackamas Town Center TC MAX Station", 13132);
