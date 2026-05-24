pipeline {
    agent any
    
    options {
        buildDiscarder(logRotator(numToKeepStr: '5'))
        timeout(time: 15, unit: 'MINUTES')
    }

    stages {
        stage('Checkout & Clean') {
            steps {
                // This wipes any hidden corrupted state before pulling
                deleteDir() 
                
                // Explicitly pull the repository fresh
                git branch: 'main',
                    url: 'https://github.com/Dheeraj-Kapuganti/ventureLink-platform.git'
            }
        }

        stage('Build Docker Image') {
            steps {
                // Using --no-cache to save your t3.micro's precious RAM
                sh 'docker build --no-cache -t venturelink-app .'
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
                --restart unless-stopped \
                venturelink-app
                '''
            }
        }
    }
    
    post {
        always {
            // Wipe the files after completion to save EC2 disk space
            deleteDir()
        }
    }
}