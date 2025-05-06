CREATE TABLE flights (
    id BIGINT IDENTITY(1,1) PRIMARY KEY,
    vehicle_id BIGINT NOT NULL,
    class_air_id BIGINT NOT NULL,
    airline_name NVARCHAR(255) NOT NULL,
    flight_number NVARCHAR(255) NOT NULL,
    source_airport NVARCHAR(255) NOT NULL,
    destination_airport NVARCHAR(255) NOT NULL,
    stops INT NOT NULL,
    FOREIGN KEY (vehicle_id) REFERENCES vehicles(id),
    FOREIGN KEY (class_air_id) REFERENCES class_airs(id)
);
