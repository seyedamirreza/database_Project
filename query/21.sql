UPDATE t
SET t.price = t.price * 0.9
FROM tickets t
JOIN ticket_reserve tr ON t.id = tr.ticket_id
JOIN vehicles v ON t.vehicle_id = v.id
JOIN companies c ON v.company_id = c.id
WHERE 
    c.name = 'mahan' AND
    CAST(tr.created_at AS DATE) = CAST(DATEADD(DAY, -1, GETDATE()) AS DATE);        
    --! we don't have these fields