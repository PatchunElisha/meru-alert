<?php declare(strict_types=1);

namespace App\Http\Traits;

use Illuminate\Http\JsonResponse;

trait JsonTrait {
    /**
     * 引数の内容をJsonに変換して出力する
     *
     * @param bool $result
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    private function responseJson(bool $result, string $message): JsonResponse {
        $results = [
            'result' => $result,
            'message' => $message,
        ];

        return response()->json($results);
    }
}
