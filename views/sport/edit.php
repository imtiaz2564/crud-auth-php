<?php
$pageTitle = 'Edit Sport';
$pageClass = 'page-sport-edit';
$pageStylesheet = 'assets/css/sport-edit.css';
include __DIR__ . '/../layouts/header.php';
?>
<div class="container">

    <div class="edit-card">

        <h2>Edit Sport</h2>

        <?php if (!empty($error)): ?>
            <p role="alert"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p>
        <?php endif; ?>

        <?php
        $imageName = basename($sport->getImage() ?? '');
        $hasImage = $imageName !== '' && is_file(dirname(__DIR__, 2) . '/public/uploads/sport-images/' . $imageName);
        ?>
        <?php if ($hasImage): ?>
            <img
                class="sport-image"
                src="uploads/sport-images/<?= htmlspecialchars(rawurlencode($imageName), ENT_QUOTES, 'UTF-8') ?>"
                alt="<?= htmlspecialchars($sport->getTitle(), ENT_QUOTES, 'UTF-8') ?>"
            >
        <?php else: ?>
            <p class="no-image">No image available</p>
        <?php endif; ?>

        <form
            action="index.php?act=updateSport"
            method="POST"
            enctype="multipart/form-data"
        >

            <!-- Sport ID -->

            <input
                type="hidden"
                name="id"
                value="<?= (int) $sport->getId() ?>"
            >


            <!-- CSRF -->

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
                    value="<?= htmlspecialchars(
                        $sport->getTitle(),
                        ENT_QUOTES,
                        'UTF-8'
                    ) ?>"
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
                    required
                ><?= htmlspecialchars(
                    $sport->getPost(),
                    ENT_QUOTES,
                    'UTF-8'
                ) ?></textarea>

            </div>


            <!-- Buttons -->

            <div class="form-group">
                <label for="image">Replace image</label>
                <input
                    type="file"
                    id="image"
                    name="image"
                    accept="image/jpeg,image/png,image/webp"
                    aria-describedby="image-help"
                >
                <p id="image-help">Choose a JPEG, PNG, or WebP image. Leave empty to keep the current image.</p>
            </div>

            <div class="button-container">

                <button
                    type="submit"
                    class="update-button"
                >
                    Update Sport
                </button>

                <a
                    href="index.php?act=sports"
                    class="cancel-button"
                >
                    Cancel
                </a>

            </div>

        </form>

    </div>

</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
