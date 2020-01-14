<?php

namespace App\Http\Controllers\Video;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Video\VideoActorsService;

class VideoActorsController extends Controller
{
    protected $videoActorsService;

    public function __construct(VideoActorsService $videoActorsService)
    {
        $this->videoActorsService = $videoActorsService;
    }

    public function index(Request $request)
    {
        return view('video.actors', $this->videoActorsService->list($request->input()));
    }

    public function search(Request $request)
    {
        return redirect(get_search_uri($request->input(), 'video_actors'));
    }

    public function save(Request $request, $id)
    {
        $this->videoActorsService->save($request->post(), $id);
        return 'done';
    }
}
