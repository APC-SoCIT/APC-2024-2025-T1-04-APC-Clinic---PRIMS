pipeline {
    agent any

    environment {
        COMPOSE_FILE = 'docker-compose.yml'
        WWWUSER = '1000'
        WWWGROUP = '1000'
        DB_CONNECTION = 'mysql'
        DB_HOST = 'mysql'
        DB_PORT = '3306'
        DB_DATABASE = 'laravel'
        DB_USERNAME = 'sail'
        DB_PASSWORD = 'password'
    }

    stages {
        stage('Install Dependencies') {
            steps {
                sh 'composer install --no-interaction --prefer-dist --optimize-autoloader'
            }
        }

        stage('Start Sail') {
            steps {
                sh './vendor/bin/sail up -d'
            }
        }

        stage('Prepare Env') {
            steps {
                sh '''
                    cp .env.testing .env
                    chmod -R 777 storage bootstrap/cache .env
                '''
            }
        }

        stage('Generate Key') {
            steps {
                sh '''
                    if ! grep -q "APP_KEY=" .env || [ -z "$(grep 'APP_KEY=' .env | cut -d '=' -f2)" ]; then
                        key=$(./vendor/bin/sail artisan key:generate --show)
                        sed -i "s|^APP_KEY=.*|APP_KEY=$key|" .env
                    fi
                '''
            }
        }

        stage('Setup App') {
            steps {
                sh '''
                    ./vendor/bin/sail artisan key:generate
                    ./vendor/bin/sail artisan migrate:fresh --seed --env=testing
                    ./vendor/bin/sail root-shell -c "chown -R sail:sail /var/www/html"
                    ./vendor/bin/sail npm install
                    ./vendor/bin/sail npm audit fix || true
                    ./vendor/bin/sail npm run build
                '''
            }
        }

        stage('Unit Test') {
            steps {
                sh './vendor/bin/sail artisan test'
            }
        }

        stage('Integration Test') {
            steps {
                sh 'curl -f http://localhost || exit 1'
            }
        }

        stage('Create Docker Image') {
            steps {
                sh 'docker build -t prims-app:latest .'
            }
        }

        stage('Commit Jenkinsfile') {
            steps {
                sh '''
                git config user.email "jmmiyabe@student.apc.edu.ph"
                git config user.name "jmmiyabe"
                git add Jenkinsfile
                git commit -m "Add Jenkinsfile" || true
                git push origin HEAD:main
                '''
            }
        }
    }

    post {
        always {
            sh './vendor/bin/sail down || true'
        }
    }
}
