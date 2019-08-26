
node('master') {
    try {
        notifyBuild('STARTED')
        stage('setup') {
            // Checkout the app at the given commit sha from the webhook
            checkout scm
            // Install dependencies, create a new .env file and generate a new key, just for testing
            sh "composer install --no-progress --no-suggest --prefer-dist"
            sh "touch -a database/testing.sqlite"
            sh "cp .env.pipelines .env"
            sh "php artisan key:generate --force"
           // sh "php artisan jwt:secret --force"
        }
         stage('test') {
            // Run any testing suites
            sh "./vendor/bin/phpunit"
        }
        stage('quality') {
            sh "./vendor/bin/phpcs"
            sh "./vendor/bin/phpmd"
        }

        stage('deploy') {
            // If we had ansible installed on the server, setup to run an ansible playbook
            // sh "ansible-playbook -i ./ansible/hosts ./ansible/deploy.yml"
            sh "echo 'WE ARE DEPLOYING'"
        }
    } catch (e) {
    // If there was an exception thrown, the build failed
    currentBuild.result = "FAILED"
    throw e
  } finally {
    // Success or failure, always send notifications
    notifyBuild(currentBuild.result)
  }
}

def notifyBuild(String buildStatus = 'STARTED') {
  // build status of null means successful
  buildStatus =  buildStatus ?: 'SUCCESSFUL'

  // Default values
  def colorName = 'RED'
  def colorCode = '#FF0000'
  def subject = "${buildStatus}: Job '${env.JOB_NAME} [${env.BUILD_NUMBER}]'"
  def summary = "${subject} (${env.BUILD_URL})"
  def details = """<p>STARTED: Job '${env.JOB_NAME} [${env.BUILD_NUMBER}]':</p>
    <p>Check console output at &QUOT;<a href='${env.BUILD_URL}'>${env.JOB_NAME} [${env.BUILD_NUMBER}]</a>&QUOT;</p>"""

  // Override default values based on build status
  if (buildStatus == 'STARTED') {
    color = 'YELLOW'
    colorCode = '#FFFF00'
  } else if (buildStatus == 'SUCCESSFUL') {
    color = 'GREEN'
    colorCode = '#00FF00'
  } else {
    color = 'RED'
    colorCode = '#FF0000'
  }

  // Send notifications
   slackSend channel: '#general',
             color: colorCode,
             message: "*${currentBuild.currentResult}:* Job ${env.JOB_NAME} build ${env.BUILD_NUMBER}\n More info at: ${env.BUILD_URL}"
}
