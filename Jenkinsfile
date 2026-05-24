pipeline {
    agent any

    stages {

        stage('Clone Repository') {
            steps {
                git branch: 'main',
                url: 'https://github.com/Dheeraj-Kapuganti/ventureLink-platform.git'
            }
        }

        stage('Build Docker Image') {
            steps {
                sh 'docker build -t venturelink-app .'
            }
        }

        stage('Run Container') {
            steps {
                sh 'docker stop venturelink-container || true'
                sh 'docker rm venturelink-container || true'

                sh '''
                docker run -d \
                --name venturelink-container \
                -p 8000:8000 \
                venturelink-app
                '''
            }
        }

    }
}