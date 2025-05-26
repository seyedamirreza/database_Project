SELECT DISTINCT u.city
FROM users u
JOIN reservations r ON u.id = r.user_id
WHERE u.registerDate = (
    SELECT MIN(registerDate) FROM users
);

