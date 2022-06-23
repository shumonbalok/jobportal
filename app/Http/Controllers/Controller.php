<?php

namespace App\Http\Controllers;

use Gate;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $itemPerPage = 15;

    public function checkPermission(String $permission)
    {
        abort_if(Gate::denies($permission), 403);
    }

    public function checkPermissions(Array $permissions)
    {
        abort_if(Gate::none($permissions), 403);
    }

    public function putSL($collection)
    {
        $start = ($collection->currentPage() * $this->itemPerPage - $this->itemPerPage) + 1;
        $collection->each(function ($value) use (&$start) {
            $value->sl = $start++;
        });
    }



    public function apiResponse(int $statusCode, string $statusMessage,  $data = []): JsonResponse
    {
        $data['message'] = $statusMessage;
        // $data['status'] = $status;
        return response()->json($data, $statusCode);
    }

    public function apiResponseResourceCollection(int $statusCode, string $statusMessage, object $resourceCollection): JsonResponse
    {
        $resourceCollection = $resourceCollection->additional([
            'message' => $statusMessage
        ])->response()->getData();
        return response()->json($resourceCollection, $statusCode);
    }



}
