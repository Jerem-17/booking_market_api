<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Objet;
use App\Models\Address;
use App\Models\Boutique;
use App\Models\Commande;
use App\Models\ObjetTemp;
use Illuminate\Http\Request;
use App\Http\services\ServiceApi;
use App\Http\traits\TraitResponse;
use App\Models\Service;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ObjetController extends Controller
{
    use TraitResponse;


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function acceptCommand(Request $request)
    // {
    //     try {
    //         $validator = Validator::make($request->all(), [
    //             'commande_id' => 'required|exists:commandes,id',
    //             'boutique_id' => 'required|exists:boutiques,id'
    //         ]);
    //         if ($validator->fails()) {
    //             return $this->ResponseERROR("Fields validation fails.", $validator->errors());
    //         }
    //         $commande = Commande::find($request->commande_id);
    //         if ($commande->user_id == $request->user()->id) {
    //             $objetTemps = ObjetTemp::all()
    //                 ->where('commande_id', $commande->id)
    //                 ->where('boutique_id', $request->boutique_id);
    //             $objets = Objet::all()
    //                 ->where('commande_id', $commande->id);
    //             $total = 0;
    //             foreach ($objetTemps as $objetTemp) {
    //                 $objets->firstWhere('article', $objetTemp->article)->update([
    //                     'quantite' => $objetTemp->quantite,
    //                     'article' => $objetTemp->produit,
    //                     'prix_unitaire' => $objetTemp->prix,
    //                 ])->toArray();
    //                 $total += doubleval($objetTemp->prix) * doubleval($objetTemp->quantite);
    //             }
    //             $commande->update([
    //                 'prix_total' => $total,
    //                 'boutique_id' => $request->boutique_id,
    //             ]);
    //             return $this->ResponseSuccess("Command accepted successfully.", [
    //                 'commande' => Commande::find($request->commande_id)->reference,
    //                 'amoumt' => $total,
    //             ]);
    //         }
    //         return $this->ResponseUnauthorize("This command is private and not yours.");
    //     } catch (\Throwable $th) {
    //         return  $this->ResponseServerError('Server error !', $th->getMessage());
    //     }
    // }

    public function sendObjectToVendors(Commande $command)
    {
        $message = "Un client cherche : ";
        foreach (Objet::where(['commande_id' => $command->id])->get() as $objet) {
            $message  .= "$objet->quantite $objet->article(s) à $objet->prix_unitaire, ";
        }

        $message = rtrim($message, ",") . ". Total : $command->prix_total.";
        $address = Address::find($command->address_id);
        $latitude = $address->latitude;
        $longitude = $address->longitude;
        $radius = 20;

        $boutiques = Boutique::whereRaw('(ACOS(SIN(' . $latitude . ' * PI() / 180) * SIN(latitude * PI() / 180)+COS(' . $latitude . ' * PI() / 180) * COS(latitude * PI() / 180)*COS((' . $longitude . ' - longitude) * PI() / 180)) * 6371) <= ' . $radius . '')->get();

        if($boutiques->count()==0){
            return $this->ResponseOk("There is not shop around this area",[] );

        }
        foreach ($boutiques as $btk) {
            (new ServiceApi(
                $btk->contact,
                $message
            ))->sendMessage();
        }
        $command->boutiques()->attach($boutiques);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function addProductToCommand(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'adresse.nom' => 'required|string|max:255',
                'adresse.latitude' => 'required|numeric',
                'adresse.longitude' => 'required|numeric',
                'items' => 'required|array',
                'items.*.produit' => 'required|string|max:255',
                'items.*.prix' => 'required|numeric',
                'items.*.quantite' => 'required|integer',
                // 'adresse_id' => 'required_without:adresse|exists:addresses,id',
                // 'adresse' => 'required_without:adresse_id|array',
                // 'adresse.nom' => 'required_without:adresse_id|numeric',
                // 'adresse.latitude' => 'required_without:adresse_id|numeric',
                // 'adresse.longitude' => 'required_without:adresse_id|numeric',
                // 'items' => 'required|array',
                // 'items.*.produit' => 'required|string',
                // 'items.*.prix' => 'required|numeric',
                // 'items.*.quantite' => 'required|integer|min:1',
            ]);
            if ($validator->fails()) {
                return $this->ResponseERROR("Fields validation fails.", $validator->errors());
            }
            $address = $request->address_id ?? Address::create([
                'nom' => $request->get('adresse')['nom'],
                'user_id' => $request->user()->id,
                'longitude' => $request->get('adresse')['longitude'],
                'latitude' => $request->get('adresse')['latitude'],
            ]);

            $command = Commande::create([
                'reference' => 'Ref' . time(),
                'address_id' => $address->id,
                'user_id' => $request->user()->id,
                'prix_total' => 0,
                'boutique_id' => null,
                'statut_paiement' => false,
            ]);
            $items = $request->get('items');
            $total = 0;
            $objets = [];
            foreach ($items as $item) {
                $objets[] = Objet::create([
                    'commande_id' => $command->id,
                    'quantite' => $item['quantite'],
                    'article' => $item['produit'],
                    'prix_unitaire' => $item['prix'],
                    'quantite_final'=>0,
                    'pu_final'=>0
                ])->toArray();
                $total += doubleval($item['prix']) * doubleval($item['quantite']);
            }
            $command->update([
                'prix_total' => $total
            ]);
            $this->sendObjectToVendors($command);
            return $this->ResponseSuccess("Objects inserted successfully and SMS sent successfully.", [
                'commande' => $command["reference"],
                'amoumt' => $total,
            ]);
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
            $objets = Objet::all()->toArray();
            return $this->ResponseOk('Data retreived.', $objets);
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
                'commande_id' => "required|exists:commandes,id",
                'quantite' => "required|numeric",
                'article' => "required|max:50",
                'prix_unitaire' => "required|numeric",
                'correctif' => "required|numeric",
            ]);
            if ($validator->fails()) {
                return $this->ResponseERROR("Fields validation fails.", $validator->errors());
            }
            $objet = Objet::create([
                'commande_id' => $request->commande_id,
                'quantite' => $request->quantite,
                'article' => $request->article,
                'prix_unitaire' => $request->prix_unitaire,
                'correctif' => $request->correctif,
            ]);
            if ($objet) {
                return $this->ResponseSuccess("Objet created successfully.", $objet->toArray());
            } else {
                return $this->ResponseERROR('Objet creation failed.');
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
            $objet = Objet::find($id);
            if ($objet) {
                return $this->ResponseOk("Objet retreived successfully.", $objet->toArray());
            }
            return $this->ResponseERROR("Objet not found. Invalid id.");
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
            $objet = Objet::find($id);
            if ($objet) {
                $validator = Validator::make($request->all(), [
                    'commande_id' => "required|exists:commandes,id",
                    'quantite' => "required|numeric",
                    'article' => "required|max:50",
                    'prix_unitaire' => "required|numeric",
                    'correctif' => "required|numeric",
                ]);
                if ($validator->fails()) {
                    return $this->ResponseERROR("Fields validation fails.", $validator->errors());
                }
                $objet->update([
                    'commande_id' => $request->commande_id,
                    'quantite' => $request->quantite,
                    'article' => $request->article,
                    'prix_unitaire' => $request->prix_unitaire,
                    'correctif' => $request->correctif,
                ]);
                return $this->ResponseSuccess("Objet updated successfully.", $objet->toArray());
            }
            return $this->ResponseERROR("Objet not found. Invalid Id.");
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
            if (Objet::find($id)) {
                Objet::destroy($id);
                return $this->ResponseSuccess("Objet deleted successfully.", null);
            }
            return $this->ResponseERROR("Objet not found. Invalid Id.");
        } catch (\Throwable $th) {
            return  $this->ResponseServerError('Server error !', $th->getMessage());
        }
    }
}
