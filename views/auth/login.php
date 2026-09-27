<?php
$pageTitle = 'Sign In';
$pageClass = 'page-login';
$pageStylesheet = 'assets/css/login.css';
include __DIR__ . '/../layouts/header.php';
?>
<div class="login-container">

    <h2>Sign In</h2>

    <p class="subtitle">
        Please sign in to your account
    </p>

    <?php if (!empty($error)): ?>

        <div class="error">
            <?= htmlspecialchars(
                $error,
                ENT_QUOTES,
                'UTF-8'
            ) ?>
        </div>

    <?php endif; ?>


    <form
        action="index.php?act=login"
        method="POST"
    >

        <!-- CSRF Protection -->
        <input
            type="hidden"
            name="csrf_token"
            value="<?= htmlspecialchars(
                $_SESSION['csrf_token'] ?? '',
                ENT_QUOTES,
                'UTF-8'
            ) ?>"
        >


        <!-- Username -->
        <div class="form-group">

            <label for="email">
                Email
            </label>

            <input
                type="text"
                id="email"
                name="email"
                required
            >

        </div>


        <!-- Password -->
        <div class="form-group">

            <label for="password">
                Password
            </label>

            <input
                type="password"
                id="password"
                name="password"
                required
            >

        </div>


        <button
            type="submit"
            class="login-button"
        >
            Sign In
        </button>

    </form>


    <div class="register-link">

        Don't have an account?

        <a href="index.php?act=registrationView">
            Sign Up
        </a>

    </div>


    <div class="back-link">

        <a href="index.php?act=home">
            ← Back to Home
        </a>

    </div>

</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
