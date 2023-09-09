<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\traits\TraitResponse;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
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
            $products = Product::all()->toArray();
            return $this->ResponseOk('Data retreived.', $products);
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
                'nom' => "required|max:30",
                'prix' => "required",
                'description' => "required",
                'boutique_id' => "required|exists:boutiques,id",
                'suplement' => "required",
                'categoriebouf_id' => "required|exists:categorieboufs,id",
            ]);
            if ($validator->fails()) {
                return $this->ResponseERROR("Fields validation fails.", $validator->errors());
            }
            $product = Product::create([
                'nom' => $request->nom,
                'prix' => $request->prix,
                'description' => $request->description,
                'boutique_id' => $request->boutique_id,
                'suplement' => $request->suplement,
                'categoriebouf_id' => $request->categoriebouf_id,
            ]);
            if ($product) {
                return $this->ResponseSuccess("Product created successfully.", $product->toArray());
            } else {
                return $this->ResponseERROR('Product creation failed.');
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
            $product = Product::find($id);
            if ($product) {
                return $this->ResponseOk("Product retreived successfully.", $product->toArray());
            }
            return $this->ResponseERROR("Product not found. Invalid id.");
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
            $product = Product::find($id);
            if ($product) {
                $validator = Validator::make($request->all(), [
                    'nom' => "required|max:30",
                    'prix' => "required|numeric",
                    'description' => "required",
                    'boutique_id' => "required|exists:boutiques,id",
                    'suplement' => "required|numeric",
                    'categoriebouf_id' => "required|exists:categorieboufs,id",
                ]);
                if ($validator->fails()) {
                    return $this->ResponseERROR("Fields validation fails.", $validator->errors());
                }
                $product->update([
                    'nom' => $request->nom,
                    'prix' => $request->prix,
                    'description' => $request->description,
                    'boutique_id' => $request->boutique_id,
                    'suplement' => $request->suplement,
                    'categoriebouf_id' => $request->categoriebouf_id,
                ]);
                return $this->ResponseSuccess("Product updated successfully.", $product->toArray());
            }
            return $this->ResponseERROR("Product not found. Invalid Id.");
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
            if (Product::find($id)) {
                Product::destroy($id);
                return $this->ResponseSuccess("Product deleted successfully.", null);
            }
            return $this->ResponseERROR("Product not found. Invalid Id.");
        } catch (\Throwable $th) {
            return  $this->ResponseServerError('Server error !', $th->getMessage());
        }
    }
}
