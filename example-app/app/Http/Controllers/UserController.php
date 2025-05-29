<?php

namespace App\Http\Controllers;

//use App\Models\Cache;
use App\Notifications\SendOTPViaBale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use PDO;

class UserController extends Controller
{
    public function showUser(){
        $pdo = new PDO("mysql:host=localhost;dbname=example_app", "root", "");
        $data = $pdo->query("SELECT * FROM users")
            ->fetchAll(PDO::FETCH_ASSOC);
        return response()->json($data,200);
    }

    public function signUp(Request $request)
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

    public function signIn(Request $request){
        $request->validate([
            'phoneNumber' => 'required|string',
        ]);
        $password = $this->randomPasswordGenerator();
        $pdo = new PDO("mysql:host=localhost;dbname=example_app", "root", "");

        $stmt = $pdo->prepare("SELECT * FROM users WHERE phoneNumber = :phoneNumber LIMIT 1");
        $stmt->bindValue(':phoneNumber', $request->phoneNumber);
        $stmt->execute();

        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        $user = null;
        if ($data) {
            $user = (new \App\Models\User())->newFromBuilder($data);
        }else{
            return response()->json([
                'message' => 'User not found',
            ]);
        }
//            dd($user);
            $user->notify(new SendOTPViaBale($password));

        return response()->json([
            'message' => 'User created successfully',
            ''
        ], 201);

    }

    private function randomPasswordGenerator()
    {
        return mt_rand(10000, 99999);
    }

    public function verifySignIn(Request $request)
    {
        //$request->phoneNumber
        //$request->code
        // cache pass is available
        $pdo = new PDO("mysql:host=localhost;dbname=example_app", "root", "");
        $phone = $request->phoneNumber; // یا '9031104660' بسته به مقدار واقعی
        $stmt = $pdo->prepare("SELECT * FROM cache WHERE `key` = :key");
        $stmt->bindValue(':key', $phone);
        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        $data = Cache::get($phone);
        if ((int)$request->code == $data) {
            $stmt = $pdo->prepare("SELECT * FROM users WHERE phoneNumber = :phoneNumber LIMIT 1");
            $stmt->bindValue(':phoneNumber', $request->phoneNumber);
            $stmt->execute();
            $data = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($data) {
                $user = (new \App\Models\User())->newFromBuilder($data);
                Auth::login($user);
                return response()->json([
                    'message' => 'User login with otp successfully',
                ]);
            } else {
                return response()->json([
                    'message' => 'Invalid OTP or phone number',
                ]);
            }
        }
    }
//    public function editUser(Request $request){
//        $request->validate([
//            'firstName' => 'required|string',
//            'lastName' => 'required|string',
//            'phoneNumber' => 'required|string|unique:users,phoneNumber',
//            'password' => 'required|string|min:6',
//            'city' => 'required|string',
//            'email' => 'required|string|email',
//        ]);
//        $pdo = new PDO("mysql:host=localhost;dbname=example_app", "root", "");
//
//        $stmt = $pdo->prepare("SELECT * FROM users WHERE phoneNumber = :phoneNumber LIMIT 1");
//        $stmt->bindValue(':phoneNumber', $request->phoneNumber);
//        $stmt->execute();
//
//        $data = $stmt->fetch(PDO::FETCH_ASSOC);
//
//        $user = null;
//        if ($data) {
////            $user = (new \App\Models\User())->newFromBuilder($data);
//
//        }else{
//            return response()->json([
//                'message' => 'User not found',
//            ]);
//        }
////        $user
//
//
//    }

    public function editUser(Request $request)
    {
        $request->validate([
            'phoneNumber' => 'required|string', // از روی شماره کاربر رو پیدا می‌کنیم
        ]);

        try {
            $pdo = new PDO("mysql:host=localhost;dbname=example_app", "root", "");
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // آیا کاربر با این شماره وجود دارد؟
            $stmt = $pdo->prepare("SELECT * FROM users WHERE phoneNumber = ?");
            $stmt->execute([$request->phoneNumber]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$user) {
                return response()->json(['error' => 'User not found'], 404);
            }

            // لیست فیلدهایی که اجازه داریم آپدیت کنیم
            $fields = ['firstName', 'lastName', 'password', 'city', 'email'];

            $updateParts = [];
            $bindings = [];

            foreach ($fields as $field) {
                if ($request->filled($field)) {
                    if ($field === 'password') {
                        $updateParts[] = "$field = :$field";
                        $bindings[":$field"] = Hash::make($request->$field);
                    } else {
                        $updateParts[] = "$field = :$field";
                        $bindings[":$field"] = $request->$field;
                    }
                }
            }

            if (empty($updateParts)) {
                return response()->json(['message' => 'No valid fields provided'], 400);
            }

            $bindings[":phoneNumber"] = $request->phoneNumber;
            $sql = "UPDATE users SET " . implode(', ', $updateParts) . " WHERE phoneNumber = :phoneNumber";

            $stmt = $pdo->prepare($sql);
            $stmt->execute($bindings);

            return response()->json(['message' => 'User updated successfully']);
        } catch (\PDOException $e) {
            return response()->json(['error' => 'Database error: ' . $e->getMessage()], 500);
        }
    }


}





