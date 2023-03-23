# Challenge

1) Clone repo
2) composer install
3) npm install
4) npm run build
5) docker compose up --build
6) php artisan migrate:fresh --seed
7) change .env file APP_env = testing
8) clear cache
9) php artisan test
10) change .env file App_env = local && composer dump-autoload
11) clear cache
12) now you can consume the api
# Docker file

I've build a docker file that will contain all the app, as well a set of automated instructions for the docker compose file:
1) Dockerfile
2) docker-compose.yml
3) docker/config - start.sh

# CI/CD
### Trigger branches currently [master | dev]
As you can see i've done automated deployments and testing on my repo, so in case that we need to push the image to production anything will be tested before the push, if a test fails the whole deployment will not be deployed. (github actions = .github/workflows)

# Testing
## Feature
- Applied for the features completed for this challenge
## Unit
- Unit Testing type applied for factory pattern: Check tests
# Factory Patter to read and upload different types of files
With this we ensure that we can have a different type of files to be uploaded, readed, modified, etc.
Check ➡ **App/Services/Api/V1/DocumentReader***
- Depending on the specific type of file that you want to add or read you could  implement or add more methods in the Documentinterface
- Testing to check factory method is working: Tests/unit/Api/v1/DocumentFactoryCanBeResolvedTest
- Factory solver is in config/filereader
# UUID

I've used the personal uuid trait: 
### ➡ "traits/uuid"
### 🗒️*Note: Almost all models are using this trait*
- Inside this file we're going to use the trait HasUuid
- In your model you can specify if... : 
    protected $primaryKey = 'uuid';
    protected $uuidKey = 'uuid';
    public $incrementing = false;
  ... It is Needed.

# Swagger 🍵 
### Swagger has been implemented for some of the endpoints - Just for the features that I did for the challenge 
*I Didn't implement the whole document.*
For further documentation go to your localhost/api/v1/documentation or if you're using the docker container is http://localhost:8085/api/v1/documentation or depending on your port change it.



