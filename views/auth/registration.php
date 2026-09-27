<?php
$pageTitle = 'User Registration';
$pageClass = 'page-registration';
$pageStylesheet = 'assets/css/registration.css';
include __DIR__ . '/../layouts/header.php';
?>
<div class="register-container">

    <h2>User Registration</h2>

    <?php if (!empty($error)): ?>

        <div style="color: red; margin-bottom: 15px;">
            <?= htmlspecialchars(
                $error,
                ENT_QUOTES,
                'UTF-8'
            ) ?>
        </div>

    <?php endif; ?>

    <form
        action="index.php?act=registration"
        method="POST"
    >
        <input
            type="hidden"
            name="csrf_token"
            value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
        >

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


        <button type="submit">
            Register
        </button>

    </form>


    <a
        href="index.php?act=home"
        class="home-button"
    >
        ← Back to Home
    </a>

</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
