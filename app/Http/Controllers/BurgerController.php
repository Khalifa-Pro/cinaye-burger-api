<?php

namespace App\Http\Controllers;

use App\Models\Burger;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class BurgerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $burgers = Burger::where('archiver', 0)->get();
        return response()->json($burgers, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validation des entrées
        $request->validate([
            'nom' => 'required|max:20',
            'prix' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Validation pour le fichier image
            'description' => 'nullable|string',
        ]);

        // Traitement de l'image
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $nomImage = $image->getClientOriginalName(); // Utilisez le nom original de l'image
            $chemin = public_path('/assets/images/burgers');

            // Vérifiez si le répertoire existe, sinon créez-le
            if (!File::exists($chemin)) {
                File::makeDirectory($chemin, 0755, true);
            }

            // Déplacez l'image dans le répertoire
            $image->move($chemin, $nomImage);
        }

        // Créez le burger dans la base de données
        $burger = Burger::create([
            'nom' => $request->nom,
            'prix' => $request->prix,
            'image' => isset($nomImage) ? $nomImage : null,
            'description' => $request->description,
            'archiver' => 0 // pas encore archivé
        ]);

        // Retournez une réponse JSON
        return response()->json($burger, 201);
    }



    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $buerger = Burger::find($id);
        return response()->json($buerger, 200);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $burger = Burger::findOrFail($id);
            $request->validate([
                'nom' => 'required|max:20',
                'prix' => 'required',
                'image' => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Validation pour le fichier image
            ]);

            // Traitement de l'image
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $nomImage = $image->getClientOriginalName(); // Utilisez le nom original de l'image
                $chemin = public_path('assets/images/burgers');

                // Déplacez l'image dans le répertoire
                $image->move($chemin, $nomImage);
                $burger->image = $nomImage;
            }

            $burger->nom = $request->nom;
            $burger->prix = $request->prix;
            $burger->description = $request->description;
            $burger->archiver = $request->archiver;

            $burger->save();

            return response()->json($burger, 200, ['message' => 'Modification avec succès!']);
        } catch (Exception $ex) {
            return response()->json(['error' => 'Burger not found'], 404);
        }
    }


    /***
     * @param Request $request
     * @param string $id
     * @return void
     */
    public function archiver($id)
    {
        // Trouver le burger par son id
        $burger = Burger::find($id);

        // Vérifier si le burger existe
        if (!$burger) {
            return response()->json(['message' => 'Burger not found'], 404);
        }

        // Mettre à jour le champ 'archiver' à 1
        $burger->archiver = 1;
        $burger->save();

        // Retourner une réponse JSON avec le burger mis à jour
        return response()->json($burger, 200);
    }

    public function desarchiver($id)
    {
        // Trouver le burger par son id
        $burger = Burger::find($id);

        // Vérifier si le burger existe
        if (!$burger) {
            return response()->json(['message' => 'Burger not found'], 404);
        }

        // Mettre à jour le champ 'archiver' à 1
        $burger->archiver = 0;
        $burger->save();

        // Retourner une réponse JSON avec le burger mis à jour
        return response()->json($burger, 200);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $burger =  Burger::findOrFail($id);
            $burger->delete();
            return response()->json(null,204);
        }catch (Exception $e){
            return response()->json(['error' => 'Burger not found'],404);
        }

    }

    /***
     * @return \Illuminate\Http\JsonResponse
     * RECETTES JOURNALIERE
     */
    public function recettesJournalieres(){
        $aujourdHui = \Illuminate\Support\Carbon::today();
        $recettesJournaliere = Burger::whereDate('updated_at', $aujourdHui)->get();
        return response()->json($recettesJournaliere, 200);
    }
}
