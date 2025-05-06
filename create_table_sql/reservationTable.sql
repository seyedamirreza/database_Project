CREATE TABLE reservations (
    id BIGINT IDENTITY(1,1) PRIMARY KEY,
    ticket_id BIGINT NOT NULL,
    status BIT NOT NULL,
    reserve_time TIME NOT NULL,
    expire_time TIME NOT NULL,
    created_at DATETIME2 NOT NULL DEFAULT GETDATE(),
    updated_at DATETIME2 NOT NULL DEFAULT GETDATE(),
    FOREIGN KEY (ticket_id) REFERENCES tickets(id)
);
