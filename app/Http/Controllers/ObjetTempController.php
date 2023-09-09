<?php

namespace App\Http\Controllers;

use App\Models\Commande;
use App\Models\ObjetTemp;
use Illuminate\Http\Request;
use App\Http\traits\TraitResponse;
use App\Models\Boutique;
use Illuminate\Support\Facades\Validator;

class ObjetTempController extends Controller
{
    use TraitResponse;

    /**
     * Retreive modified products in a command by the client
     *
     * @return \Illuminate\Http\Response
     */
    public function getProductInCommand(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'commande_id' => "required|exists:commandes,id",
            ]);
            if ($validator->fails()) {
                return $this->ResponseERROR("Fields validation fails.", $validator->errors());
            }
            if (Commande::find($request->commande_id)->user_id == $request->user()->id) {
                $objets = ObjetTemp::all()->where('commande_id', $request->commande_id)->toArray();
                return $this->ResponseOk('Data retreived.', $objets);
            }
            return $this->ResponseUnauthorize("This command is private and not yours.");
        } catch (\Throwable $th) {
            return  $this->ResponseServerError('Server error !', $th->getMessage());
        }
    }

    /**
     * Update products in a command
     *
     * @return \Illuminate\Http\Response
     */

    public function updateProductInCommand(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'commande_id' => 'required|exists:commandes,id',
                'items' => 'required|array',
                'items.*.prix' => 'required|numeric',
                'items.*.quantite' => 'required|integer|min:0',
            ]);
            if ($validator->fails()) {
                return $this->ResponseERROR("Fields validation fails.", $validator->errors());
            }
            $items = $request->get('items');
            $total = 0;
            $objets = [];
            foreach ($items as $item) {
                $objets[] = ObjetTemp::create([
                    'commande_id' => $request->commande_id,
                    'boutique_id' => Boutique::all()
                        ->where('user_id', $request->user()->id)
                        ->first()->id,
                    'quantite' => $item['quantite'],
                    'article' => $item['produit'],
                    'prix_unitaire' => $item['prix'],
                    'correctif' => null,
                ])->toArray();
                $total += doubleval($item['prix']) * doubleval($item['quantite']);
            }
            return $this->ResponseSuccess("Temp Objects updated successfully.", [
                'commande' => Commande::find($request->commande_id)->reference,
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
            $objets = ObjetTemp::all()->toArray();
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
            $objet = ObjetTemp::create([
                'commande_id' => $request->commande_id,
                'quantite' => $request->quantite,
                'article' => $request->article,
                'prix_unitaire' => $request->prix_unitaire,
                'correctif' => $request->correctif,
            ]);
            if ($objet) {
                return $this->ResponseSuccess("ObjetTemp created successfully.", $objet->toArray());
            } else {
                return $this->ResponseERROR('ObjetTemp creation failed.');
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
            $objet = ObjetTemp::find($id);
            if ($objet) {
                return $this->ResponseOk("ObjetTemp retreived successfully.", $objet->toArray());
            }
            return $this->ResponseERROR("ObjetTemp not found. Invalid id.");
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
            $objet = ObjetTemp::find($id);
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
                return $this->ResponseSuccess("ObjetTemp updated successfully.", $objet->toArray());
            }
            return $this->ResponseERROR("ObjetTemp not found. Invalid Id.");
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
            if (ObjetTemp::find($id)) {
                ObjetTemp::destroy($id);
                return $this->ResponseSuccess("ObjetTemp deleted successfully.", null);
            }
            return $this->ResponseERROR("ObjetTemp not found. Invalid Id.");
        } catch (\Throwable $th) {
            return  $this->ResponseServerError('Server error !', $th->getMessage());
        }
    }
}
