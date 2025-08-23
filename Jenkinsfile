pipeline {
    agent any

    environment {
        COMPOSE_FILE = 'docker-compose.yml'
        WWWUSER = '1000'
        WWWGROUP = '1000'
        DB_CONNECTION = 'mysql
        DB_HOST = 'mysql'
        DB_PORT = '3306'
        DB_DATABASE = 'prims_test'
        DB_USERNAME = 'sail'
        DB_PASSWORD = 'password'
        APP_ENV = 'testing'
    }

    stages {   // <-- everything MUST go inside this
        stage('Install Dependencies') {
            steps {
                dir('PRIMS') {
                    sh '''
                    # Install Sail if not present
                    docker run --rm -u "$(id -u):$(id -g)" -v $(pwd):/var/www/html -w /var/www/html laravelsail/php82-composer:latest composer require laravel/sail --dev

                    # Install composer dependencies without Sail
                    docker run --rm -u "$(id -u):$(id -g)" -v $(pwd):/var/www/html -w /var/www/html laravelsail/php82-composer:latest composer install --no-interaction --prefer-dist --optimize-autoloader
                    '''
                }
            }
        }

        stage('Start Sail') {
            steps {
                dir('PRIMS') {
                    sh './vendor/bin/sail up -d'
                }
            }
        }

        stage('Wait for Containers') {
            steps {
                dir('PRIMS') {
                    sh '''
                    echo "Waiting for laravel.test container..."
                    until ./vendor/bin/sail ps | grep laravel.test | grep Up; do sleep 5; done

                    echo "Waiting for MySQL..."
                    until ./vendor/bin/sail exec mysql mysqladmin ping -h mysql -u sail -ppassword --silent; do sleep 5; done

                    echo "All containers are ready!"
                    '''
                }
            }
        }

        stage('Prepare Test Database') {
            steps {
                dir('PRIMS') {
                    sh '''
                    ./vendor/bin/sail ls -la
                    ./vendor/bin/sail exec laravel.test ls -la
                    # Generate app key
                    ./vendor/bin/sail artisan key:generate

                    # Refresh test database and seed
                    ./vendor/bin/sail artisan migrate:fresh --seed --env=testing
                    '''
                }
            }
        }

        stage('Build Frontend Assets') {
            steps {
                dir('PRIMS') {
                    sh '''
                    ./vendor/bin/sail npm install
                    ./vendor/bin/sail npm audit fix
                    ./vendor/bin/sail npm run build
                    '''
                }
            }
        }

        stage('Run Unit Tests') {
            steps {
                dir('PRIMS') {
                    sh './vendor/bin/sail artisan test --env=testing'
                }
            }
        }

        stage('Run Integration Tests') {
            steps {
                dir('PRIMS') {
                    sh 'curl -f http://localhost || exit 1'
                }
            }
        }

        stage('Create Docker Image') {
            steps {
                dir('PRIMS') {
                    sh 'docker build -t prims-app:latest .'
                }
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
    } // end of stages

    post {
        always {
            dir('PRIMS') {
                sh './vendor/bin/sail down || true'
            }
        }
    }
}