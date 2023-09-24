<?php declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\SearchLists;
use App\Models\SearchResultStocks;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    private SearchLists $list;
    private SearchResultStocks $result;

    public function __construct() {
        $this->list = new SearchLists();
        $this->result = new SearchResultStocks();
    }

    /**
     * 検索情報を取得する
     *
     * @param integer $id
     * @return JsonResponse
     */
    public function index($id): JsonResponse
    {
        $params = $this->list->where('users_id', $id)->orderBy('id')->get();
        return response()->json($params);
    }

    /**
     * 検索情報を登録する
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $success = false;
        $message = '';
        if ($request->isJson()) {
            try{
                $json = $request->json()->all();
                $data = json_decode(json_encode($json), true)['params'];

                $this->list->create($data);

                $success = true;
                $message = '登録が完了しました。';
            }catch(Exception $e){
                $success = false;
                $message = '登録が失敗しました。';
            }
        }

        return response()->json([
            'success' => $success,
            'message' => $message,
        ]);
    }

    /**
     * 検索情報を更新する
     *
     * @param Request $request
     * @param string $id
     * @return JsonResponse
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $success = false;
        $message = '';

        if ($request->isJson()) {
            try{
                $json = $request->json()->all();
                $data = json_decode(json_encode($json), true)['params'];
                $this->list->where('id', $id)->update($data);
                $this->result->where('search_lists_id', $id)->delete();

                $message = '更新が完了しました。';
            }catch(Exception $e){
                $message = '更新が失敗しました。';
            }
        }

        return response()->json([
            'success' => $success,
            'message' => $message,
        ]);
    }

    /**
     * 検索情報のレコードを削除する
     *
     * @param string $id
     * @return JsonResponse
     */
    public function destroy(string $id): JsonResponse
    {
        $success = false;
        $message = '';

        try{
            $this->list->destroy($id);

            $message = '削除が完了しました。';
        }catch(Exception $e){
            $message = '削除に失敗しました。';
        }

        return response()->json([
            'success' => $success,
            'message' => $message,
        ]);
    }
}
