<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\System\SysconfigRepository;
use App\Repositories\System\AdsRepository;
use Validator;
use Exception;

class CommonController extends Controller
{
    protected $sysconfigRepository;

    public function __construct(SysconfigRepository $sysconfigRepository)
    {
        $this->sysconfigRepository = $sysconfigRepository;
    }

    /**
     * @OA\Post(
     *   path="/param",
     *   summary="網站參數",
     *   tags={"Common"},
     *   @OA\RequestBody(
     *       @OA\MediaType(
     *           mediaType="application/x-www-form-urlencoded",
     *           @OA\Schema(
     *               type="object",
     *               @OA\Property(
     *                   property="key",
     *                   description="參數名稱(可不帶)",
     *                   type="string",
     *                   example="video_title",
     *               )
     *           )
     *       )
     *   ),
     *   @OA\Response(response="200", description="Success")
     * )
     */
    public function param(Request $request)
    {
        try {
            $config = $this->sysconfigRepository->getSysconfig();
            $data = [];
            if ($request->key) {
                if (isset($config[$request->key])) {
                    $data[$request->key] = $config[$request->key];
                }
            } else {
                foreach (['video_title'] as $val) {
                    $data[$val] = $config[$val] ?? '';
                }
            }

            return response()->json([
                'success' => true,
                'data'    => $data,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], $e->getCode() ?: 500);
        }
    }

    /**
     * @OA\Post(
     *   path="/adslist",
     *   summary="廣告列表",
     *   tags={"Common"},
     *   @OA\RequestBody(
     *       @OA\MediaType(
     *           mediaType="application/x-www-form-urlencoded",
     *           @OA\Schema(
     *               type="object",
     *               @OA\Property(
     *                   property="type",
     *                   description="廣告位置",
     *                   type="string",
     *                   example="1",
     *               ),
     *               @OA\Property(
     *                   property="page",
     *                   description="頁數",
     *                   type="string",
     *                   example="1",
     *               ),
     *               @OA\Property(
     *                   property="per_page",
     *                   description="一頁幾筆",
     *                   type="string",
     *                   example="10",
     *               ),
     *               required={"type","page","per_page"}
     *           )
     *       )
     *   ),
     *   @OA\Response(response="200", description="Success")
     * )
     */
    public function adsList(Request $request, AdsRepository $adsRepository)
    {
        try {
            $validator = Validator::make($request->all(), [
                'type' => 'required',
            ]);
            if ($validator->fails()) {
                $message = implode("\n", $validator->errors()->all());
                throw new Exception($message, 422);
            }

            $type     = $request->type;
            $page     = $request->page ?: 1;
            $per_page = $request->per_page ?: 10;

            $date = date('Y-m-d H:i:s');
            $where[] = ['type', '=', $type];
            $where[] = ['start_time', '<=', $date];
            $where[] = ['end_time', '>=', $date];

            $result = $adsRepository->where($where)
                ->order(['sort', 'asc'])
                ->paginate($per_page)
                ->result();
            $list = [];
            foreach ($result as $row) {
                $list[] = [
                    'name'  => $row['name'],
                    'image' => asset($row['image']),
                    'url'   => $row['url'],
                ];
            }

            return response()->json([
                'success' => true,
                'page'    => (int)$page,
                'total'   => $result->total(),
                'list'    => $list,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], $e->getCode() ?: 500);
        }
    }
}
