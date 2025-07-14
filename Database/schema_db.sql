CREATE TABLE Itinerary (
    itinerary_ID INT PRIMARY KEY AUTO_INCREMENT,
    price DECIMAL(10,2),
    type ENUM('PACKAGE', 'CUSTOM') NOT NULL
); -- Done // Custom is for Yeah - NO NEED

CREATE TABLE Locations (
    location_ID INT AUTO_INCREMENT PRIMARY KEY,
    location_name VARCHAR(255),
    location_address VARCHAR(255),
    is_custom_made BOOLEAN DEFAULT FALSE
); -- Done

CREATE TABLE Payment (
    payment_ID INT PRIMARY KEY AUTO_INCREMENT,
    payment_method ENUM('CASH','GCASH') NOT NULL,
    down_payment DECIMAL(10,2),
    payment_status ENUM('FULLY PAID','PARTIALLY PAID','NOT PAID') NOT NULL
); -- Done

CREATE TABLE Person (
    person_ID INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    name VARCHAR(255),
    password VARCHAR(255),
    email VARCHAR(255) UNIQUE,
    contact_number VARCHAR(12) UNIQUE
); -- Done

CREATE TABLE Employee (
    employee_ID INT PRIMARY KEY,
    FOREIGN KEY (employee_ID) REFERENCES Person(person_ID)
); -- Done

CREATE TABLE Manager (
    manager_ID INT PRIMARY KEY, 
    FOREIGN KEY (manager_ID) REFERENCES Employee(employee_ID)
); -- DOne

CREATE TABLE Driver (
    driver_ID INT PRIMARY KEY,
    plate_number VARCHAR(20),
    Availability BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (driver_ID) REFERENCES Employee(employee_ID)
); -- Done

CREATE TABLE Package_Itinerary (
    package_ID int PRIMARY KEY AUTO_INCREMENT,
    package_name VARCHAR(255),
    inclusions VARCHAR(255),
    number_of_PAX int,
    route VARCHAR(255),
    description VARCHAR(255),
    is_made_by_manager int,
    is_available BOOLEAN DEFAULT FALSE,
    package_picture VARCHAR(255),
    FOREIGN KEY (package_id) REFERENCES Itinerary(itinerary_ID),
    FOREIGN KEY (is_made_by_manager) REFERENCES Manager(manager_ID)
); -- Done

CREATE TABLE Custom_Itinerary (
    custom_ID INT PRIMARY KEY AUTO_INCREMENT,
    is_made_by_customer INT,
    FOREIGN KEY (custom_id) REFERENCES Itinerary(itinerary_ID),
    FOREIGN KEY (is_made_by_customer) REFERENCES Person(person_ID)
); -- NO NEED - Created by Customer

CREATE TABLE Itinerary_Stops (
    stop_ID INT PRIMARY KEY AUTO_INCREMENT,
    custom_ID INT,
    stop_order INT,
    location_ID INT,
    FOREIGN KEY (custom_ID) REFERENCES Itinerary(itinerary_ID),
    FOREIGN KEY (location_ID) REFERENCES Locations(location_ID)
); -- NO NEED - Created by Customer

CREATE TABLE Customer (
    customer_ID INT,
    payment_ID INT,
    number_of_PAX INT,
    date_of_travel DATE,
    number_of_luggage INT,
    ID_Picture BLOB,
    PRIMARY KEY (customer_ID, payment_ID),
    FOREIGN KEY (customer_ID) REFERENCES Person(person_ID),
    FOREIGN KEY (payment_ID) REFERENCES Payment(payment_ID)
); -- Done

CREATE TABLE Van (
    plate_number VARCHAR(20) PRIMARY KEY,
    driver_ID INT,
    customer_ID INT,
    payment_ID INT,
    passenger_capacity INT,
    FOREIGN KEY (driver_ID) REFERENCES Driver(driver_ID),
    FOREIGN KEY (customer_ID, payment_ID) REFERENCES Customer(customer_ID, payment_ID)
); -- Done

CREATE TABLE Order_Details (
    order_ID INT PRIMARY KEY AUTO_INCREMENT,
    customer_ID INT,
    payment_ID INT,
    driver_ID INT,
    itinerary_ID INT,
    number_of_PAX INT,
    date_of_travel DATE,
    time_for_pickup TIME,
    time_for_dropoff TIME,
    date_of_transaction DATE DEFAULT CURRENT_DATE,
    status ENUM('ACCEPTED', 'REJECTED', 'IN MODIFICATION','PENDING') DEFAULT 'PENDING',
    FOREIGN KEY (customer_ID, payment_ID) REFERENCES Customer(customer_ID, payment_ID),
    FOREIGN KEY (driver_ID) REFERENCES Driver(driver_ID),
    FOREIGN KEY (itinerary_ID) REFERENCES Itinerary(itinerary_ID)
); -- Done