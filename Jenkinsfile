pipeline {
    agent any

    stages {

        stage('Clone Repository') {
            steps {
                git branch: 'main',
                url: 'https://github.com/Dheeraj-Kapuganti/ventureLink-platform.git'
            }
        }

        stage('Stop Old Containers') {
            steps {
                sh 'docker compose down || true'
            }
        }

        stage('Build And Start Containers') {
            steps {
                sh 'docker compose up --build -d'
            }
        }

    }
}