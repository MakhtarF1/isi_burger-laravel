pipeline {
    agent any
    
    stages {
        stage('Checkout') {
            steps {
                // Récupérer le code depuis GitHub
                checkout scm
            }
        }
        
        stage('Install Dependencies') {
            steps {
                // Installer les dépendances PHP
                sh 'composer install --no-interaction --optimize-autoloader'
                
                // Installer les dépendances Node.js
                sh 'npm install'
            }
        }
        
        stage('Setup Environment') {
            steps {
                // Copier le fichier d'environnement
                sh 'cp .env.example .env'
                
                // Générer la clé d'application
                sh 'php artisan key:generate'
            }
        }
        
        stage('Run Tests') {
            steps {
                // Exécuter les tests
                sh 'php artisan test'
            }
        }
        
        stage('Build Assets') {
            steps {
                // Compiler les assets
                sh 'npm run build'
            }
        }
        
        stage('Build Docker Image') {
            steps {
                // Construire l'image Docker
                sh 'docker build -t isi-burger:${BUILD_NUMBER} .'
            }
        }
        
        stage('Deploy') {
            steps {
                // Déployer l'application
                sh 'docker stop isi-burger || true'
                sh 'docker rm isi-burger || true'
                sh 'docker run -d --name isi-burger -p 8080:80 isi-burger:${BUILD_NUMBER}'
            }
        }
    }
    
    post {
        success {
            echo 'Déploiement réussi!'
        }
        failure {
            echo 'Le déploiement a échoué.'
        }
    }
}