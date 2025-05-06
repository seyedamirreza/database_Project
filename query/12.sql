SELECT 
    u.firstName,
    u.lastName
FROM 
    users u
JOIN 
    ticket_reserve tr ON u.id = tr.user_id
GROUP BY 
    u.id, u.firstName, u.lastName
HAVING 
    COUNT(tr.ticket_id) >= 2;