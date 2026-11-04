# Podman test environment

One container with nginx, PHP 8.3 FPM, MySQL 8.0, Composer, Node.js and npm. The project is bind-mounted, so
edits apply immediately and generated files stay owned by the host user. MySQL data is kept in a named volume.
The MySQL port is not published to the host.

Database name and credentials are read from the project `.env`. `DB_USERNAME` must not be `root`, and
`DB_PASSWORD` must not be empty. `APP_URL`, `DB_CONNECTION`, `DB_HOST` and `DB_PORT` are overridden in the
container. Other settings, including `MAIL_*`, come from `.env` as they are.

## First run

```
cd contribs/podman
./starter build
./starter up
./starter install
./starter db-init
```

Open http://localhost:12080/admin. Use `HTTP_PORT=<port> ./starter up` for another port.

`db-init` runs `migrate:fresh --seed`, which destroys the data in the container database and asks for the
admin password.

## Commands

Run `./starter help` for the full list.

```
./starter update          # update Composer, npm, project dependencies and assets
./starter test            # run the test suite
./starter composer <args>
./starter npm <args>
./starter artisan <args>
./starter shell
./starter logs
./starter down            # remove the container, keep the database volume
./starter destroy         # remove the container and the database volume
```

Containerfile changes need `./starter down`, `./starter build` and `./starter up`.
