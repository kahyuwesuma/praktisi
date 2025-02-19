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
            }
        }

        stage('Build Docker Image') {
            steps {
                script {
                    sh "docker build -t ${DOCKER_IMAGE}:${DOCKER_TAG} ."
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
                            docker run -d -p 8083:8083 --name '${CONTAINER_NAME}' \
                            -e DB_HOST=$DB_HOST \
                            -e APP_KEY=$APP_KEY \
                            -e DB_USERNAME=$DB_USERNAME \
                            -e DB_PASSWORD=$DB_PASSWORD \
                            -e DB_DATABASE=$DB_DATABASE \
                            ${DOCKER_REGISTRY}/${DOCKER_IMAGE}:${DOCKER_TAG} \
                            bash -c "php artisan serve --host=0.0.0.0 --port=8083"
                            '''
                        }
                    }
                }
            }
        }

        stage('Run Migrations and Seed') {
            steps {
                script {
                    sh "docker exec ${CONTAINER_NAME} php artisan migrate:fresh --seed"
                }
            }
        }

        stage('Run Test') {
            steps {
                script {
                    sh "docker exec ${CONTAINER_NAME} php artisan test"
                }
            }
        }
    }

    post {
        success {
            echo 'Pipeline succeeded!'
        }

        failure {
            echo 'Pipeline failed!'
            echo 'Cleaning up Docker containers'
            sh "docker stop ${CONTAINER_NAME} || true"
            sh "docker rm ${CONTAINER_NAME} || true"
            sh 'docker system prune -f'
        }
    }
}
