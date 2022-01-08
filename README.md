https://www.koyeb.com/tutorials/dockerize-and-deploy-a-laravel-application-to-production

```docker build -t tflatebo/unifi-parental-control:api -f Dockerfile .
docker run --rm -t -p 8051:80 tflatebo/unifi-parental-control:api
docker push tflatebo/unifi-parental-control:api```
