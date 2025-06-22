# Nitea assignment

See ["implementation notes"](./implementation-notes.md) for some notes during implementations.

## How to run

- `docker compose up` to star the API service on port 8080 and the web service on port 8081
- Visit http://localhost:8081 to explore the UI

Note that there is no mysql volume in the docker-compose.yml, which means that on `docker compose down` the database is removed. It will be re-created again from the SQL-file on next `docker compose up` run.
