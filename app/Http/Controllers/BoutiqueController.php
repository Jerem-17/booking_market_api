<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Boutique;
use Illuminate\Http\Request;
use App\Http\traits\TraitResponse;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class BoutiqueController extends Controller
{
    use TraitResponse;

    /**
     * Change boutique image.
     *
     * @return \Illuminate\Http\Response
     */

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'telephone' => 'required',
            'password' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->ResponseERROR("Fields validation fails.", $validator->errors());
        }
        $user = User::where('telephone', $request->telephone)->whereHas('roles', function ($q) {
                $q->where('name', 'seller');
            })->first();
            if (!$user) {
                return $this->ResponseERROR("no user found");
            }
            if (!$user->activated) {
                $message = 'Account not activated yet!';
                return $this->ResponseERROR($message);
            }
            if ($user && Hash::check($request->password, $user->password)) {
                $token = $user->createToken('my-app-token', ['asseller'])->plainTextToken;
                return $this->ResponseSuccess("User logged in successfully.", [
                    'token' => $token,
                    'user' => $user,
                    'boutique'=>$user->boutiques
                ]);
            }

    }

    public function changeImage(Request $request)
    {
        try {
            if ($request->file('image')) {
                $validator = Validator::make($request->all(), [
                    'image' => "image|mimes:jpg,png,jpeg,gif,svg|max:2048",
                    'boutique_id' => "required|numeric|exists:boutiques,id"
                ]);
                if ($validator->fails()) {
                    return $this->ResponseERROR("Fields validation fails.", $validator->errors());
                }
                $path = $request->file('image')
                    ->storeAs("boutiques", Carbon::now() . '.jpg', "images");
                $boutique = Boutique::find($request->boutique_id);
                $boutique->update([
                    'image' => $path
                ]);
                return $this->ResponseOk('Boutique image updated successfully.', $boutique->toArray());
            }
            return $this->ResponseERROR("Field image is required.", ['image' => null]);
        } catch (\Throwable $th) {
            return  $this->ResponseServerError('Server error !', $th->getMessage());
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $boutiques = Boutique::all()->toArray();
            return $this->ResponseOk('Data retreived.', $boutiques);
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
                'etablissement' => "required|max:30",
                'user_id' => "exists:users,id",
                'pays_id' => "required|exists:pays,id",
                "contact" => "required",
                "adresse" => "string",
                'service_id' => "required|exists:services,id",
                'latitude' => "required",
                'longitude' => "required",
            ]);
            if ($validator->fails()) {
                return $this->ResponseERROR("Fields validation fails.", $validator->errors());
            }
            $boutique = Boutique::create([
                'etablissement' => $request->etablissement,
                'user_id' => $request->user()->id,
                'pays_id' => $request->pays_id,
                "contact" => $request->contact,
                "adresse" => $request->adresse,
                'service_id' => $request->service_id,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
            ]);
            if ($boutique) {
                return $this->ResponseSuccess("Boutique created successfully.", $boutique->toArray());
            } else {
                return $this->ResponseERROR('Boutique creation failed.');
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
            $boutique = Boutique::find($id);
            if ($boutique) {
                return $this->ResponseOk("Boutique retreived successfully.", $boutique->toArray());
            }
            return $this->ResponseERROR("Boutique not found. Invalid id.");
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
            $boutique = Boutique::find($id);
            if ($boutique) {
                $validator = Validator::make($request->all(), [
                    'etablissement' => "required|max:30",
                    'user_id' => "required|exists:users,id",
                    'pays_id' => "required|exists:pays,id",
                    'region' => "required",
                    'service_id' => "required|exists:services,id",
                    'latitude' => "required|numeric",
                    'longitude' => "required|numeric",
                ]);
                if ($validator->fails()) {
                    return $this->ResponseERROR("Fields validation fails.", $validator->errors());
                }
                $path = $boutique->image;
                if ($request->file('image')) {
                    $validator = Validator::make($request->all(), [
                        'image' => "image|mimes:jpg,png,jpeg,gif,svg|max:2048",
                    ]);
                    if ($validator->fails()) {
                        return $this->ResponseERROR("Image field validation fails.", $validator->errors());
                    }
                    // $path = $request->file('image')
                    //     ->storeAs(self::BOUTIQUES_IMG_PATH, Carbon::now() . '.jpg', self::IMAGES_DISK);
                }
                $boutique->update([
                    'image' => $path,
                    'etablissement' => $request->etablissement,
                    'user_id' => $request->user_id,
                    'pays_id' => $request->pays_id,
                    'region' => $request->region,
                    'service_id' => $request->service_id,
                    'latitude' => $request->latitude,
                    'longitude' => $request->longitude,
                ]);
                return $this->ResponseSuccess("Boutique updated successfully.", $boutique->toArray());
            }
            return $this->ResponseERROR("Boutique not found. Invalid Id.");
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
            if (Boutique::find($id)) {
                Boutique::destroy($id);
                return $this->ResponseSuccess("Boutique deleted successfully.", null);
            }
            return $this->ResponseERROR("Boutique not found. Invalid Id.");
        } catch (\Throwable $th) {
            return  $this->ResponseServerError('Server error !', $th->getMessage());
        }
    }
}
