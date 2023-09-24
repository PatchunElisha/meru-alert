<?php declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Traits\JsonTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    use JsonTrait;
    /**
     * ログインする
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function login(Request $request): JsonResponse
    {
        $credentials = $request->only('email', 'password');
        $result = false;
        $message = '';

        if(Auth::attempt($credentials)){
            $request->session()->regenerate();
            $result = true;
            $message = 'ログインが完了しました';
        } else {
            $message = 'ログインが失敗しました';
        }

        return $this->responseJson($result, $message);
    }

    /**
     * ログアウトする
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return $this->responseJson(true, 'ログアウトが完了しました');
    }

}
