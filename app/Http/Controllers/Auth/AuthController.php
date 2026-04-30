<?php



namespace  App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller {

public function register(Request $request)
{
    $validated = $request->validate(
        [
            'email' => 'required|email|unique:users,email',
            'name' => 'required|string|max:255',
            'password' => 'required|string|min:8|confirmed',
        ],
        [
            'email.unique' => 'Cet email est déjà utilisé. Veuillez en choisir un autre.',
            'email.required' => 'L\'email est obligatoire.',
            'email.email' => 'Veuillez entrer une adresse email valide.',
            'name.required' => 'Le nom est obligatoire.',
            'password.required' => 'Le mot de passe est obligatoire.',
            'password.min' => 'Le mot de passe doit contenir au moins 8 caractères.',
            'password.confirmed' => 'Les mots de passe ne correspondent pas.',
        ]
    );

    $user = User::create([
        'email' => $validated['email'],
        'name' => $validated['name'],
        'password' => Hash::make($validated['password']),
    ]);

    $token = $user->createToken('auth-token')->plainTextToken;

    return response()->json([
        'message' => 'Utilisateur créé avec succès',
        'user' => $user,
        'token' => $token,
    ], 201);
}

  // Profil de l'utilisateur connecté
    public function me(Request $request)
    {
        return response()->json($request->user());
    }

public function login(Request $request){
     $validated = $request->validate([
        "email"=>'required|email',
        "password"=>"required|string"
     ]);

     $user = User::where("email", $validated["email"])->first();

     if(!$user || !Hash::check($validated["password"], $user->password)){
         throw ValidationException::withMessages([
                'email' => ['Les identifiants sont incorrects.'],
            ]);
     }

     $token = $user->createToken("auth-token")->plainTextToken;

     return response()->json([
        "message"=>"connexion succes",
        "user"=>$user,
        "token"=>$token
     ], 200);

}

public function logout(Request $request)
{
    $request->user()->currentAccessToken()->delete();
    return response()->json(["message"=>"deconnexion okay"]);
}

public function refresh(Request $request){

$request->user()->tokens->delete() ;
$newToken = $request->user()->createToken("auth-token")->painTextToken;

return response()->json([
    "message"=> "token refrainshie",
    'token'=>$newToken
]);

}

}