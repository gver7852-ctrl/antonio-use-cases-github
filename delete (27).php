<?php
require_once 'config.php';

function e($value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

$requested_id = $_SERVER['REQUEST_METHOD'] === 'POST' ? ($_POST['id'] ?? '') : ($_GET['id'] ?? '');

$id = filter_var($requested_id ?? '', FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
$record = false;

if ($id !== false) {
    $statement = $pdo->prepare('SELECT * FROM equipment WHERE id = :id');
    $statement->execute([':id' => $id]);
    $record = $statement->fetch();
}

if (!$record) {
    http_response_code(404);
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Record not found</title>
        <link rel="stylesheet" href="../assets/crud.css">
    </head>
    <body class="case-1">
    <header>
        <?php if (file_exists(__DIR__ . '/../profile.php')): ?>
            <a class="portfolio-link" href="../index.php#case-1">&larr; Back to portfolio</a>
        <?php endif; ?>
        <p class="case-no">Case Study 1</p>
        <h1>Equipment Information System</h1>
    </header>
    <main>
        <p class="empty">Record not found.</p>
        <p><a href="index.php">Back to list</a></p>
    </main>
    </body>
    </html>
<?php
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $statement = $pdo->prepare('DELETE FROM equipment WHERE id = :id');
    $statement->execute([':id' => $id]);

    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Delete Equipment</title>
    <link rel="stylesheet" href="../assets/crud.css">
</head>
<body class="case-1">
<header>
    <?php if (file_exists(__DIR__ . '/../profile.php')): ?>
        <a class="portfolio-link" href="../index.php#case-1">&larr; Back to portfolio</a>
    <?php endif; ?>
    <p class="case-no">Case Study 1</p>
    <h1>Equipment Information System</h1>
</header>
<main>
    <h2>Confirm Deletion</h2>
    <p>Are you sure you want to delete this equipment? This cannot be undone.</p>
    <dl>
        <dt>ID</dt>
        <dd><?= e($record['id']) ?></dd>
        <dt>Equipment Name</dt>
        <dd><?= e($record['equipment_name']) ?></dd>
        <dt>Storage Location</dt>
        <dd><?= e($record['storage_location']) ?></dd>
    </dl>
    <form method="post">
        <input type="hidden" name="id" value="<?= e($id) ?>">
        <button type="submit" class="danger">Confirm Delete</button>
        <a href="index.php">Cancel</a>
    </form>
</main>
</body>
</html>
