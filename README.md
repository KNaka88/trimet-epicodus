# TriMet app

#### Group project for Epicodus, 03.06.2017

#### By Patrick McGreevy, Koji Nakagawa, Dan Lauby, Stella Huayhuaca

## Description

This Silex website shows transit routes using the MAX system.

## Setup/Installation Requirements
1. Change the file name of api_keys_template.php to api_keys.php, and add your Trimet and Google map API keys
2. Set project root as working directory in CLI.
3. Run `$ composer install --prefer-source --no-interaction`.
4. Setup databases.
5. Set document root in MAMP > Preferences to `{PROJECT_ROOT}/web`.
6. Click 'Start Servers' in MAMP.
7. Visit **`localhost:8888`** in web browser.


## Database Setup
```sql
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
```

## Technologies Used

HTML
Bootstrap
JavaScript
jQuery
PHP
MySQL
Silex
Twig
Composer
XML


### License

Copyright (c) 2017 _**Patrick McGreevy**_, _**Koji Nakagawa**_, _**Dan Lauby**_, _**Stella Huayhuaca**_

This software is licensed under the MIT license.
