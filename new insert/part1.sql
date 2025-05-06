
-- 6. vehicles
INSERT INTO vehicles (name, company_id, facilities, type_vehicle_id) VALUES
('bus1',11,'monitor','bus'),
('bus2',12,'-','bus'),
('bus3',12,'air-conductor','bus'),
('train1',16,'-','train'),
('train2',13,'-','train'),
('train3',17,'internet','train'),
('flight',15,'-','flight'),
('flight1',16,'-','flight'),
('flight2',16,'internet-food','flight'),
('flight3',16,'food','flight');

-- 7. buses
INSERT INTO buses (vehicle_id, class_bus_id, company_name, capacity, created_at, updated_at) VALUES
(1, 1,'hasan' , 40, GETDATE(), GETDATE()),
(2, 2, 'mamad', 42, GETDATE(), GETDATE()),
(3, 3, 'hassan', 38, GETDATE(), GETDATE()),
(1, 3, 'mamad', 45, GETDATE(), GETDATE()),
(2, 2, 'hassan', 35, GETDATE(), GETDATE()),
(3, 1, 'mamasd', 50, GETDATE(), GETDATE()),
(1, 1, 'afnonf', 37, GETDATE(), GETDATE()),
(2, 2, 'spgijewpofj', 46, GETDATE(), GETDATE()),
(3, 3, 'pigfpadojfs', 48, GETDATE(), GETDATE()),
(1, 1, 'pifhewoijf', 44, GETDATE(), GETDATE());

-- 8. flights
INSERT INTO flights (vehicle_id, class_air_id, airline_name, flight_number, source_airport, destination_airport, stops) VALUES
(10, 1, N'هواپیمایی 1', N'FL001', N'فرودگاه مبدأ 1', N'فرودگاه مقصد 1', 1),
(8, 2, N'هواپیمایی 2', N'FL002', N'فرودگاه مبدأ 2', N'فرودگاه مقصد 2', 0),
(7, 3, N'هواپیمایی 3', N'FL003', N'فرودگاه مبدأ 3', N'فرودگاه مقصد 3', 2),
(9, 3, N'هواپیمایی 4', N'FL004', N'فرودگاه مبدأ 4', N'فرودگاه مقصد 4', 0),
(10, 2, N'هواپیمایی 5', N'FL005', N'فرودگاه مبدأ 5', N'فرودگاه مقصد 5', 1),
(8, 1, N'هواپیمایی 6', N'FL006', N'فرودگاه مبدأ 6', N'فرودگاه مقصد 6', 0),
(7, 3, N'هواپیمایی 7', N'FL007', N'فرودگاه مبدأ 7', N'فرودگاه مقصد 7', 1),
(8, 2, N'هواپیمایی 8', N'FL008', N'فرودگاه مبدأ 8', N'فرودگاه مقصد 8', 2),
(9, 1, N'هواپیمایی 9', N'FL009', N'فرودگاه مبدأ 9', N'فرودگاه مقصد 9', 1),
(10, 1, N'هواپیمایی 10', N'FL010', N'فرودگاه مبدأ 10', N'فرودگاه مقصد 10', 0);