<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class StudentController extends Controller
{
    public function register(Request $request) {
        $request->validate([
            'name' => 'required',
            'password' => 'required|confirmed',
            'email' => 'required|email|unique:students,email,except,id',
        ]);

        $student = new Student();
        $student->name = $request->name;
        $student->email = $request->email;
        $student->password = Hash::make($request->password);
        $student->phone_no = $request->phone_no ? $request->phone_no : null;
        $student->save();

        return response()->json([
            "status" => 1,
            "message" => "student registered",
            "data" => $student
        ]);
    }

    public function login(Request $request) {
        $request->validate([
            'email' => 'required|email|exists:students,email',
            'password' => 'required',
        ]);
        $student = Student::where("email", $request->email)->first();
        if (Hash::check($request->password, $student->password)) {
            $token = $student->createToken("auth_token")->plainTextToken;
            return response()->json([
                "status" => 1,
                "message" => "student login",
                "token" => $token,
                "data" => $student
            ], 200);
        } else {
            return response()->json([
                "status" => 0,
                "message" => "incorrect password",
            ], 404);
        }
    }

    //need to send Authorization with format "Bearer token" 
    public function profile() {
        return response()->json([
            "status" => 1,
            "message" => "Student Profile",
            "data" => auth()->user()
        ]);
    }

    public function logout() {
        auth()->user()->tokens()->delete();
        return response()->json([
            "status" => 1,
            "message" => "Student Logout Successfully",
        ]);
    }
}