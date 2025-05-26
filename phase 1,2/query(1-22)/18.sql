WITH CanceledCounts AS (
    SELECT 
        u.id,
        COUNT(*) AS canceled_count,
        ROW_NUMBER() OVER (ORDER BY COUNT(*) DESC) AS rn
    FROM 
        users u
    JOIN 
        reservations tr ON u.id = tr.user_id
    WHERE 
        u.role != 'admin' AND
        tr.status = 0      
    GROUP BY 
        u.id
)
UPDATE u
SET u.lastName = 'Redington'
FROM users u
JOIN CanceledCounts cc ON u.id = cc.id
WHERE cc.rn = 1;
