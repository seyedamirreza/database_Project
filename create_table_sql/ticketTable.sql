CREATE TABLE tickets (
    id BIGINT IDENTITY(1,1) PRIMARY KEY,
    vehicle_id BIGINT NOT NULL,
    price INT NOT NULL,
    capacity INT NOT NULL,
    availability INT NOT NULL,
    is_two_way BIT NOT NULL,
    source NVARCHAR(255) NOT NULL,
    destination NVARCHAR(255) NOT NULL,
    arrival_time DATE NOT NULL,
    departure_time DATE NOT NULL,
    FOREIGN KEY (vehicle_id) REFERENCES vehicles(id)
);
