CREATE TABLE buses (
    id BIGINT IDENTITY(1,1) PRIMARY KEY,
    vehicle_id BIGINT NOT NULL,
    class_bus_id BIGINT NOT NULL,
    company_name NVARCHAR(255) NOT NULL,
    capacity INT NOT NULL,
    created_at DATETIME2 NOT NULL DEFAULT GETDATE(),
    updated_at DATETIME2 NOT NULL DEFAULT GETDATE(),
    FOREIGN KEY (vehicle_id) REFERENCES vehicles(id),
    FOREIGN KEY (class_bus_id) REFERENCES class_buses(id)
);
