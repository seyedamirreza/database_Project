SELECT 
    u.id AS user_id,
    u.firstName,
    u.lastName,
    MONTH(r.created_at) AS [Month],
    YEAR(r.created_at) AS [Year],
    SUM(p.price) AS total_paid
FROM payments p
JOIN reservations r ON p.reservation_id = r.id
JOIN users u ON u.id = p.user_id
GROUP BY u.id, u.firstName, u.lastName, MONTH(r.created_at), YEAR(r.created_at);

