<?php

namespace App\Http\Controllers;

use App\Http\Resources\BoardCollection;
use App\Http\Resources\BoardResource;
use App\Models\Board;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BoardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $borad = Board::when(request()->has('search'), function ($query) {
            $query->where('name', 'LIKE', request()->get('search'));
        })->paginate(10);
        return $this->apiResponseResourceCollection(200, 'All Board', BoardResource::collection($borad));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $boradValidate =  $request->validate([
            'name' => 'required|string|unique:boards,name,',
        ]);

         Board::create($boradValidate);
        return $this->apiResponse(201, 'Board create Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Board  $board
     * @return \Illuminate\Http\Response
     */
    public function show(Board $board)
    {

        return $this->apiResponseResourceCollection(200, 'All Board', BoardResource::make($board));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Board  $board
     * @return \Illuminate\Http\Response
     */
    public function edit(Board $board)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Board  $board
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Board $board)
    {
        $request->validate([
            'name' => 'required|string|unique:boards,name,' . $board->name ,
        ]);
        $board->update([
            'name' =>  $request->name
        ]);
        return $this->apiResponse(201, 'Board update Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Board  $board
     * @return \Illuminate\Http\Response
     */
    public function destroy(Board $board)
    {
        $board->delete();
        return $this->apiResponse(201, 'Board Delete Successfully');
    }

    // public function forceDelete($id)
    // {
    //     $borad= Board::withTrashed()->find($id);
    //     $borad->forceDelete();
    //     return $this->apiResponse(201, 'Borad Delete Successfully');
    // }

    public  function boardList(){
        $borad = Board::all();
        return $this->apiResponseResourceCollection(200, 'All Board', BoardResource::collection($borad));
    }
}
