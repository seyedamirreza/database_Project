SELECT u.firstName, u.lastName, t.source, COUNT(*) AS num_purchases
FROM users u
JOIN reservations r ON u.id = r.user_id
JOIN tickets t ON r.ticket_id = t.id
GROUP BY u.id, u.firstName, u.lastName, t.source
HAVING COUNT(*) = 1;

