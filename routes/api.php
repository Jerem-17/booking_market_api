<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaysController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ZoneController;
use App\Http\Controllers\ObjetController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\BoutiqueController;
use App\Http\Controllers\CommandeController;
use App\Http\Controllers\ObjetTempController;
use App\Http\Controllers\ProductShopController;
use App\Http\Controllers\CategorieBoufController;
use App\Http\Controllers\CategorieVitrineController;
use App\Http\Controllers\RetourController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user()->id;
});

//ok
Route::post('phone/activation',[UserController::class,'activate'])->middleware('auth:sanctum');
//ok
Route::get('phone/resendotp',[UserController::class,'resendOTPCode'])->middleware('auth:sanctum');

//ok
Route::post('users/login', [UserController::class, 'login']);
//ok
Route::post('seller/login', [BoutiqueController::class, 'login']);
//ok
Route::post('users/register', [UserController::class, 'store']);
//ok
Route::post('users/passwordforgot', [UserController::class, 'passwordForgot']);
Route::get('users/logout', [UserController::class, 'logout'])->middleware('auth:sanctum');
//ok
Route::post('users/passwordchange', [UserController::class, 'passwordChange'])
    ->middleware('auth:sanctum');
//ok
Route::post('objets/addproducttocommand', [ObjetController::class, 'addProductToCommand'])->middleware('auth:sanctum');

Route::post('objets/acceptcommand', [ObjetController::class, 'acceptCommand'])
    ->middleware('auth:sanctum');

Route::post('objettemps/getproductincommand', [ObjetTempController::class, 'getProductInCommand'])
    ->middleware('auth:sanctum');

Route::post('objettemps/updateproductincommand', [ObjetTempController::class, 'updateProductInCommand'])
    ->middleware('auth:sanctum');

Route::post('users/changeprofil', [UserController::class, 'changeProfil'])->middleware('auth:sanctum');

Route::post('boutiques/change_image', [BoutiqueController::class, 'changeImage']);
//ok
Route::apiResource('/users', UserController::class);

Route::apiResource('/pays', PaysController::class);

Route::apiResource('/boutiques', BoutiqueController::class)
    ->middleware('auth:sanctum');

Route::post('/commandes/validatepaiement', [CommandeController::class, 'validatePaiement'])
    ->middleware('auth:sanctum');

Route::apiResource('/categoriebouf', CategorieBoufController::class);

Route::apiResource('/categorievitrine', CategorieVitrineController::class);
//ok
Route::apiResource('/commande', CommandeController::class)->middleware('auth:sanctum');
//ok
Route::get('/my/commande', [CommandeController::class,'myCommande'])->middleware('auth:sanctum');
//seller commanade
Route::get('/seller/commande', [CommandeController::class,'sellerCommande'])->middleware('auth:sanctum');

Route::apiResource('/objet', ObjetController::class);

Route::apiResource('/objettemp', ObjetTempController::class);

Route::apiResource('/productshop', ProductShopController::class);

Route::apiResource('/product', ProductController::class)->middleware('auth:sanctum');

Route::apiResource('/role', RoleController::class);

Route::apiResource('/service', ServiceController::class);

Route::get('/voir',function(Request $req){
   $currentUser = $req->user();
   return  $currentUser->isSeller() ?"yes" :"no";

})->middleware('auth:sanctum');

Route::get('/retour/all',[RetourController::class,'listeretour'])->middleware('auth:sanctum');
Route::post('/retour/create',[RetourController::class,'create'])->middleware('auth:sanctum');
