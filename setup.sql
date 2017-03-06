CREATE DATABASE trimet;
USE trimet;
CREATE TABLE locations (id serial PRIMARY KEY, latitude FLOAT, longitude FLOAT, description VARCHAR(255));


# POPULATE
USE trimet;
INSERT INTO locations (latitude, longitude, description) VALUES (45.58757, -122.5931, "Portland Int'l Airport MAX Station");
INSERT INTO locations (latitude, longitude, description) VALUES (45.519125, -122.678982, "Pioneer Square North MAX Station");
