<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Users;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

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
        if (!$token = JWTAuth::attempt(['username' => $username, 'password' => $password])) {
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

    public function logout(Request $request)
    {
        try {
            $customer = Users::find(JWTAuth::user()->id);
            if ($customer->fcm_token == $request->fcm_token) {
                $customer->fcm_token = '';
                $customer->save();
            }
            JWTAuth::invalidate(JWTAuth::getToken());
            return response()->json([
                'status' => 'success',
                'message' => 'Đăng xuất thành công',
            ]);
        } catch (JWTException $e) {
            return response()->json([
                'status' => 'success',
                'message' => 'Đăng xuất thất bại',
            ]);
        } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
        }
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
            if (!$token = JWTAuth::attempt(['username' => $request->username, 'password' => $request->mat_khau])) {
                return response()->json([
                    'success' => false,
                    'messager' => "Cập nhật thất bại",
                ]);
            } else {
                $user->access_token = $token;
                $user->save();
                return response()->json([
                    'success' => true,
                    'messager' => "Cập nhật thành công",
                    'data' => $user
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
