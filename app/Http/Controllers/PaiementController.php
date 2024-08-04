<?php

namespace App\Http\Controllers;

use App\Models\Paiement;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class PaiementController extends Controller
{
    /***
     * @return false|string
     * LISTE DES PAIEMENTS
     */
    public function paiements(){
        $paiements = Paiement::all();
        return json_encode($paiements);
    }
    /***
     * PAYER UNE COMMANDE
     * @param Request $request
     * @param $idLigneCommande
     * @return \Illuminate\Http\JsonResponse
     */
    public function payer(Request $request,$idLigneCommande){
        $request->validate([
            'montant' => 'required',
        ]);

        $paiement = Paiement::create([
            'montant' => $request->montant,
            'id_ligne_commande' => $idLigneCommande,
        ]);

        return response()->json($paiement,201, (array)'Paiement effectué avec succes');
    }

    public function bilan(){
        $today = Carbon::today();

        $total = DB::table('paiements')
            ->whereDate('created_at', $today)
            ->sum('montant');

        return response()->json(['montant_total' => $total], 200);
    }

    public function delete()
    {
        try {
            $paie =  Paiement::findOrFail($id);
            $paie->delete();
            return response()->json(null,204);
        }catch (Exception $e){
            return response()->json(['error' => 'Paiement non troubé'],404);
        }
    }
}
