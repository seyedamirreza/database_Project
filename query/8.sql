SELECT TOP 3 u.firstName, u.lastName, COUNT(*) AS ticket_count
FROM users u
JOIN reservations r ON u.id = r.user_id
WHERE r.created_at >= DATEADD(DAY, -7, GETDATE())
GROUP BY u.id, u.firstName, u.lastName
ORDER BY ticket_count DESC;

