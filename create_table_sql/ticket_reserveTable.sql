CREATE TABLE ticket_reserve (
    id BIGINT IDENTITY(1,1) PRIMARY KEY,
    ticket_id BIGINT NOT NULL,
    reservation_id BIGINT NOT NULL,
    created_at DATETIME2 NOT NULL DEFAULT GETDATE(),
    updated_at DATETIME2 NOT NULL DEFAULT GETDATE(),
    FOREIGN KEY (ticket_id) REFERENCES tickets(id),
    FOREIGN KEY (reservation_id) REFERENCES reservations(id)
);
