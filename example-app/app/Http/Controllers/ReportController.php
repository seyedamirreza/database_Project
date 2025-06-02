<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PDO;

class ReportController extends Controller
{
   public function createReport(Request $request){

       $request->validate([
           'reservation_id' => 'required|integer',
           'title' => 'required|string',
           'status' => 'required|boolean',
           'body' => 'required|string'
       ]);

       $pdo = new PDO("mysql:host=localhost;dbname=example_app", "root", "");


       $stmt = $pdo->prepare("
        INSERT INTO reports (reservation_id, title, status, body, created_at, updated_at)
        VALUES (?, ?, ?, ?, NOW(), NOW())
    ");

       $stmt->execute([
           $request->reservation_id,
           $request->title,
           $request->status,
           $request->body
       ]);

       return response()->json([
           'success' => true,
           'message' => 'Report added successfully',

       ], 201);
   }
}

