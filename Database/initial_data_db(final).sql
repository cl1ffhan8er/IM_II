INSERT INTO Person (person_ID, name, password, email, contact_number) VALUES
(1, 'manager_arcilla', '21232f297a57a5a743894a0e4a801fc3', '24100314@usc.edu.ph', '09991258601'),
(2, 'manager_jao', '21232f297a57a5a743894a0e4a801fc3', '24101922@usc.edu.ph', '09991258602'),
(3, 'manager_ariosa', '21232f297a57a5a743894a0e4a801fc3', '24100315@usc.edu.ph', '09991258603'),
(4, 'manager_tan', '21232f297a57a5a743894a0e4a801fc3', '24100316@usc.edu.ph', '09991258604'),
(5, 'driver_api', 'e2d45d57c7e2941b65c6ccd64af4223e', 'alissamay071006@gmail.com', '09991458605'),
(6, 'driver_ngujo', 'e2d45d57c7e2941b65c6ccd64af4223e', 'alissamay071007@gmail.com', '09991458606'),
(7, 'driver_garrote', 'e2d45d57c7e2941b65c6ccd64af4223e', 'alissamay071008@gmail.com', '09991458607'),
(8, 'user_columbina', 'ee11cbb19052e40b07aac0ca060c23ee', 'nexieplays1@gmail.com', '09991558608'),
(9, 'user_sunday', 'ee11cbb19052e40b07aac0ca060c23ee', 'nexieplays2@gmail.com', '09991558609'),
(10, 'user_aglaea', 'ee11cbb19052e40b07aac0ca060c23ee', 'nexieplays3@gmail.com', '099915586010');

INSERT INTO Employee (employee_ID) VALUES (1), (2), (3), (4), (5), (6), (7);
INSERT INTO Manager (manager_ID) VALUES (1), (2), (3), (4);
INSERT INTO Driver (driver_ID, plate_number, Availability) VALUES
(5, 'ABC123', FALSE),
(6, 'XYZ456', FALSE),
(7, 'LMN789', FALSE);

INSERT INTO Locations (location_name, location_address) VALUES 
('SM City Cebu', 'Juan Luna Avenue, North Reclamation Area, Cebu City, Cebu'),
('Ayala Center Cebu', 'Cebu Business Park, Archbishop Reyes Avenue, Cebu City, Cebu'),
('Robinsons Galleria Cebu', 'Gen. Maxilom Avenue Extension, North Reclamation Area, Cebu City, Cebu'),
('Cebu IT Park', 'Jose Maria del Mar Street, Apas, Cebu City, Cebu'),
('Basilica del Santo Niño', 'Osmeña Boulevard, Barangay 25, Cebu City, Cebu'),
('Magellan''s Cross', 'P. Burgos Street, Barangay 25, Cebu City, Cebu'),
('Fort San Pedro', 'A. Pigafetta Street, Plaza Independencia, Cebu City, Cebu'),
('Taoist Temple', 'Beverly Hills Subdivision, Lahug, Cebu City, Cebu'),
('Casa Gorordo Museum', '35 Eduardo Aboitiz Street, Parian, Cebu City, Cebu'),
('Simala Shrine', 'Marian Hills, Lindogon, Sibonga, Cebu'),
('Temple of Leah', 'Cebu Transcentral Highway, Busay, Cebu City, Cebu'),
('Sirao Flower Farm', 'Cebu Transcentral Highway, Sirao, Cebu City, Cebu'),
('Tops Lookout', 'Cebu Tops Road, Busay, Cebu City, Cebu'),
('Lapu-Lapu Shrine', 'Mactan Shrine, Punta Engaño Road, Lapu-Lapu City, Cebu');

INSERT INTO Itinerary (itinerary_ID, price, type) VALUES
(1, 1999, 'PACKAGE'),
(2, 1399, 'PACKAGE'),
(3, 1999, 'PACKAGE');

INSERT INTO Package_Itinerary (
    package_ID, package_name, inclusions, number_of_PAX, route, description, is_made_by_manager, is_available, package_picture
) VALUES 
(
    1,
    'Oslob Tour',
    'Transportation Joiners, Boat, Snorkeling Gadgets, Life Jackets, Entrance Fees, Parking Fees, Pick Up Hotel Accommodation within Cebu City / Mandaue City - SM City Traveller Lounger - Parkmall, Drop Off SM City Cebu - Guest Option',
    12,
    'Whale Watching - Swim with the Whale Shark, Sumilon Island, Tumalog Falls, Cuartel Ruins, Boljoon Church, Torta Pasalubong Stop, Simala Church, Carcar Chicharon Pasalubong Stop',
    'Experience the wonders of Southern Cebu with this all-in-one adventure! Enjoy whale watching and swimming with the gentle giants, explore the historic Cuartel Ruins and Boljoon Church, take an optional dip at the refreshing Tumalog Falls, and visit the iconic Simala Church. Cap off your trip with tasty pasalubong stops for Torta in Argao and Chicharon in Carcar—perfect souvenirs of an unforgettable journey!',
    1,
    TRUE,
    '_______________'
),
(
    2,
    'Moalboal Tour',
    'Private Tour, Car, Driver, Fuel, Pick-up/Drop-off Airport, Seaport Transfer to Accommodation w/ in the Tour',
    12,
    'Panagsama Beach, Sardines Run, Turtle Point, Mantayupan Falls, Kawasan Falls, Kawasan Canyoneering',
    'Experience the wonders of Southern Cebu with this all-in-one adventure! Enjoy whale watching and swimming with the gentle giants, explore the historic Cuartel Ruins and Boljoon Church, take an optional dip at the refreshing Tumalog Falls, and visit the iconic Simala Church. Cap off your trip with tasty pasalubong stops for Torta in Argao and Chicharon in Carcar—perfect souvenirs of an unforgettable journey!',
    2,
    TRUE,
    '_______________'
),
(
    3,
    'Cebu Travel Promo',
    'Private Tour, Car, Driver, Fuel, Pick-up/Drop-off Airport, Seaport Transfer to Accommodtion w/ in the Tour',
    12,
    'Sirao Garden, Temple of Leah, Taiost Temple, Cebu Heritage Park, Ancestral House, Santo Nino De Cebu, Magellans Cross, Pasalubong Center Shamrock, Tabuan Market, CCLEX Bridge, 10K Roses, House of Lechon',
    'Experience the wonders of Southern Cebu with this all-in-one adventure! Enjoy whale watching and swimming with the gentle giants, explore the historic Cuartel Ruins and Boljoon Church, take an optional dip at the refreshing Tumalog Falls, and visit the iconic Simala Church. Cap off your trip with tasty pasalubong stops for Torta in Argao and Chicharon in Carcar—perfect souvenirs of an unforgettable journey!',
    3,
    TRUE,
    '_______________'
);

INSERT INTO Payment (payment_ID, payment_method, down_payment, payment_status) VALUES
(1, 'CASH', 1000.00, 'PARTIALLY PAID'),
(2, 'CASH', 11192.00, 'FULLY PAID'),
(3, 'CASH', 1000.00, 'PARTIALLY PAID');

INSERT INTO Customer (customer_ID, payment_ID, number_of_PAX, date_of_travel, number_of_luggage, ID_Picture) VALUES
(8, 1, 12, '2025-08-15', 12, NULL),
(9, 2, 8, '2025-08-17', 7, NULL),
(10, 3, 12, '2025-08-20', 12, NULL);

INSERT INTO Van (plate_number, driver_ID, customer_ID, payment_ID, passenger_capacity) VALUES
('ABC123', 5, 8, 1, 12),
('XYZ456', 6, 9, 2, 12),
('LMN789', 7, 10, 3, 12);

INSERT INTO Order_Details (customer_ID, payment_ID, driver_ID, itinerary_ID, number_of_PAX, date_of_travel, time_for_pickup, time_for_dropoff, status) VALUES
(8, 1, 5, 1, 4, '2025-08-15', '06:00:00', '18:00:00', 'ACCEPTED'),
(9, 2, 6, 2, 2, '2025-08-17', '07:30:00', '16:00:00', 'PENDING'),
(10, 3, 7, 3, 3, '2025-08-20', '08:00:00', '19:00:00', 'PENDING');