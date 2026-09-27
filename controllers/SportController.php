<?php

namespace Controller;

use Model\Sport;
use Repository\Interfaces\SportRepositoryInterface;



class SportController
{
    private SportRepositoryInterface $sportRepo;
    
    public function __construct(SportRepositoryInterface $sportRepo)
    {
        $this->sportRepo = $sportRepo;

    }

    public function sportInsertView()
    {
       include __DIR__ . '/../views/sport/create.php';
    }

    public function sportInsert()
    {
        $title = $_POST['title'] ?? '';
        $post = $_POST['post'] ?? '';
        $image = null;

        $upload = $_FILES['image'] ?? null;
        if ($upload !== null && ($upload['error'] ?? null) !== UPLOAD_ERR_NO_FILE) {
            $error = null;
            if (($upload['error'] ?? null) !== UPLOAD_ERR_OK) {
                $error = 'Image upload failed. Please select the image again and check its size.';
            } else {
                $temporaryPath = $upload['tmp_name'];
                $imageInfo = is_uploaded_file($temporaryPath) ? @getimagesize($temporaryPath) : false;
                $extensions = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];
                $extension = $extensions[$imageInfo['mime'] ?? ''] ?? null;

                if ($extension === null) {
                    $error = 'Please upload a valid JPEG, PNG, or WebP image.';
                } else {
                    $directory = dirname(__DIR__) . '/public/uploads/sport-images';
                    $image = bin2hex(random_bytes(16)) . '.' . $extension;
                    if (!is_dir($directory) && !@mkdir($directory, 0755, true) && !is_dir($directory)) {
                        $error = 'The image could not be saved. Please try again.';
                    } elseif (!move_uploaded_file($temporaryPath, $directory . '/' . $image)) {
                        $error = 'The image could not be saved. Please try again.';
                    }
                }
            }

            if ($error !== null) {
                include __DIR__ . '/../views/sport/create.php';
                return;
            }
        }

        $sport = new Sport(
            $title,
            $post,
            $image
        );
        $this->sportRepo->createSport($sport);
        $this->getSportList();

    }
    
    public function getSportList()
    {
        if (!isset($_SESSION['email'])) {
            header("Location: index.php?act=loginView");
            exit;
        }
        $result = $this->sportRepo->getData(0);

        $sports = [];

        while ($row = $result->fetch_assoc()) {
            
            $sport = new Sport(
                $row['title'],
                $row['post'],
                $row['image'],
                $row['id']
            );

            $sports[] = $sport;
        }
        include __DIR__ . '/../views/sport/dashboard.php';
        
    }

    public function sportDelete(int $id)
    {

        $result = $this->sportRepo->delete($id);

        if ($result) {
            header('Location: index.php?act=sports');
            exit;
        }

        echo 'Sport could not be deleted.';
    }

    public function editSport(int $id)
    {
        $result = $this->sportRepo->getData($id);

        $row = $result->fetch_assoc();

        if (!$row) {
            die("Sport not found");
        }

        $sport = new Sport(
            $row['title'],
            $row['post'],
            $row['image'],
            $row['id']
        );

        include __DIR__ . '/../views/sport/edit.php';
    }

    public function updateSport()
    {
        $id = (int) ($_POST['id'] ?? 0);
        $title = $_POST['title'] ?? '';
        $post = $_POST['post'] ?? '';


        if ($id <= 0 || $title === '' || $post === '') {
            die("Invalid sport data");
        }

        $row = $this->sportRepo->getData($id)->fetch_assoc();
        if (!$row) {
            die('Sport not found.');
        }
        $image = $row['image'];

        $upload = $_FILES['image'] ?? null;
        if ($upload !== null && ($upload['error'] ?? null) !== UPLOAD_ERR_NO_FILE) {
            $error = null;
            if (($upload['error'] ?? null) !== UPLOAD_ERR_OK) {
                $error = 'Image upload failed. Please select the image again and check its size.';
            } else {
                $temporaryPath = $upload['tmp_name'];
                $imageInfo = is_uploaded_file($temporaryPath) ? @getimagesize($temporaryPath) : false;
                $extensions = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];
                $extension = $extensions[$imageInfo['mime'] ?? ''] ?? null;

                if ($extension === null) {
                    $error = 'Please upload a valid JPEG, PNG, or WebP image.';
                } else {
                    $directory = dirname(__DIR__) . '/public/uploads/sport-images';
                    $image = bin2hex(random_bytes(16)) . '.' . $extension;
                    if (!is_dir($directory) && !@mkdir($directory, 0755, true) && !is_dir($directory)) {
                        $error = 'The image could not be saved. Please try again.';
                    } elseif (!move_uploaded_file($temporaryPath, $directory . '/' . $image)) {
                        $error = 'The image could not be saved. Please try again.';
                    }
                }
            }

            if ($error !== null) {
                $sport = new Sport($title, $post, $row['image'], $id);
                include __DIR__ . '/../views/sport/edit.php';
                return;
            }
        }

        $sport = new Sport(
            $title,
            $post,
            $image,
            $id
        );

        $result = $this->sportRepo->updateSport($sport);

        if ($result) {
            header("Location: index.php?act=sports");
            exit;
        }

        echo "Sport update failed.";
    }

    public function viewSport(int $id)
    {
        $result = $this->sportRepo->getData($id);

        $row = $result->fetch_assoc();

        if (!$row) {
            die('Sport not found.');
        }

        $sport = new Sport(
            $row['title'],
            $row['post'],
            $row['image'],
            $row['id']
        );

        include __DIR__ . '/../views/sport/detail.php';
    }

}

?>
