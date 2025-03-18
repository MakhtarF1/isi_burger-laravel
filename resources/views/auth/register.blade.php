<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - ISI BURGER</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #ff6b00;
            --primary-hover: #e05e00;
            --secondary-color: #ffa062;
            --background-gradient: linear-gradient(135deg, #ff8f3e 0%, #ff6b00 100%);
        }
        
        body {
            background-color: #f8f9fa;
            background-image: url('/api/placeholder/1920/1080');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .register-container {
            max-width: 500px;
            width: 100%;
            padding: 15px;
            animation: fadeIn 0.8s ease-in-out;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .card {
            border: none;
            border-radius: 18px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            backdrop-filter: blur(10px);
            background-color: rgba(255, 255, 255, 0.95);
        }
        
        .card-header {
            background: var(--background-gradient);
            color: white;
            border-radius: 0 !important;
            padding: 25px 20px;
            border-bottom: none;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        
        .card-header::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.2) 0%, rgba(255,255,255,0) 70%);
            opacity: 0.6;
        }
        
        .btn-primary {
            background: var(--background-gradient);
            border: none;
            border-radius: 30px;
            padding: 12px;
            font-weight: 600;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            font-size: 0.9rem;
            box-shadow: 0 5px 15px rgba(255, 107, 0, 0.3);
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            background: linear-gradient(135deg, #ff7920 0%, #e05e00 100%);
            box-shadow: 0 7px 20px rgba(255, 107, 0, 0.4);
            transform: translateY(-2px);
        }
        
        .form-control, .form-select {
            border-radius: 8px;
            padding: 12px 15px;
            font-size: 0.95rem;
            border: 1px solid #e1e1e1;
            background-color: #f9f9f9;
            transition: all 0.3s ease;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(255, 107, 0, 0.15);
            background-color: #fff;
        }
        
        .input-group-text {
            border-radius: 8px 0 0 8px;
            background-color: #f0f0f0;
            border: 1px solid #e1e1e1;
            border-right: none;
            color: #777;
        }
        
        .logo {
            font-size: 2.5rem;
            font-weight: bold;
            color: white;
            text-align: center;
            margin-bottom: 15px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .logo i {
            margin-right: 12px;
            filter: drop-shadow(2px 2px 3px rgba(0, 0, 0, 0.2));
            transform: rotate(-5deg);
            font-size: 2.8rem;
        }
        
        .btn-google {
            background-color: white;
            color: #444;
            border: 1px solid #eee;
            position: relative;
            padding: 12px 15px 12px 50px;
            text-align: center;
            font-weight: 600;
            border-radius: 30px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }
        
        .btn-google:hover {
            background-color: #f8f8f8;
            color: #333;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            transform: translateY(-2px);
        }
        
        .btn-google img {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            width: 20px;
            height: 20px;
        }
        
        .divider {
            display: flex;
            align-items: center;
            text-align: center;
            margin: 25px 0;
        }
        
        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            border-bottom: 1px solid #e0e0e0;
        }
        
        .divider span {
            padding: 0 15px;
            color: #888;
            font-size: 0.9rem;
            font-weight: 500;
        }
        
        .invalid-feedback {
            font-size: 0.85rem;
            font-weight: 500;
        }
        
        .card-footer {
            border-top: 1px solid rgba(0,0,0,0.05);
            background-color: rgba(248, 249, 250, 0.7);
            padding: 18px;
        }
        
        .card-footer a {
            color: var(--primary-color);
            font-weight: 500;
            transition: all 0.2s;
        }
        
        .card-footer a:hover {
            color: var(--primary-hover);
            text-decoration: underline !important;
        }
        
        .alert {
            border-radius: 8px;
            font-size: 0.9rem;
            font-weight: 500;
        }
        
        .card-body {
            padding: 30px;
        }
        
        label.form-label {
            font-weight: 500;
            font-size: 0.9rem;
            color: #555;
            margin-bottom: 5px;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="card">
            <div class="card-header text-center">
                <div class="logo">
                    <i class="fas fa-hamburger"></i> ISI BURGER
                </div>
                <h4 class="mb-0 fw-bold">Inscription</h4>
            </div>
            <div class="card-body p-4">
                @if (session('error'))
                    <div class="alert alert-danger mb-3">
                        {{ session('error') }}
                    </div>
                @endif
                
                <!-- Bouton d'inscription Google avec chemin local -->
                <div class="d-grid gap-2 mb-4">
                    <a href="{{ route('auth.google') }}" class="btn btn-google">
                        <img src="{{ asset('googleImg.png') }}" alt="Google Logo">
                        S'inscrire avec Google
                    </a>
                </div>
                
                <div class="divider">
                    <span>OU</span>
                </div>
                
                <form method="POST" action="{{ route('register') }}">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="name" class="form-label">Nom complet</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required autofocus>
                        </div>
                        @error('name')
                            <div class="invalid-feedback d-block mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="email" class="form-label">Adresse e-mail</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required>
                        </div>
                        @error('email')
                            <div class="invalid-feedback d-block mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="role" class="form-label">Type de compte</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-user-tag"></i></span>
                            <select class="form-select @error('role') is-invalid @enderror" id="role" name="role" required>
                                <option value="client" {{ old('role') == 'client' ? 'selected' : '' }}>Client</option>
                                <option value="gestionnaire" {{ old('role') == 'gestionnaire' ? 'selected' : '' }}>Gestionnaire</option>
                            </select>
                        </div>
                        @error('role')
                            <div class="invalid-feedback d-block mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="password" class="form-label">Mot de passe</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required>
                        </div>
                        @error('password')
                            <div class="invalid-feedback d-block mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-4">
                        <label for="password_confirmation" class="form-label">Confirmer le mot de passe</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                        </div>
                    </div>
                    
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">S'inscrire</button>
                    </div>
                </form>
            </div>
            <div class="card-footer text-center py-3">
                <p class="mb-0">Vous avez déjà un compte ? <a href="{{ route('login') }}" class="text-decoration-none">Se connecter</a></p>
                <p class="mt-2 mb-0"><a href="{{ route('home') }}" class="text-decoration-none"><i class="fas fa-arrow-left me-2"></i>Retour à l'accueil</a></p>
            </div>
        </div>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>