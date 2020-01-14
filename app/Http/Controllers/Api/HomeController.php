<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\System\SysconfigRepository;

class HomeController extends Controller
{
    protected $sysconfigRepository;

    public function __construct(SysconfigRepository $sysconfigRepository)
    {
        $this->sysconfigRepository = $sysconfigRepository;
    }
    
    /**
     * @OA\Post(
     *   path="/maintenance",
     *   summary="網站維護資訊",
     *   tags={"Home"},
     *   @OA\Response(response="200", description="Success")
     * )
     */
    public function maintenance(Request $request)
    {
        $data = $this->sysconfigRepository->getSysconfig();

        return response()->json([
            'success' => true,
            'message' => $data['maintenance_message'] ?? '',
        ]);
    }
}
