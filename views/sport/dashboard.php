<?php

// sport-dashboard.php

// Expected:
// $sports = array of Sport objects
// $_SESSION['csrf_token']

$pageTitle = 'Sports Dashboard';
$pageClass = 'page-sport-dashboard';
$pageStylesheet = 'assets/css/sport-dashboard.css';
include __DIR__ . '/../layouts/header.php';


/*
|--------------------------------------------------------------------------
| Pagination
|--------------------------------------------------------------------------
*/

$page = (int) ($_GET['page'] ?? 1);

if ($page < 1) {
    $page = 1;
}

$limit = 5;

$totalSports = count($sports);

$totalPages = (int) ceil($totalSports / $limit);

// Prevent invalid page numbers
if ($totalPages > 0 && $page > $totalPages) {
    $page = $totalPages;
}

$offset = ($page - 1) * $limit;

// Get only the sports for the current page
$sportsForPage = array_slice(
    $sports,
    $offset,
    $limit
);

?>





<div class="container">


    <!-- Dashboard Header -->

    <div class="dashboard-header">

        <h2>
            Sports Dashboard
        </h2>

        <a
            href="index.php?act=sportInsertView"
            class="add-button"
        >
            + Add New Sport
        </a>

    </div>


    <!-- Sport Table -->

    <div class="table-container">


        <?php if (!empty($sportsForPage)): ?>


            <table>

                <thead>

                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Content</th>
                        <th>Actions</th>
                    </tr>

                </thead>


                <tbody>


                <?php foreach ($sportsForPage as $sport): ?>


                    <tr>


                        <!-- ID -->

                        <td>

                            <?= htmlspecialchars(
                                $sport->getId(),
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>

                        </td>


                        <!-- Title -->

                        <td>

                            <?= htmlspecialchars(
                                $sport->getTitle(),
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>

                        </td>


                        <!-- Content -->

                        <td>

                            <?= htmlspecialchars(
                                $sport->getPost(),
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>

                        </td>


                        <!-- Actions -->

                        <td>

                            <div class="actions">


                                <!-- View -->

                                <a
                                    href="index.php?act=viewDetail&id=<?= urlencode(
                                        $sport->getId()
                                    ) ?>"
                                    class="action-button view-button"
                                >
                                    View
                                </a>


                                <!-- Edit -->

                                <a
                                    href="index.php?act=updateInfo&id=<?= urlencode(
                                        $sport->getId()
                                    ) ?>"
                                    class="action-button edit-button"
                                >
                                    Edit
                                </a>


                                <!-- Delete -->

                                <form
                                    action="index.php?act=deleteSport"
                                    method="POST"
                                    class="delete-form"
                                >

                                    <input
                                        type="hidden"
                                        name="id"
                                        value="<?= (int) $sport->getId() ?>"
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


                                    <button
                                        type="submit"
                                        class="action-button delete-button"
                                        onclick="return confirm(
                                            'Are you sure you want to delete this sport?'
                                        )"
                                    >
                                        Delete
                                    </button>


                                </form>


                            </div>

                        </td>


                    </tr>


                <?php endforeach; ?>


                </tbody>

            </table>


        <?php else: ?>


            <div class="empty-message">

                No sport posts found.

            </div>


        <?php endif; ?>


    </div>


    <!-- Pagination -->

    <?php if ($totalPages > 1): ?>


        <div class="pagination">


            <!-- Previous -->

            <?php if ($page > 1): ?>

                <a
                    href="index.php?act=sports&page=<?= $page - 1 ?>"
                >
                    Previous
                </a>

            <?php endif; ?>


            <!-- Page Numbers -->

            <?php for ($i = 1; $i <= $totalPages; $i++): ?>

                <a
                    href="index.php?act=sports&page=<?= $i ?>"
                    class="<?= $i === $page ? 'active' : '' ?>"
                >
                    <?= $i ?>
                </a>

            <?php endfor; ?>


            <!-- Next -->

            <?php if ($page < $totalPages): ?>

                <a
                    href="index.php?act=sports&page=<?= $page + 1 ?>"
                >
                    Next
                </a>

            <?php endif; ?>


        </div>


    <?php endif; ?>


</div>


<?php

include __DIR__ . '/../layouts/footer.php';

?>