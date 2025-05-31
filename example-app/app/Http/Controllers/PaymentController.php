<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PDO;

class PaymentController extends Controller
{
    public function createPayment(Request $request)
    {
        $request->validate([
            'reservation_id' => 'required',
            'payment_method' => 'required',
        ]);
        $pdo = new PDO("mysql:host=localhost;dbname=example_app", "root", "");
        $stmt = $pdo->prepare("INSERT INTO payments (reservation_id, payment_method, status, time )
                           VALUES (:reservation_id, :payment_method, true, Now())");
        $stmt->bindValue(':reservation_id', $request->reservation_id);
        $stmt->bindValue(':payment_method', $request->payment_method);
        $data=$stmt->execute();
        return response()->json($data);
    }
}
