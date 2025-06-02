<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PDO;

class TicketController extends Controller
{


    public function searchTicket(Request $request)
    {

// فرض می‌کنیم مقادیر ورودی از $request یا هر ورودی دیگه به صورت امن وارد شدند

        $source = isset($request->source) ? $request->source : null;
        $destination = isset($request->destination) ? $request->destination : null;
        $departureDate = isset($request->departureDate) ? $request->departureDate : null; // تاریخ رفت
        $vehicleType = isset($request->vehicleType) ? $request->vehicleType : null; // اسم نوع وسیله
        $priceMin = isset($request->priceMin) ? (int)$request->priceMin : null;
        $priceMax = isset($request->priceMax) ? (int)$request->priceMax : null;
        $companyId = isset($request->companyId) ? (int)$request->companyId : null;
        $departureTime = isset($request->departureTime) ? $request->departureTime : null; // مثلا '08:00:00'
        $class = isset($request->class) ? $request->class : null;

        $pdo = new PDO("mysql:host=localhost;dbname=example_app;charset=utf8", "root", "");

// ساختن شرط ها و پارامترها داینامیک
        $where = [];
        $params = [];

// مبدا
        if ($source) {
            $where[] = "tickets.source = :source";
            $params[':source'] = $source;
        }

// مقصد
        if ($destination) {
            $where[] = "tickets.destination = :destination";
            $params[':destination'] = $destination;
        }

// تاریخ رفت (می‌تونی این رو به departure_time مقایسه کنی)
        if ($departureDate) {
            $where[] = "tickets.departure_time = :departureDate";
            $params[':departureDate'] = $departureDate;
        }

// نوع وسیله
        if ($vehicleType) {
            $where[] = "type_vehicle.name = :vehicleType";
            $params[':vehicleType'] = $vehicleType;
        }

// حداقل قیمت
        if (!is_null($priceMin)) {
            $where[] = "tickets.price >= :priceMin";
            $params[':priceMin'] = $priceMin;
        }

// حداکثر قیمت
        if (!is_null($priceMax)) {
            $where[] = "tickets.price <= :priceMax";
            $params[':priceMax'] = $priceMax;
        }

// شرکت حمل و نقل
        if ($companyId) {
            $where[] = "vehicles.company_id = :companyId";
            $params[':companyId'] = $companyId;
        }

// ساعت حرکت (می‌تونی با LIKE یا مقایسه زمانی کار کنی، اینجا فرض ساده می‌گیریم دقیقا ساعت معین)
        if ($departureTime) {
            $where[] = "TIME(tickets.departure_time) = :departureTime";
            $params[':departureTime'] = $departureTime;
        }

// کلاس سفر
        if ($class) {
            $where[] = "tickets.class = :class";
            $params[':class'] = $class;
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
        $stmt->execute($params);

        $tickets = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $tickets;

    }

    public function getCityTicket(){
  $pdo = new PDO("mysql:host=localhost;dbname=example_app", "root", "");
        $data = $pdo->query($sql = "
    SELECT DISTINCT source AS city FROM tickets
    UNION
    SELECT DISTINCT destination AS city FROM tickets
    ORDER BY city ASC
")->fetchAll(PDO::FETCH_ASSOC);
         return response()->json($data,200);
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
            return response()->json(['price for canceling is' => $price/10], 200);
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
        }else if ($request->mood == "cancelled") {
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


}
