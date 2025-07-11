CREATE TABLE Person (
    person_ID INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    name VARCHAR(255),
    password VARCHAR(255),
    email VARCHAR(255) UNIQUE,
    contact_number VARCHAR(12) UNIQUE
);

CREATE TABLE Itinerary (
    itinerary_ID INT PRIMARY KEY AUTO_INCREMENT,
    price DECIMAL(10,2),
    type ENUM('PACKAGE', 'CUSTOM') NOT NULL
);

CREATE TABLE Locations (
    location_ID INT AUTO_INCREMENT PRIMARY KEY,
    location_name VARCHAR(255),
    location_address VARCHAR(255),
);

CREATE TABLE Payment (
    payment_ID INT PRIMARY KEY AUTO_INCREMENT,
    payment_method VARCHAR(50),
    down_payment DECIMAL(10,2),
    payment_status BOOLEAN DEFAULT FALSE
);

CREATE TABLE Package_Itinerary (
    package_id int PRIMARY KEY AUTO_INCREMENT,
    package_name VARCHAR(255),
    inclusions VARCHAR(255), -- Added 
    passenger_count INT, -- Added
    description VARCHAR(255),
    is_made_by_manager int,
    is_available BOOLEAN DEFAULT FALSE,
    package_picture VARCHAR(255),
    FOREIGN KEY (package_id) REFERENCES Itinerary(itinerary_ID),
    FOREIGN KEY (is_made_by_manager) REFERENCES Manager(manager_ID)
);

CREATE TABLE Custom_Itinerary (
    custom_ID INT PRIMARY KEY AUTO_INCREMENT,
    is_made_by_customer INT,
    FOREIGN KEY (custom_id) REFERENCES Itinerary(itinerary_ID),
    FOREIGN KEY (is_made_by_customer) REFERENCES Customer(customer_ID)
);

CREATE TABLE Itinerary_Stops (
    stop_ID INT PRIMARY KEY AUTO_INCREMENT,
    custom_ID INT,
    stop_order INT,
    location_ID INT,
    FOREIGN KEY (custom_ID) REFERENCES Itinerary(itinerary_ID),
    FOREIGN KEY (location_ID) REFERENCES Locations(location_ID)
);

CREATE TABLE Employee (
    employee_ID INT PRIMARY KEY,
    FOREIGN KEY (employee_ID) REFERENCES Person(person_ID)
);

CREATE TABLE Manager (
    manager_ID INT PRIMARY KEY, 
    FOREIGN KEY (manager_ID) REFERENCES Employee(employee_ID)
); 

CREATE TABLE Customer (
    customer_ID INT PRIMARY KEY,
    itinerary_ID INT,
    payment_ID INT,
    passenger_count INT CHECK (passenger_count BETWEEN 1 AND 10),
    date_of_arrival DATE,
    number_of_luggage INT,
    pick_up_location VARCHAR(255),
    pickup_time TIME,
    ID_Picture BLOB,
    FOREIGN KEY (customer_ID) REFERENCES Person(person_ID),
    FOREIGN KEY (itinerary_ID) REFERENCES Itinerary(itinerary_ID),
    FOREIGN KEY (payment_ID) REFERENCES Payment(payment_ID)
);

CREATE TABLE Driver (
    driver_ID INT PRIMARY KEY,
    plate_number VARCHAR(20),
    Availability BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (driver_ID) REFERENCES Employee(employee_ID)
);

CREATE TABLE Van (
    plate_number VARCHAR(20) PRIMARY KEY,
    driver_ID INT,
    customer_ID INT,
    passenger_capacity INT,
    FOREIGN KEY (driver_ID) REFERENCES Driver(driver_ID),
    FOREIGN KEY (customer_ID) REFERENCES Customer(customer_ID)
);

CREATE TABLE Order_Details (
    order_ID INT PRIMARY KEY AUTO_INCREMENT,
    driver_ID INT,
    customer_ID INT,
    itinerary_ID INT,
    payment_ID INT,
    number_of_PAX INT,
    date_of_travel DATE,
    time_for_pickup TIME,
    time_for_dropoff TIME,
    status ENUM('ACCEPTED', 'REJECTED', 'IN MODIFICATION','PENDING') DEFAULT 'PENDING',
    date_of_transaction DATE DEFAULT CURRENT_DATE,
    FOREIGN KEY (driver_ID) REFERENCES Driver(driver_ID),
    FOREIGN KEY (customer_ID) REFERENCES Customer(customer_ID),
    FOREIGN KEY (itinerary_ID) REFERENCES Itinerary(itinerary_ID),
    FOREIGN KEY (payment_ID) REFERENCES Payment(payment_ID)
);