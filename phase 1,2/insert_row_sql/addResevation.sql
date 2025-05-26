INSERT INTO reservations (ticket_id, status, reserve_time, expire_time, created_at, updated_at) VALUES
(1, 1, '08:00:00', '10:00:00', GETDATE(), GETDATE()),
(2, 1, '09:00:00', '11:00:00', GETDATE(), GETDATE()),
(3, 1, '10:00:00', '12:00:00', GETDATE(), GETDATE()),
(4, 1, '11:00:00', '13:00:00', GETDATE(), GETDATE()),
(5, 1, '12:00:00', '14:00:00', GETDATE(), GETDATE()),
(6, 1, '13:00:00', '15:00:00', GETDATE(), GETDATE()),
(7, 0, '14:00:00', '16:00:00', GETDATE(), GETDATE()),
(8, 0, '15:00:00', '17:00:00', GETDATE(), GETDATE()),
(9, 1, '16:00:00', '18:00:00', GETDATE(), GETDATE()),
(10, 1, '17:00:00', '19:00:00', GETDATE(), GETDATE());
