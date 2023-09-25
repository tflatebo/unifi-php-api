https://www.koyeb.com/tutorials/dockerize-and-deploy-a-laravel-application-to-production

Docker stuff
```
docker build -t tflatebo/unifi-parental-control:api -f Dockerfile .
docker run --rm -t --env-file /tmp/api-variables.env -p 8051:80 tflatebo/unifi-parental-control:api
docker push tflatebo/unifi-parental-control:api
```

Use the API on the command line
```
Get a list of all rules
curl -s -X GET -H "Content-Type: application/json" http://0.0.0.0:8051/api/firewallrules/

Get info about one rule


Turn a rule on
curl -s -X PUT -H "Content-Type: application/json" -d '{"enabled": true}' http://192.168.1.10:8051/api/firewallrules/<rule id> | jq .
```
