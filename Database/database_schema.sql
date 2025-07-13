

CREATE TABLE Person (
    person_ID INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    contact_number VARCHAR(20) UNIQUE NOT NULL
);

CREATE TABLE Employee (
    employee_ID INT PRIMARY KEY,
    FOREIGN KEY (employee_ID) REFERENCES Person(person_ID)
);

CREATE TABLE Manager (
    manager_ID INT PRIMARY KEY, 
    FOREIGN KEY (manager_ID) REFERENCES Employee(employee_ID)
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
    is_custom_made BOOLEAN DEFAULT FALSE
);

CREATE TABLE Payment (
    payment_ID INT PRIMARY KEY AUTO_INCREMENT,
    payment_method VARCHAR(50),
    down_payment DECIMAL(10,2),
    payment_status BOOLEAN DEFAULT FALSE
);


CREATE TABLE Package_Itinerary (
    package_id INT PRIMARY KEY,
    package_name VARCHAR(255),
    is_made_by_manager INT,
    is_available BOOLEAN DEFAULT TRUE,
    package_picture VARCHAR(255),
    description VARCHAR(255),
    FOREIGN KEY (package_id) REFERENCES Itinerary(itinerary_ID),
    FOREIGN KEY (is_made_by_manager) REFERENCES Manager(manager_ID)
);

CREATE TABLE Custom_Itinerary (
    custom_id INT PRIMARY KEY,
    is_made_by_customer INT,
    FOREIGN KEY (custom_id) REFERENCES Itinerary(itinerary_ID),
    FOREIGN KEY (is_made_by_customer) REFERENCES Person(person_ID)
);

CREATE TABLE Itinerary_Stops (
    stop_ID INT PRIMARY KEY AUTO_INCREMENT,
    itinerary_ID INT,
    stop_order INT,
    location_ID INT,
    FOREIGN KEY (itinerary_ID) REFERENCES Itinerary(itinerary_ID),
    FOREIGN KEY (location_ID) REFERENCES Locations(location_ID)
);

CREATE TABLE Booking (
    booking_ID INT PRIMARY KEY AUTO_INCREMENT,
    customer_ID INT NOT NULL,
    itinerary_ID INT NOT NULL,
    payment_ID INT,
    customer_name VARCHAR(255),
    passenger_count INT CHECK (passenger_count BETWEEN 1 AND 10),
    pickup_date DATE,
    number_of_luggage INT,
    pickup_location VARCHAR(255),
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
    booking_ID INT,
    passenger_capacity INT,
    FOREIGN KEY (driver_ID) REFERENCES Driver(driver_ID),
    FOREIGN KEY (booking_ID) REFERENCES Booking(booking_ID)
);

CREATE TABLE Order_Details (
    order_ID INT PRIMARY KEY AUTO_INCREMENT,
    booking_ID INT,
    driver_ID INT,
    payment_ID INT,
    number_of_PAX INT,
    date_of_travel DATE,
    time_for_pickup TIME,
    time_for_dropoff TIME,
    status ENUM('ACCEPTED', 'REJECTED', 'IN MODIFICATION','PENDING') DEFAULT 'PENDING',
    date_of_transaction DATE DEFAULT CURRENT_DATE,
    FOREIGN KEY (booking_ID) REFERENCES Booking(booking_ID),
    FOREIGN KEY (driver_ID) REFERENCES Driver(driver_ID),
    FOREIGN KEY (payment_ID) REFERENCES Payment(payment_ID)
);
