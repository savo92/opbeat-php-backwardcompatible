Opbeat-php-backwardcompatible
=================

## !!!!!!!!!!!!! WIP !!!!!!!!!!!!! 
## Still in development, please don't use  

[![Build Status](https://travis-ci.org/savo92/opbeat-php-backwardcompatible.svg?branch=master)](https://travis-ci.org/savo92/opbeat-php-backwardcompatible)  
  
> Simple PHP Client for Opbeat. It provides a simple integration that works with PHP \>= 5.3

_Really thanks to [Mads Jensen](https://github.com/madsleejensen) for his [Opbeat-php](https://github.com/madsleejensen/opbeat-php). It inspires me to develop a more compatible version of the client for my needs._  
  
  
## Requirements
This client requires only cURL (curl for PHP)

## Installation
1. Clone this repository wherever you prefer (it doesn't use Composer nor namespace)
2. Define constants for Opbeat settings (Organization ID, App ID, Secret Token):  
    * **OPBEATOPT\_ORGANIZATION\_ID**.  
    * **OPBEATOPT\_APP\_ID**.
    * **OPBEATOPT\_SECRET\_TOKEN**.
    * OPBEATOPT\_PROJECT\_ABS\_PATH (optional, used to transform the file name from absolute to a version that Opbeat can use to present the its advanced stack trace - if you have already set the Git integration on Opbeat).
3. Include opbeat.php and you have done
  
*This is the simple version, which automatically use set\_error\_handler, set\_exception\_handler and register\_shutdown\_function. In this mode, the client will automatically catch any uncaught error*  

## What you can do
_There are some advanced stuff you can directly use. Please consider that you MUST follow the [Opbeat API documentation](https://opbeat.com/docs/api/intake/v1/#-error-logging-). In the wiki you can find a complete documentation of what this client can do and how to use it._  
  
A little list:
* You can pass to the hook a callable that will be executed at the end of the procedure.
* You can pass a callable or an array to the loader to generate the 'user' node of the array that will be sent to Opbeat.
* You can send an Exception, so you can use the client as logger to your custom error handling.
* You can declare your own set\_error\_handler, set\_exception\_handler and register\_shutdown\_function.
* You can use some utility functions to be more conform to the Opbeat API.
  

## DISCLAIMER
As written above, I initially developed this client for my purposes only. After the development I decided to publish this client to help other developers that don't use PHP \>= 5.6 in legacy applications. I hope you enjoy my work and contribute.
This client is provided to you with the MIT license, so you can use it as you prefer.