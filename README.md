# Visitor Management Service

[![SonarCloud Status](https://sonarcloud.io/api/project_badges/measure?project=ALICE-SaaS_visitor-management-service&metric=alert_status&token=3138a23fd3451991e83eda8940adb84e4fc30188)](https://sonarcloud.io/dashboard?id=ALICE-SaaS_visitor-management-service)

[![CI Status](https://github.com/ALICE-SaaS/visitor-management-service/workflows/CI/badge.svg)](https://github.com/ALICE-SaaS/visitor-management-service/actions)

## Environment Setup

Place a file named `.env` in the root directory, in which you can configure the path to your PPK key to enable the SSH tunnel for the RDS connection:

```env
PPK_FILE=C:\Users\ScottCollier\.ssh\laureninnovations-us-east-2-test.ppk
```

You can also configure port mappings for the various containers in the Compose stack, in case you happen to already have running applications on the standard ports (80/8080/5432):

```env
SERVICE_PORT=8081
INGRESS_PORT=81
POSTGRES_PORT=5433
```

For sending emails from your dev environment, you will also need an AWS credentials file:

```env
AWS_CREDS=C:\AWS\credentials
```

For linking password reset emails to front-end application:
```env
CLIENT_ENDPOINT=http://localhost:3000
```

## Running in Docker

As long as your `.env` file is configured as documented above, just run `docker-compose up`.  The application will be available at `localhost:8080`.

## Running Tests

The easiest way to run the unit test suite is via the `Makefile`:

```bash
make test
```

Under the hood, the `Makefile` just runs the following (which you can also run from the command line):

```bash
docker-compose run --rm visitor-management sh -c ./vendor/bin/phpunit
```

Of course if you have PHP/Composer installed on your local system, you can just run the tests locally via your normal PHP/Composer/IDE workflow.

## Local DNS

If you would like to access the services locally via a friendly name, e.g., `api.navigate360.com`, just add an entry as follows to your OS's `hosts` file:

```env
127.0.0.1   api.navigate360.com
```

The Compose stack now contains an Nginx reverse proxy which acts like an ingress controller and watches the Docker API for container port mappings.

Since the Visitor Management service runs on port 8080, you can then send a request like:

```bash
curl -X GET api.navigate360.com:8080/persons/1
```

## Configuring the Debugger with Docker

The XDebug configuration is automatically created in the container.  From there, you simply need to configure your editor/IDE.

Here's an example launch configuration for Visual Studio Code:

```json
{
  "version": "0.2.0",
  "configurations": [
    {
      "name": "Docker XDebug",
      "type": "php",
      "request": "launch",
      "pathMappings": {
          "/var/www": "${workspaceRoot}"
      },
      "port": 9000,
      "log": true
    }
  ]
}
```

## Releases

The first step of the "release" process is UAT.

To trigger a UAT build, create a new *release* branch.  The naming scheme for the branch should be *release/{version}*, e.g., `release/3.1.4`.

**IMPORTANT**: Releases require *Git tags*.  The tag on the branch indicates the release *version* and will be used to generate artifact tags.

The process for promoting the current `develop` branch to UAT thus looks like, assuming a hypothetical v3.0.0:

```bash
git fetch
git checkout develop
git pull
git checkout -b release/3.0.0
git commit -m "Release v3.0.0"
git tag -a 3.0.0 -m "v3.0.0"
git push --set-upstream origin release/3.0.0 --follow-tags
```

The process for doing a *hotfix* release is slightly different, as the **source branch** will be an existing release rather than `develop`:

```bash
git fetch
git checkout release/3.0.0
git pull
git checkout -b release/3.0.1
git commit -m "Release v3.0.1"
git tag -a 3.0.1 -m "v3.0.1"
git push --set-upstream origin release/3.0.1 --follow-tags
```
