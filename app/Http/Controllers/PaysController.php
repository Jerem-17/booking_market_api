<?php

namespace App\Http\Controllers;

use App\Http\Resources\PaysResource;
use App\Models\Pays;
use Illuminate\Http\Request;
use App\Http\traits\TraitResponse;
use Illuminate\Support\Facades\Validator;

class PaysController extends Controller
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
            $pays = Pays::all()->toArray();
            return $this->ResponseOk('Data retreived.', $pays);
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
                'nom' => 'required'
            ]);
            if ($validator->fails()) {
                return $this->ResponseERROR("Field 'name' is required.", $validator->errors());
            }
            $pays = Pays::create([
                'nom' => $request->nom
            ]);
            if ($pays) {
                return $this->ResponseSuccess("Country created successfully.", $pays->toArray());
            } else {
                return $this->ResponseERROR('Country creation failed.');
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
            $pays = Pays::find($id);
            if ($pays) {
                return $this->ResponseOk("Country retreived successfully.", $pays->toArray());
            }
            return $this->ResponseERROR("Country not found. Invalid id.");
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
            $pays = Pays::find($id);
            if ($pays) {
                $validator = Validator::make($request->all(), [
                    'nom' => 'required'
                ]);
                if ($validator->fails()) {
                    return $this->ResponseERROR("Field 'name' is required.", $validator->errors());
                }
                $pays->update([
                    'nom' => $request->name
                ]);
                return $this->ResponseSuccess("Country updated successfully.", $pays->toArray());
            }
            return $this->ResponseERROR("Country not found. Invalid Id.");
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
            if (Pays::find($id)) {
                Pays::destroy($id);
                return $this->ResponseSuccess("Country deleted successfully.", null);
            }
            return $this->ResponseERROR("Country not found. Invalid Id.");
        } catch (\Throwable $th) {
            return  $this->ResponseServerError('Server error !', $th->getMessage());
        }
    }
}
