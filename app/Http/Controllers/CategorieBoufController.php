<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CategorieBouf;
use App\Http\traits\TraitResponse;
use Illuminate\Support\Facades\Validator;

class CategorieBoufController extends Controller
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
            $categorieBoufs = CategorieBouf::all()->toArray();
            return $this->ResponseOk('Data retreived.', $categorieBoufs);
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
                'type' => 'required'
            ]);
            if ($validator->fails()) {
                return $this->ResponseERROR("Field 'type' is required.", $validator->errors());
            }
            $categorieBouf = CategorieBouf::create([
                'type' => $request->type
            ]);
            if ($categorieBouf) {
                return $this->ResponseSuccess("CategorieBouf created successfully.", $categorieBouf->toArray());
            } else {
                return $this->ResponseERROR('CategorieBouf creation failed.');
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
            $categorieBouf = CategorieBouf::find($id);
            if ($categorieBouf) {
                return $this->ResponseOk("CategorieBouf retreived successfully.", $categorieBouf->toArray());
            }
            return $this->ResponseERROR("CategorieBouf not found. Invalid id.");
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
            $categorieBouf = CategorieBouf::find($id);
            if ($categorieBouf) {
                $validator = Validator::make($request->all(), [
                    'type' => 'required'
                ]);
                if ($validator->fails()) {
                    return $this->ResponseERROR("Field 'type' is required.", $validator->errors());
                }
                $categorieBouf->update([
                    'type' => $request->type
                ]);
                return $this->ResponseSuccess("CategorieBouf updated successfully.", $categorieBouf->toArray());
            }
            return $this->ResponseERROR("CategorieBouf not found. Invalid Id.");
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
            if (CategorieBouf::find($id)) {
                CategorieBouf::destroy($id);
                return $this->ResponseSuccess("CategorieBouf deleted successfully.", null);
            }
            return $this->ResponseERROR("CategorieBouf not found. Invalid Id.");
        } catch (\Throwable $th) {
            return  $this->ResponseServerError('Server error !', $th->getMessage());
        }
    }
}
