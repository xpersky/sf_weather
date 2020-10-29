# sf_weather

## Installation
git clone <br>
composer install <br>
yarn install <br>
yarn encore dev <br>
php bin/console doctrine:schema:create <br>
php bin/console server:run ( if not working use first => composer require symfony/web-server-bundle 4.4 ) 
<br>
## Forecast
Simple form, provide city and country to get the temperature forecast. <br>

## Sources
Dynamically add Weather API's for system to forecast <br>

### Supported APIs
OpenWeatherApi (https://openweathermap.org/api) <br>
WeatherBitApi (https://www.weatherbit.io/api) <br>
Any API that returns flat array should work either. <br>

## Logs
Requests are logged automatically, historical data can be accessed under logs section. <br>

