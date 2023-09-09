<?php

namespace App\Http\Controllers;

use App\Models\ProductShop;
use Illuminate\Http\Request;
use App\Http\traits\TraitResponse;
use Illuminate\Support\Facades\Validator;

class ProductShopController extends Controller
{
    use TraitResponse;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $productShops = ProductShop::all()->toArray();
            return $this->ResponseOk('Data retreived.', $productShops);
        } catch (\Throwable $th) {
            return  $this->ResponseServerError('Server error !', $th->getMessage());
        }
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
        try {
            $validator = Validator::make($request->all(), [
                'boutique_id' => "required|exists:users,id",
                'article' => "required|max:30",
                'categorievitrine_id' => "required|exists:categorievitrines,id",
                'prix' => "required|max:60",
                'dm' => "required|numeric",
            ]);
            if ($validator->fails()) {
                return $this->ResponseERROR("Fields validation fails.", $validator->errors());
            }
            $productShop = ProductShop::create([
                'boutique_id' => $request->boutique_id,
                'article' => $request->article,
                'categorievitrine_id' => $request->categorievitrine_id,
                'prix' => $request->prix,
                'dm' => $request->dm
            ]);
            if ($productShop) {
                return $this->ResponseSuccess("ProductShop created successfully.", $productShop->toArray());
            } else {
                return $this->ResponseERROR('ProductShop creation failed.');
            }
        } catch (\Throwable $th) {
            return  $this->ResponseServerError('Server error !', $th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $productShop = ProductShop::find($id);
            if ($productShop) {
                return $this->ResponseOk("ProductShop retreived successfully.", $productShop->toArray());
            }
            return $this->ResponseERROR("ProductShop not found. Invalid id.");
        } catch (\Throwable $th) {
            return  $this->ResponseServerError('Server error !', $th->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $productShop = ProductShop::find($id);
            if ($productShop) {
                $validator = Validator::make($request->all(), [
                    'boutique_id' => "required|exists:users,id",
                    'article' => "required|max:30",
                    'categorievitrine_id' => "required|exists:categorievitrines,id",
                    'prix' => "required|max:60",
                    'dm' => "required|numeric",
                ]);
                if ($validator->fails()) {
                    return $this->ResponseERROR("Fields validation fails.", $validator->errors());
                }
                $productShop->update([
                    'boutique_id' => $request->boutique_id,
                    'article' => $request->article,
                    'categorievitrine_id' => $request->categorievitrine_id,
                    'prix' => $request->prix,
                    'dm' => $request->dm
                ]);
                return $this->ResponseSuccess("ProductShop updated successfully.", $productShop->toArray());
            }
            return $this->ResponseERROR("ProductShop not found. Invalid Id.");
        } catch (\Throwable $th) {
            return  $this->ResponseServerError('Server error !', $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            if (ProductShop::find($id)) {
                ProductShop::destroy($id);
                return $this->ResponseSuccess("ProductShop deleted successfully.", null);
            }
            return $this->ResponseERROR("ProductShop not found. Invalid Id.");
        } catch (\Throwable $th) {
            return  $this->ResponseServerError('Server error !', $th->getMessage());
        }
    }
}
