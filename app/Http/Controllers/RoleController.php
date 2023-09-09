<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use App\Http\traits\TraitResponse;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller
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
            $roles = Role::all()->toArray();
            return $this->ResponseOk('Data retreived.', $roles);
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
                'name' => 'required'
            ]);
            if ($validator->fails()) {
                return $this->ResponseERROR("Field 'name' is required.", $validator->errors());
            }
            $role = Role::create([
                'name' => $request->name
            ]);
            if ($role) {
                return $this->ResponseSuccess("Role created successfully.", $role->toArray());
            } else {
                return $this->ResponseERROR('Role creation failed.');
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
            $role = Role::find($id);
            if ($role) {
                return $this->ResponseOk("Role retreived successfully.", $role->toArray());
            }
            return $this->ResponseERROR("Role not found. Invalid id.");
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
            $role = Role::find($id);
            if ($role) {
                $validator = Validator::make($request->all(), [
                    'name' => 'required'
                ]);
                if ($validator->fails()) {
                    return $this->ResponseERROR("Field 'name' is required.", $validator->errors());
                }
                $role->update([
                    'name' => $request->name
                ]);
                return $this->ResponseSuccess("Role updated successfully.", $role->toArray());
            }
            return $this->ResponseERROR("Role not found. Invalid Id.");
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
            if (Role::find($id)) {
                Role::destroy($id);
                return $this->ResponseSuccess("Role deleted successfully.", null);
            }
            return $this->ResponseERROR("Role not found. Invalid Id.");
        } catch (\Throwable $th) {
            return  $this->ResponseServerError('Server error !', $th->getMessage());
        }
    }
}
