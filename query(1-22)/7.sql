SELECT tv.name AS vehicle_type, COUNT(*) AS total_tickets
FROM tickets t
JOIN vehicles v ON t.vehicle_id = v.id
JOIN type_vehicle tv ON v.type_vehicle_id = tv.id
GROUP BY tv.name;

