INSERT INTO Person (person_ID, name, password, email, contact_number) VALUES
(1, 'admin', '21232f297a57a5a743894a0e4a801fc3', '24100314@usc.edu.ph', '09991258600'),
(2, 'driver', 'e2d45d57c7e2941b65c6ccd64af4223e', 'alissamay071006@gmail.com', '09991458600'),
(3, 'user', 'ee11cbb19052e40b07aac0ca060c23ee', 'nexieplays@gmail.com', '09991558601');

INSERT INTO Employee (employee_ID) VALUES
(1),
(2);

INSERT INTO Driver (driver_ID) VALUES (2);

INSERT INTO Manager (manager_ID) VALUES (1);

INSERT INTO Locations (location_name, location_address) VALUES 
('SM City Cebu', 'Cebu City, Cebu'),
('Ayala Center Cebu', 'Cebu City, Cebu'),
('Robinsons Galleria Cebu', 'Cebu City, Cebu'),
('Cebu IT Park', 'Cebu City, Cebu'),
('Basilica del Santo Niño', 'Cebu City, Cebu'),
('Magellan''s Cross', 'Cebu City, Cebu'),
('Fort San Pedro', 'Cebu City, Cebu'),
('Taoist Temple', 'Cebu City, Cebu'),
('Casa Gorordo Museum', 'Cebu City, Cebu'),
('Simala Shrine', 'Cebu City, Cebu'),
('Temple of Leah', 'Cebu City, Cebu'),
('Sirao Flower Farm', 'Cebu City, Cebu'),
('Tops Lookout', 'Cebu City, Cebu'),
('Lapu-Lapu Shrine', 'Lapu-Lapu City, Cebu');

-- 1. Insert a Person (basic user info)
INSERT INTO Person (name, password, email, contact_number)
VALUES ('Juan Dela Cruz', 'securepass123', '24101922@usc.edu.ph', '09171234567');

-- 2. Insert a Payment (you can add more details if needed)
INSERT INTO Payment (payment_method, down_payment, payment_status)
VALUES ('GCash', 500.00, TRUE);

-- 3. Insert an Itinerary
INSERT INTO Itinerary (price, type)
VALUES (1500.00, 'PACKAGE');

-- 4. Insert a Customer using the person_ID and payment_ID
-- Assume person_ID = 1 and payment_ID = 1 from above inserts
INSERT INTO Customer (
    customer_ID, itinerary_ID, payment_ID, passenger_count, date_of_arrival,
    number_of_luggage, pick_up_location, pickup_time, ID_Picture
)
VALUES (
    1, 1, 1, 3, '2025-08-01', 2, '123 Main St, Manila', '10:00:00', NULL
);

-- 5. Insert a Driver
INSERT INTO Person (name, password, email, contact_number)
VALUES ('Pedro Driver', 'driverpass', 'driver@example.com', '09998887777');

INSERT INTO Employee (employee_ID)
VALUES (2);

INSERT INTO Driver (driver_ID, plate_number, availability)
VALUES (2, 'ABC1234', TRUE);

-- 6. Insert an Order_Details entry
INSERT INTO Order_Details (
    driver_ID, customer_ID, itinerary_ID, payment_ID, number_of_PAX,
    date_of_travel, time_for_pickup, time_for_dropoff, status
)
VALUES (
    2, 1, 1, 1, 3,
    '2025-08-01', '10:00:00', '12:30:00', 'PENDING'
);

-- 1. Insert a Person
INSERT INTO Person (name, password, email, contact_number)
VALUES ('Manager One', 'pass123', 'manager1@example.com', '09171234567');

-- 2. Make that Person an Employee
INSERT INTO Employee (employee_ID)
SELECT person_ID FROM Person WHERE email = 'manager1@example.com';

-- 3. Make that Employee a Manager
INSERT INTO Manager (manager_ID)
SELECT employee_ID FROM Employee WHERE employee_ID = (
    SELECT person_ID FROM Person WHERE email = 'manager1@example.com'
);

-- 4. Insert corresponding Itinerary rows (type = 'PACKAGE')
INSERT INTO Itinerary (price, type) VALUES (4999.99, 'PACKAGE');
INSERT INTO Itinerary (price, type) VALUES (7499.00, 'PACKAGE');
