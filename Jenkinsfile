pipeline {
    agent any
    environment {
        IMAGE_NAME = "praktisi_app"
        CONTAINER_NAME = "praktisi_app_container"
        COMPOSE_FILE = "docker-compose.yml"
    }
    stages {
        stage('Checkout Code') {
            steps {
                git branch: 'main', credentialsId: 'github-praktisi', url: 'git@github.com:kahyuwesuma/praktisi.git'
                echo 'Berhasil Checkout Kode'
            }
        }
        stage('Build & Start Containers') {
            steps {
                script {
                    sh 'docker-compose down || true'
                    sh 'docker-compose up -d --build'
                    sh 'sleep 10' // Memberikan waktu agar container siap
                }
            }
        }
        stage('Check Database Connection') {
            steps {
                script {
                    sh 'docker-compose exec -T app ls /var/www/database'
                }
            }
        }
        stage('Run Unit Tests') {
            steps {
                script {
                    sh 'docker-compose exec -T app php artisan config:clear'
                    sh 'docker-compose exec -T app php artisan cache:clear'
                    sh 'docker-compose exec -T app php artisan migrate --force'
                    sh 'docker-compose exec -T app php artisan test --parallel || true'
                }
            }
        }
        stage('Deploy') {
            steps {
                script {
                    sh 'docker-compose down || true'
                    sh 'docker-compose up -d'
                }
            }
        }
    }
    post {
        always {
            junit 'tests/logs/junit.xml'
        }
    }
}
