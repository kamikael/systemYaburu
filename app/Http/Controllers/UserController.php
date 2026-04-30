<?php


namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller 
{

  public function show($id){

  $user = User::findOrFail($id);

  if(auth()->user()->id !== (int)$id){
    return respoense()->json(["message"=>"non autorisé"], 403);
  }

  return response()->json($user);

  }

  public function update(Request $request){
    $user = User::findOrFail($id);

    if(auth()->user()->id !== (int)$id){
        return response()->json(["message"=>"non autorisé"], 403);
    }
    $validated = $request->validate([
        "name"=>"sometimes|string|max:255",
        "email"=>"sometimes|email|unique:users.email" . $id,
    ]);

    $user->update($request);

    return  response()->json([ "message"=> "profil mis à jour ", "user"=> $user]);

  }

  public function changePassword(Request $request, $id){

    $user  = User::findOrFail($id);

    if(auth()->user()->id !== (int)$id){
       return response()->json([ "message"=> 'non autorisé'], 401);
    }
     $validated = $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if (!Hash::check($validated['current_password'], $user->password)) {
            return response()->json(['message' => 'Mot de passe actuel incorrect'], 400);
        }

        $user->update(['password' => Hash::make($validated['password'])]);

        return response()->json(['message' => 'Mot de passe changé avec succès']);
    
  }

      // Supprimer un utilisateur
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if (auth()->user()->id !== (int)$id) {
            return response()->json(['message' => 'Non autorisé'], 403);
        }

        $user->delete();

        return response()->json(['message' => 'Utilisateur supprimé']);
    }

    // Liste des utilisateurs (admin seulement)
    public function index()
    {
        // À ajouter : vérification rôle admin
        $users = User::all();
        return response()->json($users);
    }

}
