<?php
$pageTitle = 'Sport Details';
$pageClass = 'page-sport-detail';
$pageStylesheet = 'assets/css/sport-detail.css';
include __DIR__ . '/../layouts/header.php';
?>



<div class="sport-detail-container">

    <article class="sport-card">

        <h1 class="sport-title">
            <?= htmlspecialchars(
                $sport->getTitle(),
                ENT_QUOTES,
                'UTF-8'
            ) ?>
        </h1>

        <?php
        $imageName = basename($sport->getImage() ?? '');
        $hasImage = $imageName !== '' && is_file(dirname(__DIR__, 2) . '/public/uploads/sport-images/' . $imageName);
        ?>
        <?php if ($hasImage): ?>

            <img
                class="sport-image"
                src="uploads/sport-images/<?= htmlspecialchars(
                    rawurlencode($imageName),
                    ENT_QUOTES,
                    'UTF-8'
                ) ?>"
                alt="<?= htmlspecialchars(
                    $sport->getTitle(),
                    ENT_QUOTES,
                    'UTF-8'
                ) ?>"
            >

        <?php else: ?>

            <div class="no-image">
                No image available
            </div>

        <?php endif; ?>

        <div class="sport-content">
            <?= nl2br(
                htmlspecialchars(
                    $sport->getPost(),
                    ENT_QUOTES,
                    'UTF-8'
                )
            ) ?>
        </div>

        <div class="sport-actions">

            <a
                href="index.php?act=sports"
                class="back-button"
            >
                ← Back to Dashboard
            </a>

            <a
                href="index.php?act=updateInfo&id=<?= (int) $sport->getId() ?>"
                class="edit-button"
            >
                Edit Sport
            </a>

        </div>

    </article>

</div>

<?php
include __DIR__ . '/../layouts/footer.php';
?>
