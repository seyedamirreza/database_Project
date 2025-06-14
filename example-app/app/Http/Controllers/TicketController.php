<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use PDO;
use PDOException;

class TicketController extends Controller
{

    public function searchTicket(Request $request)
    {
        $params = [
            'source' => $request->source ?? null,
            'destination' => $request->destination ?? null,
            'departureDate' => $request->departureDate ?? null,
            'vehicleType' => $request->vehicleType ?? null,
            'priceMin' => isset($request->priceMin) ? (int)$request->priceMin : null,
            'priceMax' => isset($request->priceMax) ? (int)$request->priceMax : null,
            'companyId' => isset($request->companyId) ? (int)$request->companyId : null,
            'departureTime' => $request->departureTime ?? null,
            'class' => $request->class ?? null,
        ];

        // ساختن کلید منحصر به‌فرد بر اساس پارامترها
        $cacheKey = 'search_ticket:' . md5(json_encode($params));

        // چک کردن کش در ردیس
        $cachedResult = Redis::get($cacheKey);
        if ($cachedResult)
            return response()->json(['message'=>'ticket from redis is returned',
                'data'=>$cachedResult], 200);

        // ----------------- اجرای کوئری از دیتابیس -------------------
        $pdo = new PDO("mysql:host=localhost;dbname=example_app;charset=utf8", "root", "");

        $where = [];
        $pdoParams = [];

        if ($params['source']) {
            $where[] = "tickets.source = :source";
            $pdoParams[':source'] = $params['source'];
        }

        if ($params['destination']) {
            $where[] = "tickets.destination = :destination";
            $pdoParams[':destination'] = $params['destination'];
        }

        if ($params['departureDate']) {
            $where[] = "tickets.departure_time = :departureDate";
            $pdoParams[':departureDate'] = $params['departureDate'];
        }

        if ($params['vehicleType']) {
            $where[] = "type_vehicle.name = :vehicleType";
            $pdoParams[':vehicleType'] = $params['vehicleType'];
        }

        if (!is_null($params['priceMin'])) {
            $where[] = "tickets.price >= :priceMin";
            $pdoParams[':priceMin'] = $params['priceMin'];
        }

        if (!is_null($params['priceMax'])) {
            $where[] = "tickets.price <= :priceMax";
            $pdoParams[':priceMax'] = $params['priceMax'];
        }

        if ($params['companyId']) {
            $where[] = "vehicles.company_id = :companyId";
            $pdoParams[':companyId'] = $params['companyId'];
        }

        if ($params['departureTime']) {
            $where[] = "TIME(tickets.departure_time) = :departureTime";
            $pdoParams[':departureTime'] = $params['departureTime'];
        }

        if ($params['class']) {
            $where[] = "tickets.class = :class";
            $pdoParams[':class'] = $params['class'];
        }

        $sql = "
        SELECT tickets.*, vehicles.name AS vehicle_name, type_vehicle.name AS vehicle_type_name, vehicles.company_id
        FROM tickets
        JOIN vehicles ON tickets.vehicle_id = vehicles.id
        JOIN type_vehicle ON vehicles.type_vehicle_id = type_vehicle.id
    ";

        if (count($where) > 0) {
            $sql .= " WHERE " . implode(" AND ", $where);
        }

        $stmt = $pdo->prepare($sql);
        $stmt->execute($pdoParams);

        $tickets = $stmt->fetchAll(PDO::FETCH_ASSOC);


        Redis::setex($cacheKey, 1800, json_encode($tickets));
        return response()->json(['data'=>$tickets,'message'=>'this ticket are returned from database'], 200);
    }


    public function getCityTicket()
    {
        $pdo = new PDO("mysql:host=localhost;dbname=example_app", "root", "");
        $data = $pdo->query($sql = "
    SELECT DISTINCT source AS city FROM tickets
    UNION
    SELECT DISTINCT destination AS city FROM tickets
    ORDER BY city ASC
")->fetchAll(PDO::FETCH_ASSOC);
        return response()->json($data, 200);
    }

    public function getdetailTicket(Request $request)
    {
        $pdo = new PDO("mysql:host=localhost;dbname=example_app", "root", "");
        if (!isset($request->id)) {
            return response()->json(['success' => false, 'message' => 'id ticket not found. ']);
        } else {
            $sql = "
SELECT
    t.id, t.source, t.destination, t.departure_time, t.arrival_time, t.price,
    t.remaining_cap, t.class,
    v.id AS vehicle_id, tv.name AS vehicle_type
FROM tickets t
JOIN vehicles v ON t.vehicle_id = v.id
JOIN type_vehicle tv ON v.type_vehicle_id = tv.id
WHERE t.id = :id
";
            $sql2 = "SELECT v.facilities
FROM tickets t
JOIN vehicles v ON t.vehicle_id = v.id
WHERE t.id = :id
";

            $stmt = $pdo->prepare($sql2);
            $stmt->bindValue(':id', $request->id);
            $stmt->execute();
            $data1 = $stmt->fetch(PDO::FETCH_ASSOC);

            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':id', $request->id);
            $stmt->execute();
            $data = $stmt->fetch(PDO::FETCH_ASSOC);

            return response()->json(['ticket' => $data, 'vehicle_facilities' => $data1, 200]);
        }
    }

    public function reserveTicket(Request $request)
    {
        $pdo = new PDO("mysql:host=localhost;dbname=example_app", "root", "");

        $this->cleanupExpiredReservations();
        // ۲. ادامه منطق رزرو
        $user_id = $request->input('user_id');
        $ticket_id = $request->input('ticket_id');

        $stmt = $pdo->prepare("SELECT remaining_cap FROM tickets WHERE id = ?");
        $stmt->execute([$ticket_id]);
        $ticket = $stmt->fetch(\PDO::FETCH_ASSOC);


        if (!$ticket || $ticket['remaining_cap'] < 1) {
            return response()->json(['success' => false, 'message' => 'Ticket not available.']);
        }

        $reserveTime = date('Y-m-d H:i:s');
        $expireTime = date('Y-m-d H:i:s', strtotime('+1 minutes'));

        $stmt = $pdo->prepare("
        INSERT INTO reservations (user_id, ticket_id, status, reserve_time, expire_time, created_at, updated_at)
        VALUES (?, ?, true, ?, ?, NOW(), NOW())
    ");
        $stmt->execute([$user_id, $ticket_id, $reserveTime, $expireTime]);

        $pdo->prepare("UPDATE tickets SET remaining_cap = remaining_cap - 1 WHERE id = ?")
            ->execute([$ticket_id]);

        return response()->json([
            'success' => true,
            'message' => 'Reservation created successfully.',
            'reserve_time' => $reserveTime,
            'expire_time' => $expireTime
        ]);
    }

    private function cleanupExpiredReservations()
    {
        $pdo = new PDO("mysql:host=localhost;dbname=example_app", "root", "");

        $stmt = $pdo->prepare("
        SELECT r.id, r.ticket_id
        FROM reservations r
        LEFT JOIN payments p ON p.reservation_id = r.id
        WHERE r.status = true
        AND r.expire_time <= NOW()
        AND p.id IS NULL
    ");
        $stmt->execute();
        $expired = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        // غیرفعال‌سازی و افزایش ظرفیت
        foreach ($expired as $res) {
            $pdo->prepare("UPDATE reservations SET status = false WHERE id = ?")
                ->execute([$res['id']]);

            $pdo->prepare("UPDATE tickets SET remaining_cap = remaining_cap + 1 WHERE id = ?")
                ->execute([$res['ticket_id']]);
        }
    }

    public function showCancelValue(Request $request)
    {
        $request->validate([
            'ticket_id' => 'required'
        ]);
        $pdo = new PDO("mysql:host=localhost;dbname=example_app", "root", "");
        $sql2 = "SELECT t.price FROM tickets t WHERE id=:id";

        $stmt = $pdo->prepare($sql2);
        $stmt->bindValue(':id', $request->ticket_id);
        $stmt->execute();

        $data1 = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($data1) {
            $price = $data1['price'];
            return response()->json(['price for canceling is' => $price / 10], 200);
        } else {
            return response()->json(['success' => false, 'message' => 'Ticket not found.']);
        }
//    dd($data1);

    }

    public function getTicketUserBooking(Request $request)
    {
//        dd($request->mood);
        $pdo = new PDO("mysql:host=localhost;dbname=example_app", "root", "");
        if ($request->mood == "booked") {
            $sql2 = "SELECT t.*
FROM payments p
JOIN reservations r ON p.reservation_id = r.id
JOIN tickets t ON r.ticket_id = t.id
WHERE r.user_id = :user_id";

            $stmt = $pdo->prepare($sql2);
            $stmt->bindValue(':user_id', $request->user_id);
            $stmt->execute();
            $data1 = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$data1) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ticket not found.'
                ]);

            } else {
                return response()->json([
                    'success' => true,
                    'data' => $data1
                ]);
            }
        } else if ($request->mood == "cancelled") {
//            dd($request->mood);
            $sql = "SELECT t.*
        FROM reservations r
        JOIN tickets t ON r.ticket_id = t.id
        WHERE r.user_id = :user_id
          AND r.status=0";

            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':user_id', $request->user_id);
            $stmt->execute();
            $tickets = $stmt->fetchAll(PDO::FETCH_ASSOC);

//            dd($tickets);
            return response([
                'data' => $tickets
            ]);

        }
    }

    public function cancelTicket(Request $request)
    {
        $reservationId = $request->input('reservation_id');

        $pdo = new PDO("mysql:host=localhost;dbname=example_app", "root", "");


        // گام 1: بررسی وجود رزرو
        $stmt = $pdo->prepare("SELECT * FROM reservations WHERE id = ?");
        $stmt->execute([$reservationId]);
        $reservation = $stmt->fetch(\PDO::FETCH_ASSOC);
//        dd($reservation['ticket_id']);
        if (!$reservation) {
            return response()->json(['success' => false, 'message' => 'Reservation not found.']);
        }

        if (!$reservation['status']) {
            return response()->json(['success' => false, 'message' => 'Reservation already cancelled.']);
        }

        // گام 2: گرفتن مبلغ از جدول tickets
        $stmt = $pdo->prepare("SELECT price, remaining_cap FROM tickets WHERE id = ?");
        $stmt->execute([$reservation['ticket_id']]);
        $ticket = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!$ticket) {
            return response()->json(['success' => false, 'message' => 'Ticket not found.']);
        }

        $refundAmount = $ticket['price'];

        // گام 3: بررسی وجود کیف پول برای کاربر
        $stmt = $pdo->prepare("SELECT * FROM wallets WHERE user_id = ?");
        $stmt->execute([$reservation['user_id']]);
        $wallet = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($wallet) {
            // اگر کیف پول وجود داشت → افزایش اعتبار
            $pdo->prepare("UPDATE wallets SET value = value + ? WHERE user_id = ?")
                ->execute([$refundAmount, $reservation['user_id']]);
        } else {
            // اگر وجود نداشت → ایجاد و مقداردهی اولیه
            $pdo->prepare("INSERT INTO wallets (user_id, value, created_at, updated_at)
                       VALUES (?, ?, NOW(), NOW())")
                ->execute([$reservation['user_id'], $refundAmount]);
        }

        // گام 4: تغییر وضعیت رزرو
        $pdo->prepare("UPDATE reservations SET status = false, updated_at = NOW() WHERE id = ?")
            ->execute([$reservationId]);

        // گام 5: افزایش ظرفیت بلیط
        $pdo->prepare("UPDATE tickets SET remaining_cap = remaining_cap + 1 WHERE id = ?")
            ->execute([$reservation['ticket_id']]);

        return response()->json(['success' => true, 'message' => 'Reservation cancelled and refund applied.']);
    }

    public function adminTicketManagement(Request $request)
    {
        $filter = $request->input('filter', 'all');

        try {
            $pdo = new PDO("mysql:host=localhost;dbname=example_app", "root", "");
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $sql = "
            SELECT r.id AS reservation_id, r.user_id, r.ticket_id, r.status,
                   t.source, t.destination, t.departure_time, t.arrival_time,
                   p.amount AS payment_amount,
                   rep.body AS report_reason
            FROM reservations r
            JOIN tickets t ON t.id = r.ticket_id
            LEFT JOIN payments p ON p.reservation_id = r.id
            LEFT JOIN reports rep ON rep.reservation_id = r.id
        ";

            // فیلترها
            switch ($filter) {
                case 'cancelled':
                    $sql .= " WHERE r.status = 0";
                    break;
                case 'suspicious':
                    $sql .= " WHERE (p.id IS NULL OR p.amount IS NULL) AND r.status = 1";
                    break;
                case 'reported':
                    $sql .= " WHERE rep.id IS NOT NULL";
                    break;
                default:
                    // همه موارد (فیلتر اعمال نمی‌شه)
                    break;
            }

            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            $reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (count($reservations)) {
                return response()->json(['success' => true, 'data' => $reservations]);
            } else {
                return response()->json(['success' => false, 'message' => 'رزروی یافت نشد.']);
            }

        } catch (PDOException $e) {
            return response()->json(['success' => false, 'message' => 'خطا در اتصال به دیتابیس', 'error' => $e->getMessage()]);
        }
    }
}
