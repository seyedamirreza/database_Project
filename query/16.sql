WITH TicketSales AS (
    SELECT 
        tr.ticket_id,
        COUNT(*) AS sale_count,
        ROW_NUMBER() OVER (ORDER BY COUNT(*) DESC) AS rank
    FROM 
        ticket_reserve tr
    GROUP BY 
        tr.ticket_id
)
SELECT 
    ts.ticket_id,
    ts.sale_count
FROM 
    TicketSales ts
WHERE 
    ts.rank = 2;
