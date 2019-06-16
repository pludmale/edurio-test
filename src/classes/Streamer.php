<?php

use Slim\Http\Response;

class Streamer
{
    /**
     * @var PDO
     */
    protected $db;

    /**
     * Streamer constructor.
     * @param PDO $db
     */
    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    /**
     * Stream rows from $table in CSV format
     *
     * @param string $table
     * @return Response|null
     */
    public function streamTable($table)
    {
        // Check if such table exists, return 400 Bad Request if it doesn't
        //TODO: Sanitise me, maybe?
        $result = $this->db->query("SHOW TABLES LIKE '" . $table . "';");
        if ($result->rowCount() === 0) {
            return (new Response)->withStatus(400)->write('No such table, my friend.');
        }

        // Set necessary headers
        header('Transfer-Encoding: chunked', true);
        header('Content-Encoding: chunked', true);
        header('Content-Type: text/plain', true);
        header('Connection: keep-alive', true);
        header('Cache-Control: no-cache', true);
        header('X-Content-Type-Options: nosniff', true);
        flush();

        // Output the CSV headers first
        $this->outputChunk('a,b,c');

        // Set the batch size and first IDs to be retrieved
        $batchSize = 1000;
        $nextFirstId = 1;
        $nextLastId = 1000;

        // Query the DB 1000 times for a 1000 rows
        for ($i = 0; $i < $batchSize; $i++) {
            $statement = $this->db->prepare("SELECT id, a, b, c FROM source WHERE id BETWEEN ? AND ? ORDER BY id");
            $statement->execute([$nextFirstId, $nextLastId]);

            // Output each row as chunk
            foreach ($statement->fetchAll() as $rowData) {
                $currentId = $rowData['id'];
                //We don't want to output id column
                unset($rowData['id']);
                $this->outputChunk(implode(',', array_values($rowData)));
            }

            // Increment next ID range to retrieve
            $nextLastId = $currentId + $batchSize;
            $nextFirstId = ++$currentId;
        }

        // Output terminating chunk
        $this->outputChunk('');

        return null;
    }

    /**
     * Formats string like a chunk.
     *
     * @param string $chunkContent
     * @return string
     */
    private function formatChunk($chunkContent): string
    {
        $chunk = dechex(strlen($chunkContent)) . Response::EOL;
        $chunk .= $chunkContent;
        $chunk .= Response::EOL;

        return sprintf($chunk);
    }

    /**
     * Outputs a string, formatted as a chunk.
     *
     * @param string $rawChunk
     */
    private function outputChunk($rawChunk)
    {
        ob_start();
        echo $this->formatChunk($rawChunk);
        ob_end_flush();
		flush();
    }
}