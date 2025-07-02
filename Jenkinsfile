pipeline {
    agent any

    stages {
        stage('Clone') {
            steps {
                git 'https://github.com/AKS144/laravelapp.git'
            }
        }

        stage('Build Docker Image') {
            steps {
                sh 'docker build -t laravelapp .'
            }
        }

        stage('Push to Minikube Docker') {
            steps {
                sh 'eval $(minikube docker-env) && docker build -t laravelapp .'
            }
        }

        stage('Deploy to Kubernetes') {
            steps {
                sh 'kubectl apply -f k8s/deployment.yaml'
                sh 'kubectl apply -f k8s/service.yaml'
            }
        }
    }
}
