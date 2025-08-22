pipeline {
    agent any

    stages {
        stage('Build') {
            steps {
                echo 'Building the application...'
                dir('PRIMS') {
                    bat 'composer install'
                    bat 'npm install'
                    bat 'npm run build'
                }
            }
        }

        stage('Unit Test') {
            steps {
                dir('PRIMS') {
                    bat 'php artisan migrate --env=testing'
                    bat 'vendor\\bin\\phpunit.bat --coverage-text'
                }
            }
        }

        stage('Deploy To Test Env') {
            steps {
                echo 'Deploying to test environment...'
                bat 'scp -r . user@test-server:/var/www/test-app'
            }
        }

        stage('Integration Test') {
            steps {
                echo 'Running integration tests...'
                dir('PRIMS') {
                    bat 'php artisan test --testsuite=Feature'
                }
            }
        }

        stage('Create Docker Image') {
            steps {
                echo 'Building Docker image...'
                dir('PRIMS') {
                    bat 'docker build -t prims-app:latest .'
                }
            }
        }
    }

    post {
        always {
            echo 'Pipeline completed.'
        }
    }
}
