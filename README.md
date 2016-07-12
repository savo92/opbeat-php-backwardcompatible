Opbeat-php-backwardcompatible
=================

## !!!!!!!!!!!!! WIP !!!!!!!!!!!!! 
## Still in development, please don't use 

> Simple PHP Client for Opbeat. It provides a simple integration that works with any PHP5 versions

_Really thanks to [Mads Jensen](https://github.com/madsleejensen) for his [Opbeat-php](https://github.com/madsleejensen/opbeat-php). It inspires me to develop a more compatible version of the client for my needs._  
_So I publish this client to everyone (if you have questions, see the disclaimer at the end of the README and then open an issue - thanks)_
<br/>
<br/>
<br/>
## Requirements
This client requires only cURL (libcurl for PHP)

## Installation
1. Clone this repository wherever you prefer (it doesn't use Composer nor namespace)
2. Define constants for Opbeat settings (Organization ID, App ID, Secret Token):  
    * *OPBEAT_ORGANIZATION_ID*  
    * *OPBEAT_APP_ID*  
    * *OPBEAT_SECRET_TOKEN*  
3. Include opbeat.php and you have done
  
*This is the simple version, which automatically use set\_error\_handler and register\_shutdown\_function*  
  
There are some advanced stuff you can use:
* You can disable the hooks' automatic setup.
* You can pass to the hook a callable that will be executed at the end of the procedure.

### Disable the hooks' automatic setup
If you want to declare custom set\_error\_handler or register\_shutdown\_function, you must not include the opbeat.php (basically, it's 
an "autoloader file").  
1. After the clone, define the constants as above
2. Include Opbeat/init.php
3. Invoke OpbeatInitializer::load with the first parameter as FALSE (boolean) - it will check dependencies and settings
4. Use the interfaces provided by the initializer:
    * sendStandardPhpError which receives the same params provided by set\_error\_handler
    * sendPrettyError which receives an error msg, an error level (fatal, error, debug, notice) and a cleaned trace (please see the 
    Opbeat Public API documentation)
    * sendException which receives an \\Exception object

*Please, note that if you use sendStandardPhpError or sendException, the client will automatically generates the trace for you.*


## DISCLAIMER
As written above, I initially developed this client for my purposes only. After the development I decided to publish this client to help 
other developers that don't use PHP \>= 5.6. I hope you enjoy my work and contribute.
This client is provided to you with the MIT license, so you can use it as you prefer.
Maybe I will update this client if any error/incompatibility occurs.
REALLY IMPORTANT: THIS CLIENT DOESN'T USE COMPOSER NOR NAMESPACES.  
*IF YOU WANT A BETTER VERSION WITH NAMESPACES OR OTHER INTERESTING THINGS, PLEASE CONSIDER TO FORK IT.*  
*MAYBE, IN THE FUTURE, OPBEAT WILL RELEASE A OFFICIAL CLIENT - SO THIS PROJECT WILL BECOME USELESS (SURE, IF THEY'LL SUPPORT PHP \<= 5.5). 
THIS IS ANOTHER REASON THAT STOPS ME TO IMPROVE THIS PROJECT. *
