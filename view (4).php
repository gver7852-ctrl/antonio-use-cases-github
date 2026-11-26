<?php
require_once 'config.php';

function e($value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

$id = filter_var($_GET['id'] ?? '', FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
$record = false;

if ($id !== false) {
    $statement = $pdo->prepare('SELECT * FROM registrations WHERE id = :id');
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
    <body class="case-2">
    <header>
        <?php if (file_exists(__DIR__ . '/../profile.php')): ?>
            <a class="portfolio-link" href="../index.php#case-2">&larr; Back to portfolio</a>
        <?php endif; ?>
        <p class="case-no">Case Study 2</p>
        <h1>Activity Registration Information System</h1>
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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>View Registration</title>
    <link rel="stylesheet" href="../assets/crud.css">
</head>
<body class="case-2">
<header>
    <?php if (file_exists(__DIR__ . '/../profile.php')): ?>
        <a class="portfolio-link" href="../index.php#case-2">&larr; Back to portfolio</a>
    <?php endif; ?>
    <p class="case-no">Case Study 2</p>
    <h1>Activity Registration Information System</h1>
</header>
<main>
    <h2>Registration Details</h2>
    <dl>
        <dt>ID</dt>
        <dd><?= e($record['id']) ?></dd>
        <dt>Registration Number</dt>
        <dd><?= e($record['registration_number']) ?></dd>
        <dt>Participant Name</dt>
        <dd><?= e($record['participant_name']) ?></dd>
        <dt>Activity Name</dt>
        <dd><?= e($record['activity_name']) ?></dd>
        <dt>Event Period</dt>
        <dd><?= e($record['event_period']) ?></dd>
        <dt>Status</dt>
        <dd><?= e($record['status']) ?></dd>
    </dl>
    <p><a href="index.php">Back to list</a></p>
</main>
</body>
</html>
