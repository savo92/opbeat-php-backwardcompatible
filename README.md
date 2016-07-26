Opbeat-php-backwardcompatible
=================

## !!!!!!!!!!!!! WIP !!!!!!!!!!!!! 
## Still in development, please don't use 

> Simple PHP Client for Opbeat. It provides a simple integration that works with any PHP5 versions

_Really thanks to [Mads Jensen](https://github.com/madsleejensen) for his [Opbeat-php](https://github.com/madsleejensen/opbeat-php). It inspires me to develop a more compatible version of the client for my needs._  
_So I publish this client to everyone_
<br/>
<br/>
## Requirements
This client requires only cURL (curl for PHP)

## Installation
1. Clone this repository wherever you prefer (it doesn't use Composer nor namespace)
2. Define constants for Opbeat settings (Organization ID, App ID, Secret Token):  
    * *OPBEATOPT_ORGANIZATION_ID*  
    * *OPBEATOPT_APP_ID*  
    * *OPBEATOPT_SECRET_TOKEN*  
3. Include opbeat.php and you have done
  
*This is the simple version, which automatically use set\_error\_handler and register\_shutdown\_function. In this mode (or, if you manually initialize the client – see below) the client will automatically catch any uncaught error*  
  
There are some advanced stuff you can use:
* You can disable the hooks' automatic setup.
* You can pass to the hook a callable that will be executed at the end of the procedure.

### Disable the hooks' automatic setup
1. Clone the repository 
2. Define the constants as above
3. Include opbeat.php
4. Execute OpbeatInitializer::load with the first parameter as FALSE (boolean) - so it will only check dependencies and settings
5. Use the interfaces provided by the initializer:
    * sendStandardPhpError which receives the same params provided by set\_error\_handler
    * sendPrettyError which receives an error msg, an error level (fatal, error, warning, info, debug), a cleaned trace and an array of info for the http interface (please see the Opbeat Public API documentation)
    * sendException which receives an \\Exception object
*Please, note that if you use sendStandardPhpError or sendException, the client will automatically generates the trace for you.*
    
### Pass a callable
1. Clone the repository 
2. Define the constants as above
3. Include opbeat.php
4. Execute OpbeatInitializer::load with the first parameter as TRUE (boolean). Obviously, if you pass FALSE as first parameter, the callable is useless because you need to declare your own set\_error\_handler and register\_shutdown\_function 
5. Provide a callable as second parameter

## Declare custom set\_error\_handler and register\_shutdown\_function
If you pass FALSE as first parameter to Opbeat::init(), you need to declare your own set\_error\_handler and register\_shutdown\_function and manually send the error to the Opbeat client. This is an advanced stuff, if you are doing it only to do other actions before, please consider to run OpbeatInitializer::errorHandler() or OpbeatInitializer::shutdownHandler().


## DISCLAIMER
As written above, I initially developed this client for my purposes only. After the development I decided to publish this client to help other developers that don't use PHP \>= 5.6. I hope you enjoy my work and contribute.
This client is provided to you with the MIT license, so you can use it as you prefer.