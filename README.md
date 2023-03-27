# Challenge

1) Clone repo
2) composer install
3) npm install
4) npm run build
5) docker compose up --build
6) php artisan migrate:fresh --seed
7) change .env file APP_env = testing
8) run composer dump-autoload, next php artisan config:cache
9) php artisan test
10) change .env file App_env = local && composer dump-autoload && php artisan config:cache
11) php artisan l5-swagger:generate
12) clear cache
13) now you can consume the api
# Docker file

I've build a docker file that will contain all the app, as well a set of automated instructions for the docker compose file:
1) [Dockerfile](https://github.com/Quisui/buckhill-challenge/blob/develop/Dockerfile)
2) [docker-compose.yml](https://github.com/Quisui/buckhill-challenge/blob/develop/docker-compose.yml)
3) [docker/config - start.sh](https://github.com/Quisui/buckhill-challenge/tree/develop/docker)

# CI/CD [link](https://github.com/Quisui/buckhill-challenge/tree/develop/.github/workflows)
### Trigger branches currently [master | dev]
As you can see i've done automated deployments and testing on my repo, so in case that we need to push the image to production anything will be tested before the push, if a test fails the whole deployment will not be deployed. (github actions = .github/workflows)

# L4 Challenge Package
So I've build the package that will send a webhook notification for the order status:
My package's name is: [quisui/order-basic-notification](https://github.com/Quisui/order-basic-notification)
To use it on this or other projects you need to run:
> composer require quisui/order-basic-notification

<br> Check package to see how's implemented in this project: [read](https://github.com/Quisui/order-basic-notification)

# Challenge Integration
So as the challenge requirements the package was integrated to an observer like so:
### **[OrderObserver](https://github.com/Quisui/buckhill-challenge/blob/develop/app/Observers/OrderObserver.php)**
Any time that an order controller updates an order and the respective checks do their job, and the status order has been changed, we'll send a webhook to specific url.

Check **[Order Controller](https://github.com/Quisui/buckhill-challenge/blob/develop/app/Http/Controllers/Api/V1/OrderController.php)** for logic implementation

### **[Testing](https://github.com/Quisui/buckhill-challenge/blob/develop/tests/Feature/Api/V1/Controllers/OrderControllerTest.php) for this controller and possible bugs**

I've implemented testing for the order controller to ensure the whole functionality, I didn't tested the package here because that's why we test the package before

# PHP insights <br>
<img width="500" alt="image" src="https://user-images.githubusercontent.com/22399803/228089729-2c6c6718-ba97-43b0-80a8-85b634cfa88b.png"> <br>

At this moment the provided score with this package [nunomaduro/phpinsights](https://github.com/nunomaduro/phpinsights)

And the provided [rules](https://github.com/Quisui/buckhill-challenge/blob/develop/config/insights.php) from the challenge<br>

# Testing
## [Feature](https://github.com/Quisui/buckhill-challenge/tree/develop/tests/Feature)
- Applied for the features completed for this challenge
## [Unit](https://github.com/Quisui/buckhill-challenge/tree/develop/tests/Unit)
- Unit Testing type applied for factory pattern: Check tests
# Factory Patter to read and upload different types of files
With this we ensure that we can have a different type of files to be uploaded, readed, modified, etc.
Check ➡ **App/Services/Api/V1/DocumentReader*** [link](https://github.com/Quisui/buckhill-challenge/tree/develop/app/Services/Api/V1/DocumentReader)
- Depending on the specific type of file that you want to add or read you could  implement or add more methods in the Documentinterface [link](https://github.com/Quisui/buckhill-challenge/blob/develop/app/Services/Api/V1/DocumentReader/DocumentInterface.php)
- Testing to check factory method is working: [Tests/unit/Api/v1/DocumentFactoryCanBeResolvedTest](https://github.com/Quisui/buckhill-challenge/blob/develop/tests/Unit/Api/V1/DocumentFactoryCanBeResolvedTest.php)
- Factory solver is in [config/filereader](https://github.com/Quisui/buckhill-challenge/blob/develop/config/filereader.php)
# JWT
As the implementation requested I've implemented a complete JWT Auth system using use [Firebase\JWT\JWT](https://github.com/firebase/php-jwt);
- This includes and makes the jwt as a valid auth token inside the system
- Helpers: I'm using a helper to make the functionality of the JWT handling: ➡ [App\Helpers\TokenHelper](https://github.com/Quisui/buckhill-challenge/blob/develop/app/Helpers/TokenHelper.php)
- [Guard](https://github.com/Quisui/buckhill-challenge/blob/develop/app/Providers/AuthServiceProvider.php): check **Auth::extend('jwt')**
- [Checks and ensures auth provider](https://github.com/Quisui/buckhill-challenge/blob/develop/app/Services/Api/V1/JWTAuth/JwtGuard.php)

### General Protection - JWT Integration
I've created my own jwt integration that i've been using through the years<br>
➡ Testing: [tests/feature/Api/V1/Middlewares/AuthApiJWTTest](https://github.com/Quisui/buckhill-challenge/blob/develop/tests/Feature/Api/V1/Middlewares/AuthApiJWTTest.php) - 3 tests
➡ Integration:<br>
    1) **Configurations:** [config/auth](https://github.com/Quisui/buckhill-challenge/blob/develop/config/auth.php) ➡ guards -> api = ['driver' => 'jwt',] <br>
    2) **Guard Registration:** [App\Providers\AuthServiceProvider](https://github.com/Quisui/buckhill-challenge/blob/develop/app/Providers/AuthServiceProvider.php) ➡ Auth::extend('jwt') <br>
    3) **Service That Check's Token Validation for guard:** [App\Services\Api\V1\JWTAuth\JwtGuard](https://github.com/Quisui/buckhill-challenge/blob/develop/app/Services/Api/V1/JWTAuth/JwtGuard.php) <br>
    4) **Using Bearer Token:** as *'middleware' => ['auth:api'](https://github.com/Quisui/buckhill-challenge/blob/develop/routes/api.php)*  <br>
    5) Note 🗒️: Remember to send your token: *Bearer* Token <br>
    6) [Config/auth](https://github.com/Quisui/buckhill-challenge/blob/develop/config/auth.php) this is the registration of the guard
        
- [LoginController](https://github.com/Quisui/buckhill-challenge/blob/develop/app/Http/Controllers/Api/V1/Auth/LoginController.php): Based only in a invoke function that attempts a login, saves the token on jwt_tokens, now you can use the token in your middleware<br>
    ➡ Testing was applied to the functionality<br>
        ➡ Feature: [tests/Feature/Api/V1/Auth/LoginControllerTest](https://github.com/Quisui/buckhill-challenge/blob/develop/tests/Feature/Api/V1/Auth/LoginControllerTest.php)<br>
        ➡ Unit: [tests/unit/Api/V1/JWTAppTest](https://github.com/Quisui/buckhill-challenge/blob/develop/tests/Unit/Api/V1/JWTAppTest.php) - ensure the functionality of the helper<br>
- 🌉 Middleware integration (Middleware protection): <br>
    ➡ For **IsAdmin** check: [App/http/middleware/IsAdmin](https://github.com/Quisui/buckhill-challenge/blob/develop/app/Http/Middleware/IsAdmin.php) <br>
        - Testing included: [tests/feature/Api/V1/Middlewares/IsAdminTest](https://github.com/Quisui/buckhill-challenge/blob/develop/tests/Feature/Api/V1/Middlewares/IsAdminTest.php) <br>
    ➡ For IsUser check: App/http/middleware/IsUser <br>
        - this could contain is_marketing as well but just to ensure or to provide more functionality <br>
Finally this is registered in [app/http/kernel](https://github.com/Quisui/buckhill-challenge/blob/develop/app/Http/Kernel.php) -> protected $routeMiddleware 
# Model Relations at this moment: <br>
[User model](https://github.com/Quisui/buckhill-challenge/blob/develop/app/Models/User.php): <br>
    - has many Order models <br>
[JWTToken Model](https://github.com/Quisui/buckhill-challenge/blob/develop/app/Models/JwtToken.php): <br>
    - belongs to User Model <br>
[Order model](https://github.com/Quisui/buckhill-challenge/blob/develop/app/Models/Order.php): <br>
    - belongs to User model <br>
    - belongs to OrderStatus model <br>
    - belongs to Payment model <br>
    - has many Product query through jsonb <br>
[Payment model](https://github.com/Quisui/buckhill-challenge/blob/develop/app/Models/Payment.php): <br>
    - has many Order models <br>
    - belongs to User model <br>
[Product model](https://github.com/Quisui/buckhill-challenge/blob/develop/app/Models/Product.php): <br>
    - belongs to Category model <br>
    - has many Order models through wher In <br>
[Category model](https://github.com/Quisui/buckhill-challenge/blob/develop/app/Models/Category.php): <br>
    - belongs to product <br>
[OrderStatus model](https://github.com/Quisui/buckhill-challenge/blob/develop/app/Models/OrderStatus.php): <br>
    - has many Order models <br>
# [Migrations](https://github.com/Quisui/buckhill-challenge/tree/develop/database/migrations)
- First step we are going to check the migrations following the structure provided on the challenge
![image](https://user-images.githubusercontent.com/22399803/227690277-19cb7291-4dd9-4ee3-b8ae-cfed1835d6b1.png)
- ## **Second** **[Seeders](https://github.com/Quisui/buckhill-challenge/tree/develop/database/seeders)** <br>
  Currently not using all of them: <br>
    $this->call(PaymentSeeder::class); <br>
    $this->call(UserSeeder::class); <br>
    $this->call(CategorySeeder::class); <br>
    $this->call(ProductSeeder::class); <br>
    $this->call(OrderStatusSeeder::class); <br>
    $this->call(OrderSeeder::class); <br>
- ## **Third** **[Factories](https://github.com/Quisui/buckhill-challenge/tree/develop/database/factories)** <br>
    
# UUID
I've used the personal uuid trait: 
### ➡ ["traits/uuid"](https://github.com/Quisui/buckhill-challenge/blob/develop/app/Traits/Uuid.php) 
### 🗒️*Note: Almost all models are using this trait*
- Example: [UserModel](https://github.com/Quisui/buckhill-challenge/blob/develop/app/Models/User.php)
- Inside this file we're going to use the trait HasUuid
- In your model you can specify if... : 
    protected $primaryKey = 'uuid';
    protected $uuidKey = 'uuid';
    public $incrementing = false;
  ... It is Needed.
# Swagger 🍵 
### Swagger has been implemented for some of the endpoints - Just for the features that I did for the challenge 
*I Didn't implement the whole document.*
For further documentation go to your localhost/api/v1/documentation or if you're using the docker container is http://localhost:8085/api/v1/documentation or depending on your port change it. <br>
AUTH TOKEN NEEDS TO BE: **Bearer** "token from login"
