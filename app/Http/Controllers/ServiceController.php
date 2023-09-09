<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;
use App\Http\traits\TraitResponse;
use Illuminate\Support\Facades\Validator;

class ServiceController extends Controller
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
            $services = Service::all()->toArray();
            return $this->ResponseOk('Data retreived.', $services);
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
            $service = Service::create([
                'type' => $request->type
            ]);
            if ($service) {
                return $this->ResponseSuccess("Service created successfully.", $service->toArray());
            } else {
                return $this->ResponseERROR('Service creation failed.');
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
            $service = Service::find($id);
            if ($service) {
                return $this->ResponseOk("Service retreived successfully.", $service->toArray());
            }
            return $this->ResponseERROR("Service not found. Invalid id.");
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
            $service = Service::find($id);
            if ($service) {
                $validator = Validator::make($request->all(), [
                    'type' => 'required'
                ]);
                if ($validator->fails()) {
                    return $this->ResponseERROR("Field 'type' is required.", $validator->errors());
                }
                $service->update([
                    'type' => $request->type
                ]);
                return $this->ResponseSuccess("Service updated successfully.", $service->toArray());
            }
            return $this->ResponseERROR("Service not found. Invalid Id.");
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
            if (Service::find($id)) {
                Service::destroy($id);
                return $this->ResponseSuccess("Service deleted successfully.", null);
            }
            return $this->ResponseERROR("Service not found. Invalid Id.");
        } catch (\Throwable $th) {
            return  $this->ResponseServerError('Server error !', $th->getMessage());
        }
    }
}
