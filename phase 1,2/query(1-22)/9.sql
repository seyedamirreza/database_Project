SELECT u.city, COUNT(*) AS sold_tickets
FROM users u
JOIN reservations r ON u.id = r.user_id
WHERE u.city LIKE N'%تهران%'
GROUP BY u.city;

