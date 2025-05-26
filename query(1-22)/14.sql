SELECT 
    u.email,
    u.phoneNumber
FROM 
    users u
JOIN 
    ticket_reserve tr ON u.id = tr.user_id
JOIN 
    tickets t ON tr.ticket_id = t.id
JOIN 
    vehicles v ON t.vehicle_id = v.id
JOIN 
    type_vehicle tv ON v.type_vehicle_id = tv.id
GROUP BY 
    u.id, u.email, u.phoneNumber
HAVING 
    COUNT(DISTINCT tv.name) = 3;
