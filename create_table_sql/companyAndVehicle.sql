CREATE TABLE companies (
    id BIGINT IDENTITY(1,1) PRIMARY KEY,
    name NVARCHAR(255) NOT NULL
);




CREATE TABLE vehicles (
    id BIGINT IDENTITY(1,1) PRIMARY KEY,
    name NVARCHAR(255) NOT NULL,
    company_id BIGINT NOT NULL,
    facilities NVARCHAR(255) NOT NULL,
    type_vehicle_id BIGINT NOT NULL,
    FOREIGN KEY (company_id) REFERENCES companies(id),
    FOREIGN KEY (type_vehicle_id) REFERENCES type_vehicle(id)
);
