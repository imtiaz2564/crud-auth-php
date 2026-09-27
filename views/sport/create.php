<?php
$pageTitle = 'Create Sport Post';
$pageClass = 'page-sport-create';
$pageStylesheet = 'assets/css/sport-create.css';
include __DIR__ . '/../layouts/header.php';
?>

    

<div class="sport-container">

    <h2>Create New Sport Post</h2>

    <?php if (!empty($error)): ?>
        <p role="alert"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p>
    <?php endif; ?>

    <form
        action="index.php?act=sportInsert"
        method="POST"
        enctype="multipart/form-data"
    >

        <!-- CSRF Token -->

        <input
            type="hidden"
            name="csrf_token"
            value="<?= htmlspecialchars(
                $_SESSION['csrf_token'] ?? '',
                ENT_QUOTES,
                'UTF-8'
            ) ?>"
        >


        <!-- Title -->

        <div class="form-group">

            <label for="title">
                Title
            </label>

            <input
                type="text"
                id="title"
                name="title"
                placeholder="Enter sport title"
                value="<?= htmlspecialchars($title ?? '', ENT_QUOTES, 'UTF-8') ?>"
                required
            >

        </div>


        <!-- Post -->

        <div class="form-group">

            <label for="post">
                Post
            </label>

            <textarea
                id="post"
                name="post"
                placeholder="Write your sport post..."
                required
            ><?= htmlspecialchars($post ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>

        </div>


        <!-- Image -->

        <div class="form-group">

            <label for="image">
                Image
            </label>

            <input
                type="file"
                id="image"
                name="image"
                accept="image/jpeg,image/png,image/webp"
            >

        </div>


        <!-- Submit -->

        <button
            type="submit"
            class="submit-button"
        >
            Create Post
        </button>

    </form>


    <!-- Back -->

    <a
        href="index.php?act=sports"
        class="back-button"
    >
        ← Back to Dashboard
    </a>

</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
