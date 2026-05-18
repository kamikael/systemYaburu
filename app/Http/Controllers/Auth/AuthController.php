<?php



namespace  App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Account;
use App\Models\UserAccount;


class AuthController extends Controller {

public function register(Request $request)
{
    $validated = $request->validate([
        'email' => 'required|email|unique:users,email',
        'firstname' => 'required|string',
        'lastname' => 'required|string',
        'phone' => 'nullable|digits_between:8,13',
        'password' => 'required|string|min:8|confirmed',

        // 🔥 account obligatoire
        'account_name' => 'required|string',
        'account_ref' => 'required|string',
    ]);

    DB::beginTransaction();

    try {

        // 1. CREATE USER
        $user = User::create([
            'email' => $validated['email'],
            'firstname' => $validated['firstname'],
            'lastname' => $validated['lastname'],
            'phone' => $validated['phone'] ?? null,
            'password' => Hash::make($validated['password']),
            'is_admin' => false,
            'is_agentia' => false,
        ]);

        // 2. CREATE ACCOUNT
        $account = Account::create([
            'uuid' => \Str::uuid(),
            'account_name' => $validated['account_name'],
            'account_ref' => $validated['account_ref'],
           'account_feda_id' => (string) Str::uuid(),
        ]);

        // 3. LINK USER ↔ ACCOUNT
        UserAccount::create([
            'user_id' => $user->id,
            'account_id' => $account->id,
            'owner' => true,
        ]);

        DB::commit();

        $token = $user->createToken('auth-token')->plainTextToken;

        return response()->json([
            'message' => 'Compte créé avec succès',
            'user' => $user,
            'account' => $account,
            'token' => $token,
        ], 201);

    } catch (\Exception $e) {
        DB::rollBack();

        return response()->json([
            'message' => 'Erreur création compte',
            'error' => $e->getMessage()
        ], 500);
    }
}

  // Profil de l'utilisateur connecté
    public function me(Request $request)
    {
        return response()->json($request->user());
    }

public function login(Request $request)
{
    $validated = $request->validate([
        "email" => 'required|email',
        "password" => "required|string",
        "account_id" => "required|integer",
    ]);

    $user = User::where("email", $validated["email"])->first();

    if (!$user || !Hash::check($validated["password"], $user->password)) {
        throw ValidationException::withMessages([
            'email' => ['Identifiants incorrects.'],
        ]);
    }

    // 🔥 vérifier que user appartient à ce account
    $hasAccess = UserAccount::where('user_id', $user->id)
        ->where('account_id', $validated['account_id'])
        ->exists();

    if (!$hasAccess) {
        return response()->json([
            'message' => 'Accès refusé à ce compte'
        ], 403);
    }

    $token = $user->createToken('auth-token', [
        'account_id' => $validated['account_id']
    ])->plainTextToken;

    return response()->json([
        "message" => "connexion réussie",
        "user" => $user,
        "account_id" => $validated['account_id'],
        "token" => $token
    ], 200);
}

public function logout(Request $request)
{
    $request->user()->currentAccessToken()->delete();

    return response()->json([
        "message" => "déconnexion réussie"
    ]);
}

public function refresh(Request $request)
{
    $request->user()->tokens()->delete();

    $newToken = $request->user()
        ->createToken("auth-token")
        ->plainTextToken;

    return response()->json([
        "message" => "token refreshie",
        "token" => $newToken
    ]);
}

public function adminLogin(Request $request)
{
    $validated = $request->validate([
        "email" => "required|email",
        "password" => "required|string",
    ]);

    // 🔥 rechercher utilisateur
    $user = User::where('email', $validated['email'])
        ->first();

    // 🔥 vérifier credentials
    if (
        !$user ||
        !Hash::check($validated['password'], $user->password)
    ) {
        throw ValidationException::withMessages([
            'email' => ['Identifiants incorrects.'],
        ]);
    }

    // 🔥 vérifier rôle admin / agentia
    if (
        !$user->is_admin &&
        !$user->is_agentia
    ) {
        return response()->json([
            'message' => 'Accès administrateur refusé'
        ], 403);
    }

    // 🔥 création token
    $token = $user->createToken(
        'admin-token'
    )->plainTextToken;

    return response()->json([
        'message' => 'Connexion administrateur réussie',

        'user' => [
            'id' => $user->id,
            'firstname' => $user->firstname,
            'lastname' => $user->lastname,
            'email' => $user->email,

            'is_admin' => $user->is_admin,
            'is_agentia' => $user->is_agentia,
        ],

        'token' => $token,
    ], 200);
}

}