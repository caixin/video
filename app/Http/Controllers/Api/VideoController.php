<?php

namespace App\Http\Controllers\Api;

use JWTAuth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Video\VideoService;
use App\Repositories\Video\VideoRepository;
use App\Repositories\Video\VideoTagsRepository;
use App\Repositories\User\UserRepository;
use App\Repositories\User\UserMoneyLogRepository;
use Validator;
use Exception;

class VideoController extends Controller
{
    protected $videoService;
    protected $videoRepository;
    protected $videoTagsRepository;
    protected $userRepository;
    protected $userMoneyLogRepository;

    public function __construct(
        VideoService $videoService,
        VideoRepository $videoRepository,
        VideoTagsRepository $videoTagsRepository,
        UserRepository $userRepository,
        UserMoneyLogRepository $userMoneyLogRepository
    ) {
        $this->videoService = $videoService;
        $this->videoRepository = $videoRepository;
        $this->videoTagsRepository = $videoTagsRepository;
        $this->userRepository = $userRepository;
        $this->userMoneyLogRepository = $userMoneyLogRepository;
    }

    /**
     * @OA\Post(
     *   path="/video/list",
     *   summary="視頻列表",
     *   tags={"Video"},
     *   @OA\RequestBody(
     *       @OA\MediaType(
     *           mediaType="application/x-www-form-urlencoded",
     *           @OA\Schema(
     *               type="object",
     *               @OA\Property(
     *                   property="search",
     *                   description="搜尋",
     *                   type="string",
     *                   example="",
     *               ),
     *               @OA\Property(
     *                   property="tags",
     *                   description="標籤",
     *                   type="string",
     *                   example="素人",
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
     *               required={"page","per_page"}
     *           )
     *       )
     *   ),
     *   @OA\Response(response="200", description="Success")
     * )
     */
    public function list(Request $request)
    {
        try {
            $page = $request->page ?: 1;
            $list = $this->videoService->getList($request);

            return response()->json([
                'success' => true,
                'page'    => (int)$page,
                'total'   => $list['total'],
                'list'    => $list['list'],
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
     *   path="/video/tags",
     *   summary="熱門標籤",
     *   tags={"Video"},
     *   @OA\RequestBody(
     *       @OA\MediaType(
     *           mediaType="application/x-www-form-urlencoded",
     *           @OA\Schema(
     *               type="object",
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
     *                   example="20",
     *               ),
     *               required={"page","per_page"}
     *           )
     *       )
     *   ),
     *   @OA\Response(response="200", description="Success")
     * )
     */
    public function tags(Request $request)
    {
        try {
            $page = $request->page ?: 1;
            $per_page = $request->per_page ?: 10;
    
            $result = $this->videoTagsRepository
                ->order(['hot','desc'])
                ->paginate($per_page)
                ->result();
            $list = [];
            foreach ($result as $row) {
                $list[] = $row['name'];
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

    /**
     * @OA\Post(
     *   path="/video/detail",
     *   summary="視頻資訊",
     *   tags={"Video"},
     *   @OA\RequestBody(
     *       @OA\MediaType(
     *           mediaType="application/x-www-form-urlencoded",
     *           @OA\Schema(
     *               type="object",
     *               @OA\Property(
     *                   property="keyword",
     *                   description="視頻ID",
     *                   type="string",
     *                   example="niWaHDNjt3S",
     *               ),
     *               required={"keyword"}
     *           )
     *       )
     *   ),
     *   @OA\Response(response="200", description="Success")
     * )
     */
    public function detail(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'keyword' => 'required|exists:video',
            ]);
            if ($validator->fails()) {
                $message = implode("\n", $validator->errors()->all());
                throw new Exception($message, 422);
            }

            $row = $this->videoRepository->search(['keyword'=>$request->keyword])->result_one();
            $all = false;
            //判斷是否登入
            if (JWTAuth::check()) {
                $user = JWTAuth::user();
                $buy = $this->userMoneyLogRepository->getBuyVideo($user['id']);
                if (in_array($row['keyword'], $buy)) {
                    $all = true;
                }
            }

            return response()->json([
                'success' => true,
                'buy'     => $all,
                'data'    => [
                    'keyword'   => $row['keyword'],
                    'name'      => $row['name'],
                    'publish'   => $row['publish'],
                    'actors'    => $row['actors'],
                    'tags'      => $row['tags'],
                    'pic_big'   => $row['pic_b'],
                    'pic_small' => $row['pic_s'],
                    'url'       => $all ? $row['url']: str_replace('end=36000', 'end=300', $row['url']),
                ],
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
     *   path="/video/buy",
     *   summary="購買視頻",
     *   tags={"Video"},
     *   security={{"JWT":{}}},
     *   @OA\RequestBody(
     *       @OA\MediaType(
     *           mediaType="application/x-www-form-urlencoded",
     *           @OA\Schema(
     *               type="object",
     *               @OA\Property(
     *                   property="keyword",
     *                   description="視頻ID",
     *                   type="string",
     *                   example="niWaHDNjt3S",
     *               ),
     *               required={"keyword"}
     *           )
     *       )
     *   ),
     *   @OA\Response(response="200", description="Success")
     * )
     */
    public function buy(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'keyword' => 'required|exists:video',
            ]);
            if ($validator->fails()) {
                $message = implode("\n", $validator->errors()->all());
                throw new Exception($message, 422);
            }

            $user = JWTAuth::user();
            $buy = $this->userMoneyLogRepository->getBuyVideo($user['id']);
            if (in_array($request->keyword, $buy)) {
                throw new Exception('该视频已购买过！', 422);
            }
            if ($user['money'] <= 0) {
                throw new Exception('余额不足！', 422);
            }
            //帳變明細
            $this->userRepository->addMoney($user['id'], 2, -1, "觀看影片-$request->keyword", $request->keyword);

            return response()->json([
                'success' => true,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], $e->getCode() ?: 500);
        }
    }
}
