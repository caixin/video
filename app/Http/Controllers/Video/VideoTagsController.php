<?php

namespace App\Http\Controllers\Video;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Video\VideoTagsService;

class VideoTagsController extends Controller
{
    protected $videoTagsService;

    public function __construct(VideoTagsService $videoTagsService)
    {
        $this->videoTagsService = $videoTagsService;
    }

    public function index(Request $request)
    {
        return view('video.tags', $this->videoTagsService->list($request->input()));
    }

    public function search(Request $request)
    {
        return redirect(get_search_uri($request->input(), 'video_tags'));
    }

    public function save(Request $request, $id)
    {
        $this->videoTagsService->save($request->post(), $id);
        return 'done';
    }
}
