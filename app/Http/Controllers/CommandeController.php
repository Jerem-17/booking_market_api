<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Commande;
use Illuminate\Http\Request;
use App\Http\traits\TraitResponse;
use App\Models\Retour;
use Illuminate\Support\Facades\Validator;

class CommandeController extends Controller
{
    use TraitResponse;
    function sellerCommande(Request $request)
    {
        $result = Commande::with('objets', 'user', 'address')
            ->whereDoesntHave('retours')
            ->whereHas('boutiques', function ($q) use ($request) {
                $q->where('user_id', $request->user()->id);
            })->get();
        return $this->ResponseSuccess('list of commande', $result);
    }

    function myCommande(Request $request)
    {
        $user = $request->user();
        return Commande::with('objets')
            ->where([
                "user_id" => $user->id
            ])
            ->get();
    }
    public function validatePaiement(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'commande_id' => 'required|exists:commandes,id'
            ]);
            if ($validator->fails()) {
                return $this->ResponseERROR("Fields validation fails.", $validator->errors());
            }
            $commande = Commande::find($request->commande_id);
            if ($commande->user_id == $request->user()->id) {
                $commande->update([
                    'statut_paiement' => true,
                ]);
                return $this->ResponseOk("Commande paied successfully.", $commande->toArray());
            }
            return $this->ResponseUnauthorize("This command is private and not yours.");
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
            $commandes = Commande::all()->toArray();
            return $this->ResponseOk('Data retreived.', $commandes);
        } catch (\Throwable $th) {
            return  $this->ResponseServerError('Server error !', $th->getMessage());
        }
    }


    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'adresse_id' => 'required_without:adresse|exists:addresses,id',
                'adresse' => 'required_without:adresse_id|array',
                'adresse.*.latitude' => 'required_without:adresse_id|numeric',
                'adresse.*.longitude' => 'required_without:adresse_id|numeric',
                'reference' => "required|max:255|unique:commandes,reference",
                'user_id' => "required|exists:users,id",
                'prix_total' => "required|max:50",
                'boutique_id' => "required|numeric",
                'statut_paiement' => "required",
            ]);
            if ($validator->fails()) {
                return $this->ResponseERROR("Fields validation fails.", $validator->errors());
            }
            $address_id = $request->address_id ?? Address::create([
                'nom' => null,
                'longitude' => $request->get('adresse')['longitude'],
                'latitude' => $request->get('adresse')['latitude'],
            ])->id;
            $commande = Commande::create([
                'reference' => 'Ref' . time(),
                'address_id' => $address_id,
                'user_id' => $request->user()->id,
                'prix_total' => 0,
                'boutique_id' => null,
                'statut_paiement' => false,
            ]);
            if ($commande) {
                return $this->ResponseSuccess("Commande created successfully.", $commande->toArray());
            } else {
                return $this->ResponseERROR('Commande creation failed.');
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
            $commande = Commande::find($id);
            if ($commande) {
                return $this->ResponseOk("Commande retreived successfully.", $commande->toArray());
            }
            return $this->ResponseERROR("Commande not found. Invalid id.");
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
            $commande = Commande::find($id);
            if ($commande) {
                $validator = Validator::make($request->all(), [
                    'adresse_id' => 'required_without:adresse|exists:addresses,id',
                    'adresse' => 'required_without:adresse_id|array',
                    'adresse.*.latitude' => 'required_without:adresse_id|numeric',
                    'adresse.*.longitude' => 'required_without:adresse_id|numeric',
                    'reference' => "required|max:255|unique:commandes,reference,except," . $id,
                    'user_id' => "required|exists:users,id",
                    'prix_total' => "required|max:50",
                    'boutique_id' => "required|numeric",
                    'statut_paiement' => "required",
                ]);
                if ($validator->fails()) {
                    return $this->ResponseERROR("Fields validation fails.", $validator->errors());
                }
                $address_id = $request->address_id ?? Address::create([
                    'nom' => null,
                    'longitude' => $request->get('adresse')['longitude'],
                    'latitude' => $request->get('adresse')['latitude'],
                ])->id;
                $commande->update([
                    'reference' => $request->reference,
                    'address_id' => $request->address_id,
                    'user_id' => $request->user_id,
                    'prix_total' => $request->prix_total,
                    'boutique_id' => $request->boutique_id,
                    'statut_paiement' => $request->statut_paiement,
                ]);
                return $this->ResponseSuccess("Commande updated successfully.", $commande->toArray());
            }
            return $this->ResponseERROR("Commande not found. Invalid Id.");
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
            if (Commande::find($id)) {
                Commande::destroy($id);
                return $this->ResponseSuccess("Commande deleted successfully.", null);
            }
            return $this->ResponseERROR("Commande not found. Invalid Id.");
        } catch (\Throwable $th) {
            return  $this->ResponseServerError('Server error !', $th->getMessage());
        }
    }
}
