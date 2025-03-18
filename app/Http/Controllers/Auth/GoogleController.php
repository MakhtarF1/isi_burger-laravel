<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    /**
     * Rediriger l'utilisateur vers la page d'authentification Google.
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Obtenir les informations de l'utilisateur depuis Google.
     */
    public function handleGoogleCallback(): RedirectResponse
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            
            // Log pour débogage
            Log::info('Google user data:', [
                'id' => $googleUser->id,
                'email' => $googleUser->email,
                'name' => $googleUser->name
            ]);
            
            // Vérifier si l'utilisateur existe déjà avec cet ID Google
            $user = User::where('google_id', $googleUser->id)->first();
            
            // Si l'utilisateur n'existe pas, vérifier par email
            if (!$user) {
                // Vérifier si l'email existe déjà
                $existingUser = User::where('email', $googleUser->email)->first();
                
                if ($existingUser) {
                    // Mettre à jour l'utilisateur existant avec les informations Google
                    $existingUser->update([
                        'google_id' => $googleUser->id,
                        'avatar' => $googleUser->avatar,
                    ]);
                    
                    $user = $existingUser;
                    Log::info('Utilisateur existant mis à jour avec Google ID', ['user_id' => $user->id]);
                } else {
                    // Créer un nouvel utilisateur
                    $user = User::create([
                        'name' => $googleUser->name,
                        'email' => $googleUser->email,
                        'google_id' => $googleUser->id,
                        'avatar' => $googleUser->avatar,
                        'password' => Hash::make(uniqid()), // Mot de passe aléatoire
                        'role' => 'client', // Par défaut, les utilisateurs Google sont des clients
                    ]);
                    
                    Log::info('Nouvel utilisateur créé via Google', ['user_id' => $user->id]);
                    event(new Registered($user));
                }
            } else {
                Log::info('Utilisateur existant connecté via Google', ['user_id' => $user->id]);
            }
            
            // Connecter l'utilisateur
            Auth::login($user);
            
            return redirect()->intended(route('home'));
            
        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'authentification Google', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->route('login')
                ->with('error', 'Une erreur est survenue lors de la connexion avec Google: ' . $e->getMessage());
        }
    }
}