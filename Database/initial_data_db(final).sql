INSERT INTO Person (person_ID, name, password, email, contact_number) VALUES
(1, 'manager_arcilla', '21232f297a57a5a743894a0e4a801fc3', '24100314@usc.edu.ph', '09991258601'),
(2, 'manager_jao', '21232f297a57a5a743894a0e4a801fc3', '24101922@usc.edu.ph', '09991258602'),
(3, 'manager_ariosa', '21232f297a57a5a743894a0e4a801fc3', '24100315@usc.edu.ph', '09991258603'),
(4, 'manager_tan', '21232f297a57a5a743894a0e4a801fc3', '24100316@usc.edu.ph', '09991258604'),
(11, 'manager_lopez', '21232f297a57a5a743894a0e4a801fc3', '24100317@usc.edu.ph', '09991258611'),
(5, 'driver_api', 'e2d45d57c7e2941b65c6ccd64af4223e', 'alissamay071006@gmail.com', '09991458605'),
(6, 'driver_ngujo', 'e2d45d57c7e2941b65c6ccd64af4223e', 'alissamay071007@gmail.com', '09991458606'),
(7, 'driver_garrote', 'e2d45d57c7e2941b65c6ccd64af4223e', 'alissamay071008@gmail.com', '09991458607'),
(12, 'driver_mendoza', 'e2d45d57c7e2941b65c6ccd64af4223e', 'alissamay071009@gmail.com', '09991458612'),
(13, 'driver_valencia', 'e2d45d57c7e2941b65c6ccd64af4223e', 'alissamay071010@gmail.com', '09991458613'),
(8, 'user_columbina', 'ee11cbb19052e40b07aac0ca060c23ee', 'nexieplays1@gmail.com', '09991558608'),
(9, 'user_sunday', 'ee11cbb19052e40b07aac0ca060c23ee', 'nexieplays2@gmail.com', '09991558609'),
(10, 'user_aglaea', 'ee11cbb19052e40b07aac0ca060c23ee', 'nexieplays3@gmail.com', '099915586010'),
(14, 'user_hello', 'ee11cbb19052e40b07aac0ca060c23ee', 'nexieplays4@gmail.com', '09991558614'),
(15, 'user_luna', 'ee11cbb19052e40b07aac0ca060c23ee', 'nexieplays5@gmail.com', '09991558615'),
(16, 'user_ray', 'ee11cbb19052e40b07aac0ca060c23ee', 'nexieplays6@gmail.com', '09991558616'),
(17, 'user_star', 'ee11cbb19052e40b07aac0ca060c23ee', 'nexieplays7@gmail.com', '09991558617'),
(18, 'user_echo', 'ee11cbb19052e40b07aac0ca060c23ee', 'nexieplays8@gmail.com', '09991558618'),
(19, 'user_gale', 'ee11cbb19052e40b07aac0ca060c23ee', 'nexieplays9@gmail.com', '09991558619'),
(20, 'user_nova', 'ee11cbb19052e40b07aac0ca060c23ee', 'nexieplays10@gmail.com', '09991558620');

INSERT INTO Employee (employee_ID) VALUES (1), (2), (3), (4), (5), (6), (7),(11),(12),(13);
INSERT INTO Manager (manager_ID) VALUES (1), (2), (3), (4), (11);
INSERT INTO Driver (driver_ID, plate_number, Availability) VALUES
(5, 'ABC123', FALSE),
(6, 'XYZ456', FALSE),
(7, 'LMN789', FALSE),
(12, 'DEF321', TRUE),
(13, 'GHI654', TRUE);

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
('Lapu-Lapu Shrine', 'Mactan Shrine, Punta Engaño Road, Lapu-Lapu City, Cebu'),
('Cebu Ocean Park', 'SRP, Cebu City, Cebu'),
('Mountain View Nature Park', 'Busay, Cebu City, Cebu'),
('Sky Experience Adventure', 'Crown Regency Hotel, Osmeña Blvd, Cebu City'),
('Carbon Market', 'M.C. Briones St., Cebu City, Cebu'),
('Cebu Zoo', 'Barangay Kalunasan, Cebu City, Cebu'),
('Cebu Happy World Museum', 'Gabi Road, Cordova, Cebu');

INSERT INTO Itinerary (itinerary_ID, price, type) VALUES
(1, 1999, 'PACKAGE'),
(2, 1399, 'PACKAGE'),
(3, 1999, 'PACKAGE'),
(4, 1899, 'PACKAGE'),
(5, 1599, 'PACKAGE'),
(6, 1499, 'PACKAGE'),
(7, 2099, 'PACKAGE'),
(8, 1299, 'PACKAGE'),
(9, 1799, 'PACKAGE'),
(10, 1199, 'PACKAGE'),
(11, 1399, 'PACKAGE'),
(12, 1599, 'PACKAGE'),
(13, 1699, 'PACKAGE'),
(14, 1799, 'PACKAGE'),
(15, 1499, 'PACKAGE'),
(16, 1899, 'PACKAGE'),
(17, 1299, 'PACKAGE'),
(18, 1199, 'PACKAGE'),
(19, 1099, 'PACKAGE'),
(20, 1999, 'PACKAGE');;

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
    'package-images/package1.png'
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
    'package-images/package1.png'
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
    'package-images/package2.png'
),
(4, 'North Cebu Explorer', 'Van, Fuel, Snacks', 10, 'Cebu Zoo, Mountain View, Temple of Leah', 'Discover North Cebu’s nature & culture.', 4, TRUE, 'package-images/package3.png'),
(5, 'Cebu Museum Trail', 'Van, Tour Guide, Entrance Fees', 8, 'Casa Gorordo, Museo Sugbo, Cathedral Museum', 'A dive into Cebuano history.', 3, TRUE, 'package-images/package4.png'),
(6, 'Lapu-Lapu Discovery', 'Car, Guide, Fuel', 6, 'Lapu-Lapu Shrine, 10K Roses', 'Explore historic Mactan.', 2, TRUE, 'package-images/package5.png'),
(7, 'City Tour Deluxe', 'Private Van, All-access Pass', 12, 'Carbon Market, Heritage Park, Magellan’s Cross', 'Urban adventure.', 1, TRUE, 'package-images/package6.png'),
(8, 'Tops Night View', 'Night Ride, Dinner', 5, 'Tops Lookout, Busay Hills', 'Romantic Cebu night view.', 11, TRUE, 'package-images/package7.png'),
(9, 'Heritage & Pasalubong', 'Van, Guide, Pasalubong Bag', 9, 'Shamrock, Tabuan Market, Taboan', 'Get all the goodies!', 1, TRUE, 'package-images/package8.png'),
(10, 'Sirao and Flowers', 'Garden Access, Souvenirs', 6, 'Sirao Garden, Temple of Leah', 'Perfect for IG moments.', 2, TRUE, 'package-images/package9.png'),
(11, 'Cordova Escape', 'Van, Museum Entry', 8, 'Cebu Happy World Museum', 'Surreal art & fun.', 3, TRUE, 'package-images/package10.png'),
(12, 'Wildlife Safari', 'Zoo Access, Picnic Basket', 7, 'Cebu Zoo, Mountain View', 'Family fun in nature.', 4, TRUE, 'package-images/package11.png'),
(13, 'Cultural Fiesta', 'Guide, Native Snacks', 10, 'Fort San Pedro, Basilica', 'Live local, love local.', 2, TRUE, 'package-images/package12.png'),
(14, 'Ocean View Express', 'Boat Tour, Souvenir', 11, 'Cebu Ocean Park, SRP Walk', 'Waves & wonders.', 1, TRUE, 'package-images/package13.png'),
(15, 'Museum Marathon', 'All Pass, Van, Guide', 5, 'All museums in Cebu City', 'History buffs dream.', 3, TRUE, 'package-images/package14.png'),
(16, 'Tropical Adventure', 'Beach Towel, Van', 9, 'Moalboal, Panagsama Beach', 'Chill and thrill.', 4, TRUE, 'package-images/package15.png'),
(17, 'Falls and Fun', 'Safety Gear, Lunch', 12, 'Kawasan Falls, Mantayupan Falls', 'Nature’s shower.', 2, TRUE, 'package-images/package16.png'),
(18, 'Hillside Escape', 'Tour Kit, Snacks', 6, 'Busay, Tops, Sirao', 'Cool and calm.', 1, TRUE, 'package-images/package17.png'),
(19, 'Night Lights Tour', 'Dinner Cruise, Lights Show', 8, 'CCLEX, 10K Roses', 'Cebu shines at night.', 11, TRUE, 'package-images/package7.png'),
(20, 'Faith & History', 'Church Access, Lunch', 10, 'Simala Shrine, Boljoon Church', 'Soulful and scenic.', 3, TRUE, 'package-images/package19.png');;

INSERT INTO Payment (payment_ID, payment_method, down_payment, payment_status) VALUES
(1, 'CASH', 1000.00, 'PARTIALLY PAID'),
(2, 'CASH', 11192.00, 'FULLY PAID'),
(3, 'CASH', 1000.00, 'PARTIALLY PAID'),
(4, 'GCASH', 1999.00, 'FULLY PAID'),
(5, 'CASH', 500.00, 'PARTIALLY PAID'),
(6, 'CASH', 0.00, 'NOT PAID'),
(7, 'GCASH', 1000.00, 'PARTIALLY PAID'),
(8, 'CASH', 1399.00, 'FULLY PAID'),
(9, 'GCASH', 1000.00, 'PARTIALLY PAID'),
(10, 'CASH', 1399.00, 'FULLY PAID'),
(11, 'CASH', 1999.00, 'FULLY PAID'),
(12, 'CASH', 800.00, 'PARTIALLY PAID'),
(13, 'GCASH', 0.00, 'NOT PAID'),
(14, 'CASH', 1500.00, 'PARTIALLY PAID'),
(15, 'GCASH', 1999.00, 'FULLY PAID'),
(16, 'CASH', 1000.00, 'PARTIALLY PAID'),
(17, 'CASH', 0.00, 'NOT PAID');;

INSERT INTO Customer (customer_ID, payment_ID, number_of_PAX, date_of_travel, number_of_luggage, ID_Picture) VALUES
(8, 1, 12, '2025-08-15', 12, NULL),
(9, 2, 8, '2025-08-17', 7, NULL),
(10, 3, 12, '2025-08-20', 12, NULL),
(14, 4, 3, '2025-08-22', 2, NULL),
(15, 5, 2, '2025-08-23', 1, NULL),
(16, 6, 5, '2025-08-24', 3, NULL),
(17, 7, 6, '2025-08-25', 4, NULL),
(18, 8, 10, '2025-08-26', 6, NULL),
(19, 9, 4, '2025-08-27', 2, NULL),
(20, 10, 3, '2025-08-28', 1, NULL);;

INSERT INTO Van (plate_number, driver_ID, customer_ID, payment_ID, passenger_capacity) VALUES
('ABC123', 5, 8, 1, 12),
('XYZ456', 6, 9, 2, 12),
('LMN789', 7, 10, 3, 12),
('DEF321', 12, 14, 4, 12),
('GHI654', 13, 15, 5, 12),
('JKL987', 5, 16, 6, 12),
('MNO321', 6, 17, 7, 12),
('PQR654', 7, 18, 8, 12),
('STU987', 12, 19, 9, 12),
('VWX321', 13, 20, 10, 12);

INSERT INTO Order_Details (customer_ID, payment_ID, driver_ID, itinerary_ID, number_of_PAX, date_of_travel, time_for_pickup, time_for_dropoff, status) VALUES
(8, 1, 5, 1, 4, '2025-08-15', '06:00:00', '18:00:00', 'ACCEPTED'),
(9, 2, 6, 2, 2, '2025-08-17', '07:30:00', '16:00:00', 'PENDING'),
(10, 3, 7, 3, 3, '2025-08-20', '08:00:00', '19:00:00', 'PENDING'),
(14, 4, 12, 4, 3, '2025-08-22', '08:00:00', '17:00:00', 'ACCEPTED'),
(15, 5, 13, 5, 2, '2025-08-23', '09:00:00', '15:00:00', 'PENDING'),
(16, 6, 5, 6, 5, '2025-08-24', '07:30:00', '16:30:00', 'IN MODIFICATION'),
(17, 7, 6, 7, 6, '2025-08-25', '06:00:00', '14:30:00', 'REJECTED'),
(18, 8, 7, 8, 10, '2025-08-26', '08:15:00', '18:00:00', 'PENDING'),
(19, 9, 12, 9, 4, '2025-08-27', '07:00:00', '15:45:00', 'ACCEPTED'),
(20, 10, 13, 10, 3, '2025-08-28', '09:30:00', '17:00:00', 'PENDING'),
(8, 1, 5, 11, 4, '2025-08-29', '07:45:00', '17:15:00', 'PENDING'),
(9, 2, 6, 12, 2, '2025-08-30', '06:30:00', '14:00:00', 'PENDING'),
(10, 3, 7, 13, 3, '2025-08-31', '10:00:00', '18:00:00', 'PENDING'),
(14, 4, 12, 14, 3, '2025-09-01', '08:00:00', '16:30:00', 'IN MODIFICATION'),
(15, 5, 13, 15, 2, '2025-09-02', '07:30:00', '17:00:00', 'ACCEPTED'),
(16, 6, 5, 16, 5, '2025-09-03', '06:45:00', '14:45:00', 'REJECTED'),
(17, 7, 6, 17, 6, '2025-09-04', '08:30:00', '16:30:00', 'PENDING'),
(18, 8, 7, 18, 10, '2025-09-05', '07:00:00', '17:30:00', 'ACCEPTED'),
(19, 9, 12, 19, 4, '2025-09-06', '06:30:00', '15:00:00', 'PENDING'),
(20, 10, 13, 20, 3, '2025-09-07', '09:15:00', '18:00:00', 'PENDING');