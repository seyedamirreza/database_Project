DELETE tr
FROM reservations tr
JOIN users u ON tr.user_id = u.id
WHERE 
    tr.status = 0 AND      
    u.lastName = 'Redington';