<?php declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\SearchLists;
use App\Models\SearchResultStocks;
use Illuminate\Http\JsonResponse;

class HomeController extends Controller
{
    private SearchResultStocks $stocks;
    private SearchLists $lists;
    public function __construct()
    {
        $this->stocks = new SearchResultStocks();
        $this->lists = new SearchLists();
    }

    /**
     * 検索で取得したレコードをsearch_lists_id ごとに分けてJson で出力する
     *
     * @param integer $users_id
     * @return JsonResponse
     */
    public function index($users_id): JsonResponse
    {
        $ids = $this->lists->where('users_id', $users_id)->orderBy('id')->pluck('id')->toArray();
        $json = $this->stocks->whereIn('search_lists_id', $ids)->orderBy('id')->get();
        $result = [];
        $count = $now = 0;

        foreach ($json as $params) {
            $searchListsId = $params['search_lists_id'];

            if ($searchListsId != $now) {
                $result[$count] = [];
                $now = $searchListsId;
                $count += 1;
            }

            $result[$count-1][] = [
                'id' => $params['id'],
                'search_lists_id' => $searchListsId,
                'product_name' => $params['product_name'],
                'price' => $params['price'],
                'url' => $params['url'],
                'image_url' => $params['image_url'],
            ];
        }

        return response()->json($result);
    }
}