
Readme
==========

I've tested this on Windows 11 using Docker on WSL.

Getting started
---------------

What you need:
1. Docker

Follow these instructions to get it working:

### 1. Run the setup script

Navigate to the root of the project in your favourite terminal app and run the script
In Bash run the following script:

`./setup.sh`

This should 
1. start the docker containers
2. install all the composer dependencies
3. install all the node dependencies
4. compile the Vue.js code 
5. run database migrations

### 2. Go to the URL

Go to the URL: (http://localhost:8000/notes)

### 3. Running tests

To run the tests run
`docker exec leaptest_web artisan test`
