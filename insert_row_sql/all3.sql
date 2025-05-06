-- 13. wallets
INSERT INTO wallets (user_id, value, created_at, updated_at) VALUES
(1, 2500000, GETDATE(), GETDATE()),
(2, 1800000, GETDATE(), GETDATE()),
(3, 3000000, GETDATE(), GETDATE()),
(4, 2200000, GETDATE(), GETDATE()),
(5, 2750000, GETDATE(), GETDATE()),
(6, 2600000, GETDATE(), GETDATE()),
(7, 1500000, GETDATE(), GETDATE()),
(8, 3200000, GETDATE(), GETDATE()),
(9, 2100000, GETDATE(), GETDATE()),
(10, 2900000, GETDATE(), GETDATE());

-- 14. reports
INSERT INTO reports (reservation_id, status, title, body, created_at, updated_at) VALUES
(1, 1, N'گزارش 1', N'جزئیات گزارش 1', GETDATE(), GETDATE()),
(2, 1, N'گزارش 2', N'جزئیات گزارش 2', GETDATE(), GETDATE()),
(3, 1, N'گزارش 3', N'جزئیات گزارش 3', GETDATE(), GETDATE()),
(4, 1, N'گزارش 4', N'جزئیات گزارش 4', GETDATE(), GETDATE()),
(5, 1, N'گزارش 5', N'جزئیات گزارش 5', GETDATE(), GETDATE()),
(6, 1, N'گزارش 6', N'جزئیات گزارش 6', GETDATE(), GETDATE()),
(7, 1, N'گزارش 7', N'جزئیات گزارش 7', GETDATE(), GETDATE()),
(8, 1, N'گزارش 8', N'جزئیات گزارش 8', GETDATE(), GETDATE()),
(9, 1, N'گزارش 9', N'جزئیات گزارش 9', GETDATE(), GETDATE()),
(10, 1, N'گزارش 10', N'جزئیات گزارش 10', GETDATE(), GETDATE());

-- 15. trains
INSERT INTO trains (vehicle_id, class_train_id, component) VALUES
(1, 1, N'ترکیبات قطار 1'),
(2, 2, N'ترکیبات قطار 2'),
(3, 3, N'ترکیبات قطار 3'),
(4, 4, N'ترکیبات قطار 4'),
(5, 5, N'ترکیبات قطار 5'),
(6, 6, N'ترکیبات قطار 6'),
(7, 7, N'ترکیبات قطار 7'),
(8, 8, N'ترکیبات قطار 8'),
(9, 9, N'ترکیبات قطار 9'),
(10, 10, N'ترکیبات قطار 10');

-- 16. ticket_reserve
INSERT INTO ticket_reserve (user_id, reservation_id, created_at, updated_at) VALUES
(1, 1, GETDATE(), GETDATE()),
(2, 2, GETDATE(), GETDATE()),
(3, 3, GETDATE(), GETDATE()),
(4, 4, GETDATE(), GETDATE()),
(5, 5, GETDATE(), GETDATE()),
(6, 6, GETDATE(), GETDATE()),
(7, 7, GETDATE(), GETDATE()),
(8, 8, GETDATE(), GETDATE()),
(9, 9, GETDATE(), GETDATE()),
(10, 10, GETDATE(), GETDATE());

-- 17. user_reports
INSERT INTO user_reports (user_id, report_id, created_at, updated_at) VALUES
(1, 1, GETDATE(), GETDATE()),
(2, 2, GETDATE(), GETDATE()),
(3, 3, GETDATE(), GETDATE()),
(4, 4, GETDATE(), GETDATE()),
(5, 5, GETDATE(), GETDATE()),
(6, 6, GETDATE(), GETDATE()),
(7, 7, GETDATE(), GETDATE()),
(8, 8, GETDATE(), GETDATE()),
(9, 9, GETDATE(), GETDATE()),
(10, 10, GETDATE(), GETDATE());