<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\offer\storeOfferRequest;
use App\Http\Requests\offer\UpdateOfferRequest;
use App\Models\books;
use App\Models\offer;
use Illuminate\Http\Request;

class AdminOfferController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $offer = offer::all();
        return success_response($offer);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(storeOfferRequest $request)
    {
        $request['old_price'] = books::findOrFail($request['book_id'])->price;
        $offer = offer::create($request->all());
        return success_response($offer);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $offer = offer::findOrFail($id);
        return success_response($offer);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateOfferRequest $request, $id)
    {
        $offer = offer::findOrFail($id)->update($request->all());
        return success_response(offer::findOrFail($id));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        offer::findOrFail($id)->delete();
        return success_response();
    }
}
