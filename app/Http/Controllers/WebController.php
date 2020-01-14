<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Video\VideoService;
use App\Services\System\MessageService;
use App\Repositories\Video\VideoRepository;
use App\Repositories\Video\VideoTagsRepository;
use App\Repositories\User\UserRepository;
use App\Repositories\User\UserMoneyLogRepository;
use App\Repositories\System\AdsRepository;
use Illuminate\Support\Facades\Redis;
use Exception;
use Auth;

/**
 * 過渡期程式-前端接手後廢除
 */
class WebController extends Controller
{
    protected $videoService;
    protected $messageService;
    protected $videoRepository;
    protected $videoTagsRepository;
    protected $userRepository;
    protected $userMoneyLogRepository;
    protected $adsRepository;

    public function __construct(
        VideoService $videoService,
        MessageService $messageService,
        VideoRepository $videoRepository,
        VideoTagsRepository $videoTagsRepository,
        UserRepository $userRepository,
        UserMoneyLogRepository $userMoneyLogRepository,
        AdsRepository $adsRepository
    ) {
        $this->videoService = $videoService;
        $this->messageService = $messageService;
        $this->videoRepository = $videoRepository;
        $this->videoTagsRepository = $videoTagsRepository;
        $this->userRepository = $userRepository;
        $this->userMoneyLogRepository = $userMoneyLogRepository;
        $this->adsRepository = $adsRepository;
        session(['per_page'=>12]);
    }

    public function index(Request $request)
    {
        $request->per_page = $request->per_page ?? 40;
        $video = $this->videoService->getList($request);
        //上方廣告
        $search['domain'] = $request->server('HTTP_HOST');
        $search['enabled'] = 1;
        $search['type'] = 1;
        $ads_up = $this->adsRepository->search($search)->result();
        //下方廣告
        $search['type'] = 2;
        $ads_down = $this->adsRepository->search($search)->result();
        //會員數
        $members = round((time() - strtotime('2019-01-01')) / 88888);
        //觀看數
        $watchs = round((time() - strtotime(date('Y-m-d'))) * 1.111);
        //新進影片
        $newvideo = round((strtotime(date('Y-m-d')) - strtotime(date('Y-m-01'))+86400) / 86400 * 6.666);
        //累計影片時數
        $videohours = round((time() - strtotime('2000-01-01')) / 14400);

        return view('web.index', [
            'page'       => 'index',
            'video'      => $video['list'],
            'ads_up'     => $ads_up,
            'ads_down'   => $ads_down,
            'members'    => $members,
            'watchs'     => $watchs,
            'newvideo'   => $newvideo,
            'videohours' => $videohours,
        ]);
    }

    public function detail(Request $request, $keyword)
    {
        $row = $this->videoRepository->search(['keyword'=>$keyword])->result_one();
        if ($row === null) {
            $url = route('web.index');
            echo "<script>alert('查无此影片');location.href='$url';</script>";
        }
        if ($row['status'] == 0) {
            $url = route('web.index');
            echo "<script>alert('该影片已过期');location.href='$url';</script>";
        }
        //更多影片
        $tags = explode(',', $row['tags']);
        $where[] = ['keyword', '<>', $keyword];
        $search['tags'] = $tags[0] ?? '';
        $search['status'] = 1;
        $more = $this->videoRepository->where($where)->search($search)
                    ->order(['rand()','asc'])->limit([0,16])->result();
        //上一部
        $where = [];
        $where[] = ['publish', '>=', $row['publish']];
        $where[] = ['keyword', '>', $row['keyword']];
        $where[] = ['status', '=', 1];
        $prev = $this->videoRepository->where($where)->order(['publish'=>'asc','keyword'=>'asc'])->result_one();
        //下一部
        $where = [];
        $where[] = ['publish', '<=', $row['publish']];
        $where[] = ['keyword', '<', $row['keyword']];
        $where[] = ['status', '=', 1];
        $next = $this->videoRepository->where($where)->order(['publish'=>'desc','keyword'=>'desc'])->result_one();
        $all = $request->secret == 'iloveav';
        $user = [];
        //判斷是否登入
        if (Auth::guard('web')->check()) {
            $user = Auth::guard('web')->user();
            //24H內購買過的清單
            $buy = $this->userMoneyLogRepository->getBuyVideo($user['id']);
            if (in_array($row['keyword'], $buy)) {
                $all = true;
            }
            //免費看
            if (strtotime($user['free_time']) > time()) {
                $all = true;
            }
        }
        //廣告
        $search['domain'] = $request->server('HTTP_HOST');
        $search['enabled'] = 1;
        $search['type'] = 11;
        $ads = $this->adsRepository->search($search)->result();

        return view('web.detail', [
            'page'     => 'detail',
            'user'     => $user,
            'all'      => $all,
            'more'     => $more,
            'prev_url' => $prev['keyword'] ?? '',
            'next_url' => $next['keyword'] ?? '',
            'video'    => $row,
            'ads'      => $ads,
        ]);
    }

    public function search(Request $request)
    {
        $data = $this->videoService->list($request->input());
        $data['page'] = 'search';

        return view('web.search', $data);
    }

    public function video(Request $request)
    {
        $data = $this->videoService->list($request->input());
        $data['page'] = 'video';

        return view('web.video', $data);
    }

    public function tags(Request $request)
    {
        //標籤
        $result = $this->videoTagsRepository->search(['hot'=>1])->result();
        $tags = [];
        foreach ($result as $row) {
            $tags[] = $row['name'];
        }
        //影片列表
        $data = $this->videoService->list($request->input());
        $data['page'] = 'tags';
        $data['tags'] = $tags;
        $data['param'] = $request->input();

        return view('web.tags', $data);
    }

    public function forgot(Request $request)
    {
        return view('web.forgot', [
            'page' => 'forgot',
        ]);
    }

    public function forgotAction(Request $request)
    {
        $this->validate($request, [
            'mobile'      => 'required|telphone',
            'verify_code' => 'required',
            'password'    => 'required|min:6|max:12',
            'repassword'  => 'required|same:password',
        ]);

        try {
            if (!$forgot = Redis::get("forgot:phone:$request[mobile]")) {
                throw new Exception('重置验证码错误(err01)');
            }
            $forgot = json_decode($forgot, true);
            if ($forgot['code'] != $request['verify_code']) {
                throw new Exception('重置验证码错误(err02)');
            }

            $user = $this->userRepository->getDataByUsername($request->mobile);
            $this->userRepository->update([
                'password' => $request->password
            ], $user->id);

            Redis::del("forgot:phone:$request[mobile]");

            $url = route('web.login');
            return "<script>alert('密码修改完成!');location.href='$url';</script>";
        } catch (Exception $e) {
            return back()->withErrors($e->getMessage());
        }
    }

    public function profile(Request $request)
    {
        $user = \Auth::user();
        //推薦紀錄
        $result = $this->userRepository->getReferrerList($user->id);
        foreach ($result as $key => $row) {
            $row->username = substr($row->username, 0, 3).'*****'.substr($row->username, -3);
            $row->created_at = date('Y-m-d H:i:s', strtotime($row->created_at));
            $result[$key] = $row;
        }
        //帳變明細
        $moneylog = $this->userMoneyLogRepository->search(['uid'=>$user->id])
                    ->order(['created_at','desc'])->limit([0,10])->result();
        foreach ($moneylog as $key => $row) {
            if ($row['type'] == 1) {
                $row->description = mb_substr($row->description, 0, -8).'*****'.mb_substr($row->description, -3);
            }
            if ($row['type'] == 2) {
                $url = route('web.detail', ['keyword'=>$row['video_keyword']]);
                $row->description = str_replace($row['video_keyword'], "<a href='$url' target=\"_blank\">$row[video_keyword]</a>", $row['description']);
            }

            $moneylog[$key] = $row;
        }

        return view('web.profile', [
            'page'   => 'profile',
            'result' => $result,
            'moneylog' => $moneylog,
            'user'   => [
                'id'         => $user->id,
                'username'   => $user->username,
                'money'      => $user->money,
                'free_time'  => $user->free_time,
                'status'     => $user->status,
                'created_at' => date('Y-m-d H:i:s', strtotime($user->created_at)),
            ],
        ]);
    }

    public function moneylog(Request $request)
    {
        $user = \Auth::user();
        //推薦紀錄
        $result = $this->userRepository->getReferrerList($user->id);
        $reflist = [];
        foreach ($result as $row) {
            $reflist[] = [
                'username'    => substr($row->username, 0, 7).'****',
                'money'       => $row->money,
                'status'      => $row->status,
                'login_time'  => $row->login_time,
                'created_at' => date('Y-m-d H:i:s', strtotime($row->created_at)),
            ];
        }

        return view('web.moneylog', [
            'page'    => 'profile',
            'reflist' => $reflist,
            'user'    => [
                'id'         => $user->id,
                'username'   => $user->username,
                'money'      => $user->money,
                'status'     => $user->status,
                'created_at' => date('Y-m-d H:i:s', strtotime($user->created_at)),
            ],
        ]);
    }

    public function buy(Request $request)
    {
        try {
            $validator = \Validator::make($request->all(), [
                'keyword' => 'required|exists:video',
            ]);
            if ($validator->fails()) {
                $message = implode("\n", $validator->errors()->all());
                throw new Exception($message, 422);
            }

            $user = \Auth::user();
            $buy = $this->userMoneyLogRepository->getBuyVideo($user['id']);
            if (in_array($request->keyword, $buy)) {
                throw new Exception('该视频已购买过！', 422);
            }
            if ($user['money'] <= 0) {
                throw new Exception('余额不足！', 422);
            }
            //帳變明細
            $this->userRepository->addMoney($user['id'], 2, -1, "观看影片-$request->keyword", $request->keyword);

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

    public function message(Request $request)
    {
        return view('web.message', $this->messageService->create($request->input()));
    }

    public function messageStore(Request $request)
    {
        $this->validate($request, [
            'type'    => 'required',
            'content' => 'required',
        ], [
            'type.required'    => '请选择一项问题类型',
            'content.required' => '问题描述 不能为空',
        ]);

        try {
            //判斷是否登入
            $uid = 0;
            if (Auth::guard('web')->check()) {
                $uid = Auth::user()->id;
            }
            $this->messageService->store([
                'uid'     => $uid,
                'type'    => $request->type,
                'content' => $request->content,
            ]);

            $url = route('web.message');
            return "<script>alert('留言已送出!');location.href='$url';</script>";
        } catch (Exception $e) {
            return back()->withErrors($e->getMessage());
        }
    }
}
