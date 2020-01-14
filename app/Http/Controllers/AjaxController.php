<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\User\UserRepository;

class AjaxController extends Controller
{
    /**
     * 取得頁首資訊
     */
    public function getTopInfo(UserRepository $userRepository)
    {
        //在線會員
        $top_count['online'] = $userRepository->search([
            'active_time1' => date('Y-m-d H:i:s', time() - 600),
        ])->paginate(100)->result()->total();
        //今日註冊
        $top_count['register'] = $userRepository->search([
            'created_at1' => date('Y-m-d')
        ])->paginate(100)->result()->total();

        return response()->json($top_count);
    }

    /**
     * 設定全局單頁顯示筆數
     */
    public function setPerPage(Request $request)
    {
        if ($request->ajax()) {
            session(['per_page'=>$request->per_page ?: 20]);
            return 'done';
        }
    }

    /**
     * 圖片上傳
     */
    public function imageUpload($dir='images')
    {
        request()->validate([
            'file' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $path = public_path("upload/$dir");
        @mkdir($path, 0777);

        $imageName = time().randPwd(3).'.'.request()->file->getClientOriginalExtension();
        request()->file->move($path, $imageName);

        return response()->json([
            'status'   => 1,
            'filelink' => "./upload/$dir/$imageName",
        ]);
    }

    /**
     * 檔案上傳
     */
    public function file_upload($dir='files')
    {
        $path = public_path("upload/$dir");
        @mkdir($path, 0777);

        $imageName = time().randPwd(3).'.'.request()->file->getClientOriginalExtension();
        request()->file->move($path, $imageName);

        return response()->json([
            'status'   => 1,
            'filelink' => "./upload/$dir/$imageName",
        ]);
    }
}
