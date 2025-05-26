SELECT firstName, lastName
FROM users
WHERE id NOT IN (
    SELECT DISTINCT user_id FROM reservations
);

