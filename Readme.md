buid:
```bash
docker-compose -f ./docker.server/docker-compose.yml build
```

run:
```bash
docker-compose -f ./docker.server/docker-compose.yml up -d
```

server run in http://localhost:85


go to shell:
```bash
cd ./docker.server; docker-compose exec php-fpm /bin/sh; cd ..
```


in shell:

make migration:<br>
```bash
cd /var/www/server/
./bin/console doctrine:migrations:migrate
```

test client:
```bash
cd /var/www/client
cd ./bin/console
```

```
Symfony 6.2.9 (env: dev, debug: true) #StandWithUkraine https://sf.to/ukraine
.....
Available commands:
 api
  api:group:add               create a new group
  api:group:del               delete group
  api:group:get               get group by name
  api:group:update            update group
  api:stat:groups             list groups
  api:stat:users              list of users
  api:user:add                create a new user
  api:user:del                delete user by email
  api:user:get                get user by email
  api:user:group:add          add group to user
  api:user:group:del          remove group from user
  api:user:update             update user info
....
```


```bash
/var/www/client $ ./bin/console api:stat:groups
{
    "success": true,
    "code": 1,
    "groups": []
}
/var/www/client $ 
```

Example to create new group:

```bash
/var/www/client $ ./bin/console api:group:add

Enter name:
> Test

{
  "success": true,
  "code": 1
}
/var/www/client $ ./bin/console api:stat:groups
{
  "success": true,
  "code": 1,
  "groups": [
    {
      "id": 1,
      "name": "Test",
      "users": []
    }
  ]
}
/var/www/client $ 
```