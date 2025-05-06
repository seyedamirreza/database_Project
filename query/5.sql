SELECT TOP 1 u.*
FROM users u
JOIN reservations r ON u.id = r.user_id
ORDER BY r.created_at DESC;

