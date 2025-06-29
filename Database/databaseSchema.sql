CREATE TABLE Person (
    PersonID INT PRIMARY KEY,
    Name VARCHAR(100),
    ContactDetails VARCHAR(255)
);

CREATE TABLE Employee (
    EmployeeID INT PRIMARY KEY,
    Name VARCHAR(100),
    FOREIGN KEY (EmployeeID) REFERENCES Person(PersonID)
);

CREATE TABLE Manager (
    ManagerID INT PRIMARY KEY,
    FOREIGN KEY (ManagerID) REFERENCES Employee(EmployeeID)
);

CREATE TABLE Itinerary (
    ItineraryID INT PRIMARY KEY,
    RouteDestination VARCHAR(255),
    RouteStart VARCHAR(255),
    RouteDistance DECIMAL(10,2),
    Price DECIMAL(10,2)
);

CREATE TABLE Customer (
    CustomerID INT PRIMARY KEY,
    ItineraryID INT,
    OtherPartyMembersName VARCHAR(255),
    DateOfArrival DATE,
    TimeOfArrival TIME,
    NumberOfLuggage INT,
    PickupLocation VARCHAR(255),
    PickupTime TIME,
    IDPicture BLOB,
    FOREIGN KEY (CustomerID) REFERENCES Person(PersonID),
    FOREIGN KEY (ItineraryID) REFERENCES Itinerary(ItineraryID)
);

CREATE TABLE Driver (
    DriverID INT PRIMARY KEY,
    PlateNumber VARCHAR(20),
    Availability BOOLEAN,
    FOREIGN KEY (DriverID) REFERENCES Employee(EmployeeID)
);

CREATE TABLE Van (
    PlateNumber VARCHAR(20) PRIMARY KEY,
    DriverID INT,
    CustomerID INT,
    CarModel VARCHAR(50),
    PassengerCapacity INT,
    FOREIGN KEY (DriverID) REFERENCES Driver(DriverID),
    FOREIGN KEY (CustomerID) REFERENCES Customer(CustomerID)
);

CREATE TABLE Payment (
    PaymentID INT PRIMARY KEY,
    PaymentMethod VARCHAR(50),
    DownPayment DECIMAL(10,2),
    PaymentStatus VARCHAR(50)
);

CREATE TABLE OrderDetails (
    OrderID INT PRIMARY KEY,
    DriverID INT,
    CustomerID INT,
    ItineraryID INT,
    PaymentID INT,
    NumberOfPAX INT,
    DateOfTravel DATE,
    TimeForPickup TIME,
    TimeForDropOff TIME,
    Duration TIME,
    OverallPrice DECIMAL(10,2),
    FOREIGN KEY (DriverID) REFERENCES Driver(DriverID),
    FOREIGN KEY (CustomerID) REFERENCES Customer(CustomerID),
    FOREIGN KEY (ItineraryID) REFERENCES Itinerary(ItineraryID),
    FOREIGN KEY (PaymentID) REFERENCES Payment(PaymentID)
);

