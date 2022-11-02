<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use JWTAuth;
use App\Users;

class ApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $username = $request->username;
        $password = $request->password;
        if (!$token = auth('api')->attempt(['username' => $username, 'password' => $password])) {
            return response()->json([
                'success' => false,
                'messager' => "Đăng nhập thất bại",
            ]);
        }
        $data = Users::where('username', $request->username)->first();
        $data->access_token = $token;
        $data->save();
        return response()->json([
            'success' => true,
            'messager' => "Đăng nhập thành công",
            'data' => $data
        ]);
    }

    public function thayDoiMatKhau(Request $request)
    {
        $user = Users::where('username', JWTAuth::user()->username)->first();
        if (!isset($user)) {
            return response()->json([
                'success' => false,
                'messager' => "Token đã hết hạn, vui lòng đăng nhập lại!",
            ]);
        }
        if ($request->password != $request->re_password) {
            return response()->json([
                'success' => false,
                'messager' => "Nhập lại mật khẩu không giống nhau. Vui lòng thử lại!",
            ]);
        }
        $user->password = Hash::make($request->password);
        $user->save();
        return response()->json([
            'success' => true,
            'messager' => "Đã cập nhật thành công mật khẩu mới!"
        ]);
    }

    public function capNhat(Request $request)
    {
        $user = Users::where('username', JWTAuth::user()->username)->first();
        if (!isset($user)) {
            return response()->json([
                'success' => false,
                'messager' => "Token đã hết hạn, vui lòng đăng nhập lại!",
            ]);
        }
        if (!isset($request->name) || !isset($request->phone) || !isset($request->email)) {
            return response()->json([
                'success' => false,
                'messager' => "Vui lòng nhập đầy đủ thông tin",
            ]);
        } else {
            $user->email = $request->email;
            $user->name = $request->name;
            $user->phone = $request->phone;
            $user->save();
            if (!$token = auth('api')->attempt(['username' => $request->username, 'password' => $request->mat_khau])) {
                return response()->json([
                    'success' => false,
                    'messager' => "Cập nhật thất bại",
                ]);
            } else {
                return response()->json([
                    'success' => true,
                    'messager' => "Cập nhật thành công",
                    'token' => $token,
                ]);
            }
        }
    }

    public function getUserInfo(Request $request)
    {
        $user = JWTAuth::user();
        return response()->json($user);
    }
}
