SELECT u.phoneNumber, u.email
FROM users u
JOIN payments p ON u.id = p.user_id
GROUP BY u.id, u.phoneNumber, u.email
HAVING SUM(p.price) > (
    SELECT AVG(total)
    FROM (
        SELECT SUM(price) AS total
        FROM payments
        GROUP BY user_id
    ) AS avg_payments
);

