# Paolo Barcelona's Log project

A small log persistence project


## Getting Started

* Execute ./setup.sh and you will be prompted whether to start the worker after the installation or no.
* To start the worker individually run ./start_worker.sh
* To modify to which image to start the worker on, simply replace `legalone-php-1` with the desired image name in ./start_worker.sh

## Docs

### Available Commands
* To import the current contents of the log file located in `/public/logs/logs.log`, simply run the command: `docker-compose exec -t php bin/console log:import-data`

### Available Endpoints

* /logs/count - returns the number of stored logs
* /logs/delete - truncates all log entries that were saved

Further documentation about these endpoints can be found in: https://localhost/api/doc (JSON version https://localhost/api/doc.json).

**Enjoy!**



