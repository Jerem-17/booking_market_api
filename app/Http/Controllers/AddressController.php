<?php

namespace App\Http\Controllers;

use App\Models\Address;
use Illuminate\Http\Request;
use App\Http\traits\TraitResponse;
use Illuminate\Support\Facades\Validator;

class AddressController extends Controller
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
            $addresss = Address::all()->toArray();
            return $this->ResponseOk('Data retreived.', $addresss);
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
                'nom' => 'required|max:30',
                'latitude' => 'required',
                'longitude' => 'required'
            ]);
            if ($validator->fails()) {
                return $this->ResponseERROR("Fields validations fails.", $validator->errors());
            }
            $address = Address::create([
                'nom' => $request->nom,
                'latitude' => $request->latitude,
                'user_id' => $request->user()->id,
                'longitude' => $request->longitude,
            ]);
            if ($address) {
                return $this->ResponseSuccess("Address created successfully.", $address->toArray());
            } else {
                return $this->ResponseERROR('Address creation failed.');
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
            $address = Address::find($id);
            if ($address) {
                return $this->ResponseOk("Address retreived successfully.", $address->toArray());
            }
            return $this->ResponseERROR("Address not found. Invalid id.");
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
            $address = Address::find($id);
            if ($address) {
                $validator = Validator::make($request->all(), [
                    'nom' => 'required|max:30',
                    'latitude' => 'required|numeric',
                    'longitude' => 'required|numeric'
                ]);
                if ($validator->fails()) {
                    return $this->ResponseERROR("Fields validations fails.", $validator->errors());
                }
                $address->update([
                    'nom' => $request->nom,
                    'latitude' => $request->latitude,
                    'longitude' => $request->longitude,
                ]);
                return $this->ResponseSuccess("Address updated successfully.", $address->toArray());
            }
            return $this->ResponseERROR("Address not found. Invalid Id.");
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
            if (Address::find($id)) {
                Address::destroy($id);
                return $this->ResponseSuccess("Address deleted successfully.", null);
            }
            return $this->ResponseERROR("Address not found. Invalid Id.");
        } catch (\Throwable $th) {
            return  $this->ResponseServerError('Server error !', $th->getMessage());
        }
    }
}
