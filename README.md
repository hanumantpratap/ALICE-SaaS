# Visitor Management Service

[![SonarCloud Status](https://sonarcloud.io/api/project_badges/measure?project=ALICE-SaaS_visitor-management-service&metric=alert_status)](https://sonarcloud.io/dashboard?id=ALICE-SaaS_visitor-management-service)

[![CI Status](https://github.com/ALICE-SaaS/visitor-management-service/workflows/CI/badge.svg)](https://github.com/ALICE-SaaS/visitor-management-service/actions)

## Environment Setup
Place a file named `.env` in the root directory, in which you can configure the path to your PPK key to enable the SSH tunnel for the RDS connection:

```env

PPK_FILE=C:\Users\ScottCollier\.ssh\laureninnovations-us-east-2-test.ppk

```

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
