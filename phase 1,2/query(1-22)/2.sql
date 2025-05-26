SELECT DISTINCT u.firstName, u.lastName
FROM users u
JOIN payments p ON u.id = p.user_id
WHERE p.status = 1;

