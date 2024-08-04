<?php

namespace App\Http\Controllers;

use App\Models\LigneCommande;
use Illuminate\Http\Request;
use App\Mail\FactureCommande;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class LigneCommandeController extends Controller
{
    /***
     * LISTE DES COMMANDES
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $today = Carbon::today();

        $commandes = DB::table('ligne_commandes')
            ->join('burgers', 'ligne_commandes.id_burger', '=', 'burgers.id')
            ->where('ligne_commandes.archiver', 0)
            ->whereDate('ligne_commandes.created_at', $today)
            ->select('ligne_commandes.*', 'burgers.nom as burger_nom', 'burgers.prix as burger_prix', 'burgers.image as burger_image')
            ->get();

        return response()->json($commandes, 200);
    }

    /***
     * PASSER COMMANDE
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request,$idBurger){
        $request->validate([
            'nom' => 'required|max:50',
            'prenom' => 'required|max:50',
            'telephone' => 'required',
            'email' => 'required|max:100',
        ]);

        $commande = LigneCommande::create([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'telephone' => $request->telephone,
            'email' => $request->email,
            'etat' => "WAIT", // commande en attente
            'archiver' => 0, // pas en archiver
            'id_burger' => $idBurger,
        ]);

        return response()->json($commande,201);
    }

    /***
     * @param $id
     * @return void
     */
    public function show($id){

    }

    /***
     * MODIFIER COMMANDE
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id){
        try {
            LigneCommande::findOrFail($id);
            $request->validate([
                'nom' => 'required|max:50',
                'prenom' => 'required|max:50',
                'telephone' => 'required',
                'email' => 'required|max:100',
            ]);

            $commande = LigneCommande::create([
                'nom' => $request->nom,
                'prenom' => $request->prenom,
                'telephone' => $request->telephone,
                'email' => $request->email,
                'etat' => $request->etat,
                'archiver' => $request->archiver,
                'id_burger' => $request->id_burger,
            ]);

            return response()->json($commande,201);

        }catch (Exception $ex){
            return response()->json(['error' => 'Burger not found'],404);

        }
    }

    /***
     * ARCHIVAGE COMMANDE
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function archiver($id){
        // Trouver le burger par son id
        $ligneCommande = LigneCommande::find($id);

        // Vérifier si le burger existe
        if (!$ligneCommande) {
            return response()->json(['message' => 'Commande not found'], 404);
        }

        // Mettre à jour le champ 'archiver' à 1
        $ligneCommande->archiver = 1;
        $ligneCommande->save();

        // Retourner une réponse JSON avec le burger mis à jour
        return response()->json($ligneCommande, 200);
    }

    /***
     * VALIDATION COMMANDE
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function valider($id)
    {
        $ligneCommande = DB::table('ligne_commandes')
            ->join('burgers', 'ligne_commandes.id_burger', '=', 'burgers.id')
            ->where('ligne_commandes.id', $id)
            ->where('ligne_commandes.etat', 'WAIT')
            ->select('ligne_commandes.*', 'burgers.nom as burger_nom', 'burgers.prix as burger_prix', 'burgers.description as burger_description')
            ->first();

        if (!$ligneCommande) {
            return response()->json(['message' => 'Commande not found'], 404);
        }

        DB::table('ligne_commandes')
            ->where('id', $id)
            ->update(['etat' => 'FINISH']);

        // Envoyer l'email
        Mail::to($ligneCommande->email)->send(new FactureCommande($ligneCommande));

        return response()->json($ligneCommande, 200);
    }

    /***
     * ANNULER LA COMMANDE
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function annuler($id)
    {
        // Trouver la ligne de commande par son id avec l'état WAIT
        $ligneCommande = DB::table('ligne_commandes')
            ->where('id', $id)
            ->where('etat', 'WAIT')
            ->first();

        // Vérifier si la ligne de commande existe et est en état WAIT
        if (!$ligneCommande) {
            return response()->json(['message' => 'Commande not found or not in WAIT state'], 404);
        }

        // Mettre à jour le champ 'etat' à 'FAILED'
        DB::table('ligne_commandes')
            ->where('id', $id)
            ->update(['etat' => 'FAILED']);

        // Retourner une réponse JSON avec la ligne de commande mise à jour
        return response()->json(['message' => 'Commande annulée avec succès', 'ligneCommande' => $ligneCommande], 200);
    }


    /***
     * SUPPRIMER DEFINITIVEMENT UNE COMMANDE
     * @param $id
     * @return void
     */
    public function destroy($id){
        try {
            $commande =  LigneCommande::findOrFail($id);
            $commande->delete();
            return response()->json(null,204);
        }catch (Exception $e){
            return response()->json(['error' => 'Commande not found'],404);
        }
    }

    /***
     * @return mixed
     * COMMANDES EN COURS
     */
    public function commandesEnCours()
    {
        $aujourdHui = Carbon::today();

        $commandesEnCours = DB::table('ligne_commandes')
            ->join('burgers', 'ligne_commandes.id_burger', '=', 'burgers.id')
            ->where('ligne_commandes.etat', 'WAIT')
            ->whereDate('ligne_commandes.updated_at', $aujourdHui)
            ->select(
                'ligne_commandes.*',
                'burgers.nom as burger_nom',
                'burgers.prix as burger_prix',
                'burgers.image as burger_image'
            )
            ->get();

        return response()->json($commandesEnCours, 200);
    }

    /***
     * @return mixed
     * COMMANDES ANNULEES
     */
    public function commandesValidees(){
        $aujourdHui = Carbon::today();

        $commandesEnCours = DB::table('ligne_commandes')
            ->join('burgers', 'ligne_commandes.id_burger', '=', 'burgers.id')
            ->where('ligne_commandes.etat', 'FINISH')
            ->whereDate('ligne_commandes.updated_at', $aujourdHui)
            ->select(
                'ligne_commandes.*',
                'burgers.nom as burger_nom',
                'burgers.prix as burger_prix',
                'burgers.image as burger_image'
            )
            ->get();

        return response()->json($commandesEnCours, 200);
    }

    public function commandesAnnulees(){
        $aujourdHui = Carbon::today();

        $commandesEnCours = DB::table('ligne_commandes')
            ->join('burgers', 'ligne_commandes.id_burger', '=', 'burgers.id')
            ->where('ligne_commandes.etat', 'FAILED')
            ->whereDate('ligne_commandes.updated_at', $aujourdHui)
            ->select(
                'ligne_commandes.*',
                'burgers.nom as burger_nom',
                'burgers.prix as burger_prix',
                'burgers.image as burger_image'
            )
            ->get();

        return response()->json($commandesEnCours, 200);
    }

}
