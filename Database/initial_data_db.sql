-- ===================================================================
-- INITIAL SETUP: USERS & LOCATIONS
-- ===================================================================

-- Insert initial users (Admin, Driver, Basic User)
INSERT INTO Person (person_ID, name, password, email, contact_number) VALUES
(1, 'manager', '21232f297a57a5a743894a0e4a801fc3', '24100314@usc.edu.ph', '09991258600'),
(2, 'driver', 'e2d45d57c7e2941b65c6ccd64af4223e', 'alissamay071006@gmail.com', '09991458600'),
(3, 'user', 'ee11cbb19052e40b07aac0ca060c23ee', 'nexieplays@gmail.com', '09991558601');

-- Assign roles to the initial users
INSERT INTO Employee (employee_ID) VALUES
(1),
(2);

-- Define who is a Driver (Assuming a Driver table exists)
INSERT INTO Driver (driver_ID) VALUES (2);

-- Define who is a Manager
INSERT INTO Manager (manager_ID) VALUES (1);

-- Insert predefined tour locations
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
('Simala Shrine', 'Sibonga, Cebu'),
('Temple of Leah', 'Cebu City, Cebu'),
('Sirao Flower Farm', 'Cebu City, Cebu'),
('Tops Lookout', 'Cebu City, Cebu'),
('Lapu-Lapu Shrine', 'Lapu-Lapu City, Cebu');

INSERT INTO Itinerary (price, type) VALUES (1800.00, 'PACKAGE');
SET @last_id = LAST_INSERT_ID();
INSERT INTO Package_Itinerary (package_id, package_name, is_made_by_manager, description, package_picture) VALUES
(@last_id, 'Cebu City Historical Journey', 1, 'A half-day tour of Cebu''s most iconic historical landmarks.', 'package-images/package1.png');

INSERT INTO Itinerary (price, type) VALUES (3500.00, 'PACKAGE');
SET @last_id = LAST_INSERT_ID();
INSERT INTO Package_Itinerary (package_id, package_name, is_made_by_manager, description, package_picture) VALUES
(@last_id, 'Oslob Whale Shark Encounter', 1, 'An unforgettable day swimming with the gentle giants of Oslob.', 'package-images/package2.png');

