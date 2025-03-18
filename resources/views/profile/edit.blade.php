@extends('layouts.app')

@section('title', 'Mon profil')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <!-- Profile Information Card -->
            <div class="card mb-4 border-0 shadow-sm">
                <div class="card-header bg-black text-white py-3">
                    <h4 class="mb-0"><i class="fas fa-user-circle me-2"></i>Informations du profil</h4>
                </div>
                <div class="card-body bg-light">
                    <form method="POST" action="{{ route('profile.update') }}">
                        @csrf
                        @method('patch')

                        <div class="mb-3">
                            <label for="name" class="form-label fw-bold">Nom</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label fw-bold">Adresse e-mail</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Type de compte</label>
                            <input type="text" class="form-control" value="{{ $user->role === 'gestionnaire' ? 'Gestionnaire' : 'Client' }}" disabled>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-warning text-dark fw-bold">
                                <i class="fas fa-save me-2"></i>Mettre à jour le profil
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Update Password Card -->
            <div class="card mb-4 border-0 shadow-sm">
                <div class="card-header bg-black text-white py-3">
                    <h4 class="mb-0"><i class="fas fa-lock me-2"></i>Mettre à jour le mot de passe</h4>
                </div>
                <div class="card-body bg-light">
                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf
                        @method('put')

                        <div class="mb-3">
                            <label for="current_password" class="form-label fw-bold">Mot de passe actuel</label>
                            <input type="password" class="form-control @error('current_password') is-invalid @enderror" id="current_password" name="current_password" required>
                            @error('current_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label fw-bold">Nouveau mot de passe</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label fw-bold">Confirmer le nouveau mot de passe</label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-warning text-dark fw-bold">
                                <i class="fas fa-key me-2"></i>Mettre à jour le mot de passe
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Delete Account Card -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-black text-white py-3">
                    <h4 class="mb-0"><i class="fas fa-trash-alt me-2"></i>Supprimer le compte</h4>
                </div>
                <div class="card-body bg-light">
                    <p class="card-text">Une fois votre compte supprimé, toutes ses ressources et données seront définitivement effacées.</p>
                    <form method="POST" action="{{ route('profile.destroy') }}" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer votre compte ?');">
                        @csrf
                        @method('delete')
                        <div class="d-grid">
                            <button type="submit" class="btn btn-danger fw-bold">
                                <i class="fas fa-user-slash me-2"></i>Supprimer le compte
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Success Message Toast -->
@if (session('status'))
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
    <div id="successToast" class="toast show bg-success text-white" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header bg-success text-white">
            <strong class="me-auto"><i class="fas fa-check-circle me-2"></i>Succès</strong>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body">
            {{ session('status') === 'profile-updated' ? 'Profil mis à jour avec succès.' : 
               (session('status') === 'password-updated' ? 'Mot de passe mis à jour avec succès.' : session('status')) }}
        </div>
    </div>
</div>
<script>
    setTimeout(function() {
        const toast = document.getElementById('successToast');
        if (toast) {
            const bsToast = new bootstrap.Toast(toast);
            bsToast.hide();
        }
    }, 3000);
</script>
@endif

<!-- Add Font Awesome for icons in the head section of your layout -->
@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    :root {
        --burger-orange: #FF8C00;
        --burger-dark: #212529;
    }
    
    .btn-warning {
        background-color: var(--burger-orange);
        border-color: var(--burger-orange);
    }
    
    .btn-warning:hover {
        background-color: #e67e00;
        border-color: #e67e00;
    }
    
    .card-header {
        border-bottom: 3px solid var(--burger-orange);
    }
    
    .form-control:focus {
        border-color: var(--burger-orange);
        box-shadow: 0 0 0 0.25rem rgba(255, 140, 0, 0.25);
    }
</style>
@endpush
@endsection