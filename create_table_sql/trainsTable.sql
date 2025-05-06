CREATE TABLE trains (
    id BIGINT IDENTITY(1,1) PRIMARY KEY,
    vehicle_id BIGINT NOT NULL,
    class_train_id BIGINT NOT NULL,
    component NVARCHAR(MAX) NOT NULL,
    FOREIGN KEY (vehicle_id) REFERENCES vehicles(id),
    FOREIGN KEY (class_train_id) REFERENCES class_trains(id)
);
