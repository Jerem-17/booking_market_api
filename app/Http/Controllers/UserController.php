<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\traits\TraitResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\PasswordRequest;
use App\Http\services\ServiceApi;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    use TraitResponse;

    /**
     * Change user profile image.
     *
     * @return \Illuminate\Http\Response
     */

    public function changeProfil(Request $request)
    {
        try {
            $user = $request->user();
            if ($request->file('profil')) {
                $validator = Validator::make($request->all(), [
                    'profil' => "image|mimes:jpg,png,jpeg,gif,svg|max:2048",
                ]);
                if ($validator->fails()) {
                    return $this->ResponseERROR("Image field validation fails.", $validator->errors());
                }
                $chema = Storage::putFile('images', $request->file('profil'));
                $path = $request->file('profil')
                    ->storeAs(time() . '.jpg', "images");
                var_dump($path);
                User::where("id", $user->id)->update([
                    'profil' => $chema
                ]);
                return $this->ResponseOk('User profil image updated successfully.', $user->toArray());
            }
            return $this->ResponseERROR("Field profil is required.", ['profil' => null]);
        } catch (\Throwable $th) {
            return  $this->ResponseServerError('Server error !', $th->getMessage());
        }
    }

    /**
     * Log the user.
     *
     * @return \Illuminate\Http\Response
     */

    public function login(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'telephone' => 'required',
                'password' => 'required',
            ]);
            if ($validator->fails()) {
                return $this->ResponseERROR("Fields validation fails.", $validator->errors());
            }

            $user = User::where('telephone', $request->telephone)->first();

            if (!$user->activated) {
                $message = 'Account not activated yet!';
                return $this->ResponseERROR($message);
            }

            if ($user && Hash::check($request->password, $user->password)) {
                $token = $user->createToken('my-app-token', ['asuser'])->plainTextToken;
                return $this->ResponseSuccess("User logged in successfully.", [
                    'token' => $token,
                    'token_type' => 'Bearer',
                    'user' => $user
                ]);
            }
            return $this->ResponseUnauthorize("Wrong credentials.");
        } catch (\Throwable $th) {
            return  $this->ResponseServerError('Server error !', $th->getMessage());
        }
    }


    public function passwordForgotBySms(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'telephone' => 'required|email|exists:users,telephone',
            ]);
            if ($validator->fails()) {
                return $this->ResponseERROR("Fields validation fails.", $validator->errors());
            }
            $user = User::where('telephone', $request->telephone)
                ->first();
            if ($user) {
                $mdp = Str::random(7);
                $user->update([
                    "password"=>Hash::make($mdp)
                ]);
                $send = new ServiceApi($user->telephone, "votre nouveau mot de passe  est " . $mdp . " n'oubliez pas de le changer");
                $send->sendSms();
                return $this->ResponseOk("Password reset send to your phone.", []);
            }
            return $this->ResponseUnauthorize("Wrong credentials.");
        } catch (\Throwable $th) {
            return  $this->ResponseServerError('Server error !', $th->getMessage());
        }
    }

    /**
     * Handle forgotten passwords of the user.
     *
     * @return \Illuminate\Http\Response
     */

    public function passwordForgot(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email|exists:users,email',
            ]);
            if ($validator->fails()) {
                return $this->ResponseERROR("Fields validation fails.", $validator->errors());
            }
            $user = User::where('email', $request->email)
                ->first();
            if ($user) {
                $token = Str::random(8);
                DB::table('password_resets')->insert([
                    'email' => $request->email,
                    'token' => $token,
                    'created_at' => Carbon::now()
                ]);
                Mail::send('email.forgetPassword', ['token' => $token], function ($message) use ($request) {
                    $message->to($request->email);
                    $message->subject('Reset Password');;
                });
                return $this->ResponseOk("Email reset link sent at your mail.", []);
            }
            return $this->ResponseUnauthorize("Wrong credentials.");
        } catch (\Throwable $th) {
            return  $this->ResponseServerError('Server error !', $th->getMessage());
        }
    }

    /**
     * Change user password.
     *
     * @return \Illuminate\Http\Response
     */
    public function passwordChange(Request $request, PasswordRequest $requestp)
    {
        $user = User::where('id', $request->user()->id)->first();
        if ($user) {
            if (Hash::check($requestp->oldpassword, $user->password)) {
                $response =  User::where('id', $request->user()->id)
                    ->update([
                        'password' => Hash::make($requestp->newpassword)
                    ]);
                if ($response) {
                    return $this->responseSuccess('Password changed.', []);
                } else {
                    return $this->responseError('Error.', []);
                }
            } else {
                return $this->responseError('Error.', 'Wrong credentials.');
            }
        } else {
            return $this->responseError('Error. !', []);
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
            $users = User::all()->toArray();
            return $this->ResponseOk('Data retreived.', $users);
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
    //ok
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'nom' => "required",
                'prenom' => "required",
                'telephone' => "required",
                'email' => "required|unique:users,email",
                'password' => "required",
            ]);
            if ($validator->fails()) {
                return $this->ResponseERROR("Fields validation fails.", $validator->errors());
            }
            $role = Role::where('name', 'user')->first();
            $user = User::create([
                'nom' => $request->nom,
                'prenom' => $request->prenom,
                'telephone' => $request->telephone,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);
            if ($user) {
                $user->roles()->attach($role);
                $token = $user->createToken('my-app-token', ['asuser'])->plainTextToken;
                $otp = rand(0000, 9999);
                $user->activation()->create([
                    "code" => Hash::make($otp),
                    "expires_at" => Carbon::now()->addMinutes(5)
                ]);
                $send = new ServiceApi($user->telephone, "<#> odon votre code de verifcation est " . $otp . " \nAIoVH2Rz3hM");
                $send->sendSms();
                return $this->ResponseSuccess("User created successfully.", [
                    'token' => $token,
                    'token_type' => 'Bearer',
                    'user' => $user->toArray()
                ]);
            } else {
                return $this->ResponseERROR('User creation failed.');
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
            $user = User::find($id);
            if ($user) {
                return $this->ResponseOk("User retreived successfully.", $user->toArray());
            }
            return $this->ResponseERROR("User not found. Invalid id.");
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
            $user = User::find($id);
            if ($user) {
                $validator = Validator::make($request->all(), [
                    'nom' => "required",
                    'prenom' => "required",
                    'telephone' => "required",
                    'email' => "required|unique:users,email,except," . $id,
                    'password' => "required|confirmed",
                ]);
                if ($validator->fails()) {
                    return $this->ResponseERROR("Fields validation fails.", $validator->errors());
                }
                $path = $user->profil;
                if ($request->file('profil')) {
                    $validator = Validator::make($request->all(), [
                        'profil' => "image|mimes:jpg,png,jpeg,gif,svg|max:2048",
                    ]);
                    if ($validator->fails()) {
                        return $this->ResponseERROR("Image field validation fails.", $validator->errors());
                    }
                    // $path = $request->file('profil')
                    //     ->storeAs(self::USERS_IMG_PATH, Carbon::now() . '.jpg', self::IMAGES_DISK);
                }
                $user->update([
                    'profil' => $path,
                    'nom' => $request->nom,
                    'prenom' => $request->prenom,
                    'telephone' => $request->telephone,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                ]);
                return $this->ResponseSuccess("User updated successfully.", $user->toArray());
            }
            return $this->ResponseERROR("User not found. Invalid Id.");
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
            if (User::find($id)) {
                User::destroy($id);
                return $this->ResponseSuccess("User deleted successfully.", null);
            }
            return $this->ResponseERROR("User not found. Invalid Id.");
        } catch (\Throwable $th) {
            return  $this->ResponseServerError('Server error !', $th->getMessage());
        }
    }
    public function activate(Request $request)
    {
        $request->validate([
            'code' => 'required|digits:4',
        ]);

        $user = $request->user();
        if ($user->activated) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        $activation = $user->activation;
        if (Carbon::now()->gte($activation->expires_at)) {
            return $this->ResponseERROR("code expired");
        }
        if (!Hash::check($request->code, $activation->code)) {
            return $this->ResponseERROR("code invalid");
        }

        try {
            $user->update(['activated' => 1]);
        } catch (\Throwable $th) {
            return $this->ResponseServerError("server error", $th->getMessage());
        }
        return  $this->ResponseSuccess('User activated successfully!', []);
    }
    public function resendOTPCode(Request $request)
    {
        $user = $request->user();
        $otp = rand(0000, 9999);

        if ($user->activated) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        try {
            $user->activation()->update([
                "code" => Hash::make($otp),
                "expires_at" => Carbon::now()->addMinutes(5)
            ]);
            $send = new ServiceApi($user->telephone, "<#> odon votre code de verifcation est " . $otp . "\n48Sdnh4o/3q");
            $send->sendSms();
        } catch (\Throwable $th) {
            return $this->ResponseServerError("server error", $th->getMessage());
        }

        return  $this->ResponseSuccess('code sent successfully !', []);
    }

    public function oublier()
    {
        // Récupérer l'utilisateur à partir de son email
        $user = User::where('email', request('email'))->first();

        // Générer un mot de passe aléatoire
        $password = Str::random(8);

        // Mettre à jour le mot de passe de l'utilisateur
        $user->update([
            'password' => Hash::make($password)
        ]);

        // Envoyer un email avec le mot de passe temporaire
        Mail::send('emails.forgot-password', [
            'password' => $password
        ], function ($m) use ($user) {
            $m->from('example@example.com', 'Example');
            $m->to($user->email, $user->name)->subject('Votre mot de passe temporaire');
        });

        return response()->json([
            'message' => 'Un email avec votre mot de passe temporaire vous a été envoyé'
        ]);
    }

    function logout(Request $request){
       $res = $request->user()->currentAccessToken()->delete();
       if ($res) {
       $this->ResponseSuccess('Logged out successfuly',[]);
       }
    }
}
