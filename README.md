# Sports project

## Public web directory

Only `public/` should be served by a web server:

```text
public/
  index.php           Application entry point
  assets/css/         Stylesheets
  uploads/sport-images/ Uploaded images
config/
controllers/
core/
db/
models/
repositories/
views/
vendor/
tests/
.env                  Local database settings (never serve this file)
```

Run Composer commands from the project root. The entry point loads Composer and
`.env` from its parent directory. Uploads are saved under
`public/uploads/sport-images/`; the web-server account needs write access there.

### Existing local nginx setup

The application remains at `http://localhost/www/blog-project/`.
`F:/MNP/nginx/conf/nginx.conf` includes `config/nginx-public.conf` inside its
localhost server block. This maps that URL exclusively to `public/`, allows PHP
execution only for the entry point, and blocks dotfiles and other PHP paths.
Other local projects keep their existing configuration.

The snippet contains local absolute paths and the existing FastCGI port `9023`.
Adjust these when moving the project to another machine. Include the snippet
inside a server block, not at the top level of nginx.conf.

For a separate domain, configure its document root as this project's `public/`
directory and permit PHP execution only for `public/index.php`. Remove any other
web-server mapping that exposes the project root. Simply visiting a `/public/`
URL does not protect files accessible through an older project-root mapping.

### Development server

From the project root:

```sh
php -S 127.0.0.1:8000 -t public
```

Open `http://127.0.0.1:8000/`. This server is for local development.

### Checks

```sh
php tests/csrf.php
```

After web-server configuration changes, verify that the home page, styles, and
uploaded images load, and that `.env`, `composer.json`, `controllers/`, `vendor/`,
and PHP files under uploads cannot be requested through the application URL.
