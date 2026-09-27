<?php

namespace Router;

final class Csrf
{
    public static function validatePost(): void
    {
        if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
            http_response_code(405);
            header('Allow: POST');
            exit('This action requires a POST request.');
        }

        $expected = $_SESSION['csrf_token'] ?? null;
        $submitted = $_POST['csrf_token'] ?? null;

        if (!is_string($expected) || $expected === '' ||
            !is_string($submitted) || $submitted === '' ||
            !hash_equals($expected, $submitted)) {
            http_response_code(403);
            exit('Invalid CSRF token. Reload the form and try again.');
        }
    }
}
