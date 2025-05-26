-- 9. users
INSERT INTO users (firstName, lastName, password, phoneNumber, role, registerDate, city, email, accountState, created_at, updated_at) VALUES
(N'نام1', N'نام‌خانوادگی1', N'pass1', N'0912345601', N'user', GETDATE(), N'شهر1', N'user1@test.com', 1, GETDATE(), GETDATE()),
(N'نام2', N'نام‌خانوادگی2', N'pass2', N'0912345602', N'user', GETDATE(), N'شهر2', N'user2@test.com', 1, GETDATE(), GETDATE()),
(N'نام3', N'نام‌خانوادگی3', N'pass3', N'0912345603', N'user', GETDATE(), N'شهر3', N'user3@test.com', 1, GETDATE(), GETDATE()),
(N'نام4', N'نام‌خانوادگی4', N'pass4', N'0912345604', N'user', GETDATE(), N'شهر4', N'user4@test.com', 1, GETDATE(), GETDATE()),
(N'نام5', N'نام‌خانوادگی5', N'pass5', N'0912345605', N'user', GETDATE(), N'شهر5', N'user5@test.com', 1, GETDATE(), GETDATE()),
(N'نام6', N'نام‌خانوادگی6', N'pass6', N'0912345606', N'user', GETDATE(), N'شهر6', N'user6@test.com', 1, GETDATE(), GETDATE()),
(N'نام7', N'نام‌خانوادگی7', N'pass7', N'0912345607', N'user', GETDATE(), N'شهر7', N'user7@test.com', 1, GETDATE(), GETDATE()),
(N'نام8', N'نام‌خانوادگی8', N'pass8', N'0912345608', N'user', GETDATE(), N'شهر8', N'user8@test.com', 1, GETDATE(), GETDATE()),
(N'نام9', N'نام‌خانوادگی9', N'pass9', N'0912345609', N'user', GETDATE(), N'شهر9', N'user9@test.com', 1, GETDATE(), GETDATE()),
(N'نام10', N'نام‌خانوادگی10', N'pass10', N'0912345610', N'user', GETDATE(), N'شهر10', N'user10@test.com', 1, GETDATE(), GETDATE());

-- 10. tickets
INSERT INTO tickets (vehicle_id, price, capacity, availability, is_two_way, source, destination, arrival_time, departure_time) VALUES
(1, 120000, 40, 15, 1, N'مبدأ 1', N'مقصد 1', GETDATE(), GETDATE()),
(2, 180000, 35, 20, 0, N'مبدأ 2', N'مقصد 2', GETDATE(), GETDATE()),
(3, 150000, 30, 25, 1, N'مبدأ 3', N'مقصد 3', GETDATE(), GETDATE()),
(4, 200000, 45, 10, 0, N'مبدأ 4', N'مقصد 4', GETDATE(), GETDATE()),
(5, 160000, 32, 12, 1, N'مبدأ 5', N'مقصد 5', GETDATE(), GETDATE()),
(6, 140000, 50, 30, 1, N'مبدأ 6', N'مقصد 6', GETDATE(), GETDATE()),
(7, 170000, 36, 20, 0, N'مبدأ 7', N'مقصد 7', GETDATE(), GETDATE()),
(8, 210000, 38, 18, 1, N'مبدأ 8', N'مقصد 8', GETDATE(), GETDATE()),
(9, 195000, 40, 22, 0, N'مبدأ 9', N'مقصد 9', GETDATE(), GETDATE()),
(10, 185000, 42, 19, 1, N'مبدأ 10', N'مقصد 10', GETDATE(), GETDATE());

-- 11. reservations
INSERT INTO reservations (ticket_id, user_id, status, reserve_time, expire_time, created_at, updated_at) VALUES
(1, 1, 1, '12:00:00', '14:00:00', GETDATE(), GETDATE()),
(2, 2, 1, '12:00:00', '14:00:00', GETDATE(), GETDATE()),
(3, 3, 1, '12:00:00', '14:00:00', GETDATE(), GETDATE()),
(4, 4, 1, '12:00:00', '14:00:00', GETDATE(), GETDATE()),
(5, 5, 1, '12:00:00', '14:00:00', GETDATE(), GETDATE()),
(6, 6, 1, '12:00:00', '14:00:00', GETDATE(), GETDATE()),
(7, 7, 1, '12:00:00', '14:00:00', GETDATE(), GETDATE()),
(8, 8, 1, '12:00:00', '14:00:00', GETDATE(), GETDATE()),
(9, 9, 1, '12:00:00', '14:00:00', GETDATE(), GETDATE()),
(10, 10, 1, '12:00:00', '14:00:00', GETDATE(), GETDATE());

-- 12. payments
INSERT INTO payments (user_id, reservation_id, price, status, payment_method, time) VALUES
(1, 1, 120000, 1, N'کارت بانکی', '12:30:00'),
(2, 2, 180000, 1, N'کارت بانکی', '12:30:00'),
(3, 3, 150000, 1, N'کارت بانکی', '12:30:00'),
(4, 4, 200000, 1, N'کارت بانکی', '12:30:00'),
(5, 5, 160000, 1, N'کارت بانکی', '12:30:00'),
(6, 6, 140000, 1, N'کارت بانکی', '12:30:00'),
(7, 7, 170000, 1, N'کارت بانکی', '12:30:00'),
(8, 8, 210000, 1, N'کارت بانکی', '12:30:00'),
(9, 9, 195000, 1, N'کارت بانکی', '12:30:00'),
(10, 10, 185000, 1, N'کارت بانکی', '12:30:00');