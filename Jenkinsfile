pipeline {
    agent any

    environment {
        DOCKER_IMAGE = 'praktisi-app'
        DOCKER_REGISTRY = 'docker.io'
        DOCKER_TAG = 'latest'
        CONTAINER_NAME = 'praktisi-app-container'
    }

    stages {
         stage('Checkout Code') {
            steps {
                git branch: 'main', credentialsId: 'github-praktisi', url: 'git@github.com:kahyuwesuma/DevOps-PRAKTISI.git'
                echo 'Berhasil'
            }
         }

         stage('Build Docker Image') {
            steps {
                script {
                    sh "docker build -t ${DOCKER_IMAGE}:${DOCKER_TAG} ."
                    echo 'Build Berhasil'
                }
            }
        }

        stage('Run Laravel Container') {
            steps {
                script {
                    withCredentials([
                        string(credentialsId: 'DB_HOST', variable: 'DB_HOST'),
                        string(credentialsId: 'APP_KEY', variable: 'APP_KEY'),
                        string(credentialsId: 'DB_USERNAME', variable: 'DB_USERNAME'),
                        string(credentialsId: 'DB_PASSWORD', variable: 'DB_PASSWORD'),
                        string(credentialsId: 'DB_DATABASE', variable: 'DB_DATABASE')
                    ]) {
                        withEnv([
                            "DB_HOST=${DB_HOST}",
                            "APP_KEY=${APP_KEY}",
                            "DB_USERNAME=${DB_USERNAME}",
                            "DB_PASSWORD=${DB_PASSWORD}",
                            "DB_DATABASE=${DB_DATABASE}"
                        ]) {
                            sh '''
                            echo "Starting Docker Container..."
                            echo "Container Name: $CONTAINER_NAME"
                            echo "Docker Image: ${DOCKER_REGISTRY}/${DOCKER_IMAGE}:${DOCKER_TAG}"

                            docker run -d -p 8083:8083 --name $CONTAINER_NAME \
                            -e DB_HOST=$DB_HOST \
                            -e APP_KEY=$APP_KEY \
                            -e DB_USERNAME=$DB_USERNAME \
                            -e DB_PASSWORD=$DB_PASSWORD \
                            -e DB_DATABASE=$DB_DATABASE \
                            ${DOCKER_REGISTRY}/${DOCKER_IMAGE}:${DOCKER_TAG} \
                            bash -c "php artisan serve --host=0.0.0.0 --port=8083"

                            echo "Container Started Successfully!"
                            '''
                        }
                    }
                }
            }
        }

        stage('Run Migrations and Seed') {
            steps {
                script {
                    sh '''
                    echo "Running Migrations and Seeding Database..."
                    docker exec $CONTAINER_NAME php artisan migrate:fresh --seed
                    echo "Migrations Completed!"
                    '''
                }
            }
        }

        stage('Clear Cache and Optimize') {
            steps {
                script {
                    sh '''
                    echo "Running Tests..."
                    docker exec $CONTAINER_NAME php artisan config:cache
                    docker exec $CONTAINER_NAME php artisan optimize
                    echo "Tests Completed!"
                    '''
                }
            }
        }

        stage('Run Tests') {
            steps {
                script {
                    sh '''
                    echo "Running Tests..."
                    docker exec $CONTAINER_NAME php artisan test
                    echo "Tests Completed!"
                    '''
                }
            }
        }
    }

    post {
        success {
            echo 'Pipeline Succeeded!'
        }

        failure {
            echo 'Pipeline Failed!'
            echo 'Cleaning up Docker Containers...'
            script {
                sh '''
                docker ps -a
                docker stop $CONTAINER_NAME || true
                docker rm $CONTAINER_NAME || true
                docker system prune -f
                '''
            }
        }
    }
}
