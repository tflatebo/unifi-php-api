https://www.koyeb.com/tutorials/dockerize-and-deploy-a-laravel-application-to-production

Docker stuff
```
docker build -t tflatebo/unifi-parental-control:api -f Dockerfile .
docker run --rm -t -p 8051:80 tflatebo/unifi-parental-control:api
docker push tflatebo/unifi-parental-control:api
```

Use the API on the command line
```
curl -s -X PUT -H "Content-Type: application/json" -d '{"enabled": true}' http://192.168.1.10:8051/api/firewallrules/<rule id> | jq .
```
## Run the API locally
php artisan serve
