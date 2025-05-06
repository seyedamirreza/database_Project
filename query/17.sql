WITH AdminCancelStats AS (
    SELECT 
        u.firstName,
        u.lastName,
        COUNT(*) AS total_reserves,
        SUM(CASE WHEN tr.status = 0 THEN 1 ELSE 0 END) AS total_canceled   --! is really 'canceled' in tables?
    FROM 
        users u
    JOIN 
        reservations tr ON u.id = tr.user_id
    WHERE 
        u.role = 'admin'
    GROUP BY 
        u.id, u.firstName, u.lastName
),
RankedStats AS (
    SELECT 
        *,
        CAST(total_canceled AS FLOAT) / NULLIF(total_reserves, 0) * 100 AS cancel_percent,
        ROW_NUMBER() OVER (ORDER BY total_canceled DESC) AS rn
    FROM 
        AdminCancelStats
)
SELECT 
    firstName,
    lastName,
    total_canceled,
    total_reserves,
    ROUND(cancel_percent, 2) AS cancel_percent
FROM 
    RankedStats
WHERE 
    rn = 1;
