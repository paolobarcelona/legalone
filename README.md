# Paolo Barcelona's Log project

A small log persistence project


## Getting Started

Execute setup.sh to setup the docker environment and run migrations.

## Docs

### Available Commands
* To import the current contents of the log file located in `/public/logs/logs.log`, simply run the command: `docker-compose exec -t php bin/console log:import-data`
* This command will also execute the command to consume the messages produced from running the data importer.

### Available Endpoints

* /logs/count - returns the number of stored logs
* /logs/delete - truncates all log entries that were saved

Further documentation about these endpoints can be found in: https://localhost/api/doc (JSON version https://localhost/api/doc.json).

**Enjoy!**



