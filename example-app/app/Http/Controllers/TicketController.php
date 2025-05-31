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
        $data = $pdo->query("$sql = "
    SELECT DISTINCT source AS city FROM tickets
    UNION
    SELECT DISTINCT destination AS city FROM tickets
    ORDER BY city ASC
";")
            ->fetchAll(PDO::FETCH_ASSOC);
         return response()->json($data,200);
    }

    public function getdetailTicket(){

    }
}
