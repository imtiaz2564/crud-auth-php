<?php
// Run with: php tests/csrf.php
require dirname(__DIR__) . '/vendor/autoload.php';
$root = dirname(__DIR__);
$cases = [
    ['GET', 'valid', 'valid', 405],
    ['POST', 'valid', null, 403],
    ['POST', 'valid', '', 403],
    ['POST', 'valid', 'wrong', 403],
    ['POST', 'valid', ['valid'], 403],
    ['POST', null, 'valid', 403],
    ['POST', '', '', 403],
    ['POST', ['valid'], 'valid', 403],
];
$count = 0;
function executeCheck(string $script, int $status, bool $shouldContinue = false): void
{
    global $root, $count;
    $prefix = '<?php require ' . var_export($root . '/vendor/autoload.php', true) . ';'
        . 'register_shutdown_function(function () { echo "|STATUS:" . http_response_code(); });';
    $process = proc_open([PHP_BINARY], [['pipe', 'r'], ['pipe', 'w'], ['pipe', 'w']], $pipes, $root);
    if (!is_resource($process)) throw new RuntimeException('Could not start PHP');
    fwrite($pipes[0], $prefix . $script);
    fclose($pipes[0]);
    $output = stream_get_contents($pipes[1]);
    $errors = stream_get_contents($pipes[2]);
    fclose($pipes[1]);
    fclose($pipes[2]);
    $code = proc_close($process);
    if ($code !== 0 || $errors !== '' || !str_contains($output, '|STATUS:' . $status) ||
        str_contains($output, 'CONTINUED') !== $shouldContinue) {
        throw new RuntimeException('Failed check: ' . $output . $errors);
    }
    $count++;
}
foreach (['registration', 'login', 'logout', 'sportInsert', 'updateSport', 'deleteSport'] as $action) {
    foreach ($cases as [$method, $expected, $submitted, $status]) {
        $script = '$_SERVER["REQUEST_METHOD"] = ' . var_export($method, true) . ';'
            . '$_SESSION = ["csrf_token" => ' . var_export($expected, true) . '];'
            . '$_POST = ["csrf_token" => ' . var_export($submitted, true) . ', "id" => 1];'
            . '$_GET = ["act" => ' . var_export($action, true) . '];'
            // No repositories or database: rejection must happen before dispatch.
            . '$router = (new ReflectionClass(Router\Router::class))->newInstanceWithoutConstructor();'
            . '$router->handle(); echo "CONTINUED";';
        executeCheck($script, $status);
    }
}
executeCheck('http_response_code(200); $_SERVER["REQUEST_METHOD"] = "POST";'
    . '$_SESSION = $_POST = ["csrf_token" => "valid"]; Router\Csrf::validatePost(); echo "CONTINUED";', 200, true);
$_SESSION = ['email' => 'test@example.com', 'csrf_token' => 'form-token'];
$sport = new Model\Sport('Test', 'Content', null, 1);
$sports = [$sport];
foreach (['auth/login', 'auth/registration', 'sport/create', 'sport/edit', 'sport/dashboard'] as $view) {
    ob_start();
    include $root . '/views/' . $view . '.php';
    $html = ob_get_clean();
    preg_match_all('~<form\b([^>]*)>(.*?)</form>~s', $html, $forms, PREG_SET_ORDER);
    foreach ($forms as $form) {
        if (!preg_match('~method="POST"~i', $form[1]) ||
            !preg_match('~name="csrf_token"\s+value="form-token"~', $form[2])) {
            throw new RuntimeException('Unprotected form in ' . $view);
        }
        $count++;
    }
}
echo "$count CSRF checks passed without database writes.\n";
