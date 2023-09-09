<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CategorieVitrine;
use App\Http\traits\TraitResponse;
use Illuminate\Support\Facades\Validator;

class CategorieVitrineController extends Controller
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
            $categorieVitrines = CategorieVitrine::all()->toArray();
            return $this->ResponseOk('Data retreived.', $categorieVitrines);
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
            $categorieVitrine = CategorieVitrine::create([
                'type' => $request->type
            ]);
            if ($categorieVitrine) {
                return $this->ResponseSuccess("CategorieVitrine created successfully.", $categorieVitrine->toArray());
            } else {
                return $this->ResponseERROR('CategorieVitrine creation failed.');
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
            $categorieVitrine = CategorieVitrine::find($id);
            if ($categorieVitrine) {
                return $this->ResponseOk("CategorieVitrine retreived successfully.", $categorieVitrine->toArray());
            }
            return $this->ResponseERROR("CategorieVitrine not found. Invalid id.");
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
            $categorieVitrine = CategorieVitrine::find($id);
            if ($categorieVitrine) {
                $validator = Validator::make($request->all(), [
                    'type' => 'required'
                ]);
                if ($validator->fails()) {
                    return $this->ResponseERROR("Field 'type' is required.", $validator->errors());
                }
                $categorieVitrine->update([
                    'type' => $request->type
                ]);
                return $this->ResponseSuccess("CategorieVitrine updated successfully.", $categorieVitrine->toArray());
            }
            return $this->ResponseERROR("CategorieVitrine not found. Invalid Id.");
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
            if (CategorieVitrine::find($id)) {
                CategorieVitrine::destroy($id);
                return $this->ResponseSuccess("CategorieVitrine deleted successfully.", null);
            }
            return $this->ResponseERROR("CategorieVitrine not found. Invalid Id.");
        } catch (\Throwable $th) {
            return  $this->ResponseServerError('Server error !', $th->getMessage());
        }
    }
}
