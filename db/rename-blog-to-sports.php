<?php
// Run once from the project root: php db/rename-blog-to-sports.php
require dirname(__DIR__) . '/vendor/autoload.php';

Dotenv\Dotenv::createImmutable(dirname(__DIR__))->load();
$config = new DatabaseConfig\DatabaseConfig();
$connection = new mysqli($config->host, $config->user, $config->password, $config->db);
$tables = array_column($connection->query('SHOW TABLES')->fetch_all(), 0);

if (in_array('blog', $tables, true) && !in_array('sports', $tables, true)) {
    $before = $connection->query('SELECT COUNT(*) FROM `blog`')->fetch_row()[0];
    $connection->query('RENAME TABLE `blog` TO `sports`');
    $after = $connection->query('SELECT COUNT(*) FROM `sports`')->fetch_row()[0];
    if ($before !== $after) {
        throw new RuntimeException('Record count differs after renaming the table.');
    }
    echo "Renamed blog to sports; preserved $after records.\n";
} elseif (in_array('sports', $tables, true) && !in_array('blog', $tables, true)) {
    echo "The sports table already exists.\n";
} else {
    throw new RuntimeException('Expected only one of the blog or sports tables; no changes made.');
}

$connection->close();
