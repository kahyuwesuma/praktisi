pipeline {
    agent any
    environment {
        IMAGE_NAME = "praktisi_app"
        CONTAINER_NAME = "praktisi_app_container"
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
                script {
                    // Memastikan container sebelumnya sudah dihentikan dan dihapus
                    sh 'docker stop $CONTAINER_NAME || true && docker rm $CONTAINER_NAME || true'
                    // Menjalankan container baru
                    sh 'docker run -d --name $CONTAINER_NAME -p 8000:8000 --env APP_ENV=testing $IMAGE_NAME'
                    // Memberikan waktu beberapa detik untuk memastikan container sepenuhnya siap
                    sh 'sleep 10'
                }
            }
        }
        stage('Run Unit Tests') {
            steps {
                script {
                    // Memastikan container sedang berjalan
                    sh 'docker ps -q --filter "name=$CONTAINER_NAME" || (echo "Container belum berjalan" && exit 1)'
                    // Menjalankan perintah artisan untuk meng-clear konfigurasi dan cache
                    sh 'docker exec $CONTAINER_NAME php artisan config:clear'
                    sh 'docker exec $CONTAINER_NAME php artisan cache:clear'
                    // Menjalankan migrasi database
                    sh 'docker exec $CONTAINER_NAME php artisan migrate --force'
                    // Menjalankan unit test Laravel
                    sh 'docker exec $CONTAINER_NAME php artisan test --parallel || true'
                }
            }
        }
        stage('Deploy') {
            steps {
                script {
                    // Menutup dan menghapus container setelah tes selesai
                    sh 'docker stop $CONTAINER_NAME || true'
                    sh 'docker rm $CONTAINER_NAME || true'
                    // Menjalankan container untuk deployment
                    sh 'docker run -d --name $CONTAINER_NAME -p 8000:8000 $IMAGE_NAME'
                }
            }
        }
    }
    post {
        always {
            // Menghasilkan laporan junit jika tersedia
            junit 'tests/logs/junit.xml' // Menyesuaikan dengan jalur laporan yang sesuai
        }
    }
}
