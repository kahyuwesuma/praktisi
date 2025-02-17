pipeline {
    agent any
    environment {
        IMAGE_NAME = "praktisi_app"
        CONTAINER_NAME = "praktisi_app_container"
        DOCKER_HUB_REPO = "username/laravel_app"
    }
    stages {
        stage('Checkout Code') {
            steps {
                git branch: 'main', credentialsId: 'github-praktisi', url: 'git@github.com:kahyuwesuma/praktisi.git'
                echo 'Berhasil'
            }
        }
        stage('Build Docker Image') {
            steps {
                sh 'docker build -t $IMAGE_NAME .'
            }
        }
        stage('Run Container') {
            steps {
                sh 'docker stop $CONTAINER_NAME || true && docker rm $CONTAINER_NAME || true'
                sh 'docker run -d --name $CONTAINER_NAME -p 8000:8000 $IMAGE_NAME'
            }
        }
        stage('Run Unit Tests') {
            steps {
                sh 'docker exec $CONTAINER_NAME php artisan test --parallel'
            }
        }
        stage('Migrate Database') {
            steps {
                sh 'docker exec $CONTAINER_NAME php artisan migrate --force'
            }
        }
        stage('Deploy') {
            steps {
                sh 'docker stop $CONTAINER_NAME || true'
                sh 'docker rm $CONTAINER_NAME || true'
                sh 'docker run -d --name $CONTAINER_NAME -p 8000:8000 $IMAGE_NAME'
            }
        }
    }
    post {
        always {
            junit 'tests/logs/junit.xml' // 
        }
    }
}
