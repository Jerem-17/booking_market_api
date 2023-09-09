<?php

namespace App\Http\Controllers;

use App\Models\Retour;
use App\Models\ObjetTemp;
use Illuminate\Http\Request;
use App\Http\traits\TraitResponse;

class RetourController extends Controller
{
    use TraitResponse;
    function listeretour(Request $request)
    {
        $retour =  Retour::with('objettemps','commande')
        ->whereHas('commande',function($query) use ($request){
                $query->where('user_id',$request->user()->id);
        })
        ->get();
        try {
            return $this->ResponseSuccess("liste or retourn",$retour);
        } catch (\Throwable $th) {
            return $this->ResponseServerError('server error', $th->getMessage());
        }

    }

    function create(Request $request)
    {
        try {
            $retour = Retour::create([
                'user_id' => $request->user()->id,
                'commande_id' => $request->commande_id,
            ]);

            $items = $request->get('objettemps');
            $total = 0;
            $objets = [];
            foreach ($items as $item) {
                $objets[] = ObjetTemp::create([
                    'retour_id' => $retour->id,
                    'objet_id' => $item['objet_id'],
                    'prix' => $item['prix'],
                    'quantite' => $item['quantite'],
                    'total' => 0
                ])->toArray();
                $total += doubleval($item['prix']) * doubleval($item['quantite']);
            }
            $retour->update([
                'prix_total' => $total
            ]);
            return $this->ResponseSuccess("Objects inserted successfully and SMS sent successfully.", [
                'amoumt' => $total,
            ]);
        } catch (\Throwable $th) {
            return $this->ResponseServerError('server error', $th->getMessage());
        }
    }
}
