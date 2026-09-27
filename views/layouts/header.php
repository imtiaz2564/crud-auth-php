<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title><?= htmlspecialchars($pageTitle ?? 'My Sports', ENT_QUOTES, 'UTF-8') ?></title>

    <link rel="stylesheet" href="assets/css/layout.css">
    <?php if (!empty($pageStylesheet)): ?>
        <link rel="stylesheet" href="<?= htmlspecialchars($pageStylesheet, ENT_QUOTES, 'UTF-8') ?>">
    <?php endif; ?>

</head>

<body>

<header>

    <div class="logo">
        My Sports
    </div>


    <nav>

        <a href="index.php">
            Home
        </a>


        <?php if (isset($_SESSION['email'])): ?>

            <a href="index.php?act=sports">
                Dashboard
            </a>

            <span>
                Welcome,
                <?= htmlspecialchars(
                    $_SESSION['email'],
                    ENT_QUOTES,
                    'UTF-8'
                ) ?>
            </span>

            <form action="index.php?act=logout" method="POST" class="logout-form">
                <input
                    type="hidden"
                    name="csrf_token"
                    value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                >
                <button type="submit" class="logout-button">Logout</button>
            </form>


        <?php else: ?>

            <a href="index.php?act=loginView">
                Sign In
            </a>

            <a href="index.php?act=registrationView">
                Sign Up
            </a>

        <?php endif; ?>

    </nav>

</header>


<main class="<?= htmlspecialchars($pageClass ?? '', ENT_QUOTES, 'UTF-8') ?>">
