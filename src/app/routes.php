<?php
// Add our only route callback
$app->get('/dbs/{db}/tables/{table}', function ($request, $response, $args) {
    // If database name isn't the one we have a connection to, return 400 Bad Request
    if ($args['db'] !== $this->get('settings')['db']['dbname']) {
        return $response->withStatus(400)->write('Wrong database name, pal.');
    }

    $streamer = new Streamer($this->db);
    $streamer->streamTable($args['table']);

    exit;
});