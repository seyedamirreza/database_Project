<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PDO;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function showUser(){
        $pdo = new PDO("mysql:host=localhost;dbname=example_app", "root", "");
        $data = $pdo->query("SELECT * FROM users")
            ->fetchAll(PDO::FETCH_ASSOC);
        return response()->json($data,200);
    }

    public function signup(Request $request)
    {
        $request->validate([
            'firstName' => 'required|string',
            'lastName' => 'required|string',
            'phoneNumber' => 'required|string|unique:users,phoneNumber',
            'password' => 'required|string|min:6',
            'city' => 'required|string',
        ]);

        $pdo = new PDO("mysql:host=localhost;dbname=example_app", "root", "");

        $hashedPassword = Hash::make($request->password);

        $stmt = $pdo->prepare("INSERT INTO users (firstName, lastName, phoneNumber, password, city, role, accountState, registerDate)
                           VALUES (:firstName, :lastName, :phoneNumber, :password, :city, 'passenger', true, CURRENT_DATE())");

        $stmt->bindValue(':firstName', $request->firstName);
        $stmt->bindValue(':lastName', $request->lastName);
        $stmt->bindValue(':phoneNumber', $request->phoneNumber);
        $stmt->bindValue(':password', $hashedPassword);
        $stmt->bindValue(':city', $request->city);

        $stmt->execute();

//        $userId = $pdo->lastInsertId();
//
//        // JWT Token (ساده)
//        $payload = [
//            'sub' => $userId,
//            'phone' => $request->phoneNumber,
//            'iat' => time(),
//            'exp' => time() + (60 * 60 * 24)
//        ];
//        $token = JWT::encode($payload, env('JWT_SECRET'), 'HS256');

        return response()->json([
            'message' => 'User created successfully',
//            'token' => $token
        ], 201);
    }
}
