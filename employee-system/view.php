<?php
// view.php - shows all fields of one employee selected by ?id=
require_once 'config.php';

// e() makes saved text safe to print inside HTML (stops injected tags/scripts).
function e($value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

// Accept only a positive whole number as the ID.
$id = filter_var($_GET['id'] ?? '', FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
$record = false;

if ($id !== false) {
    $statement = $pdo->prepare('SELECT * FROM employees WHERE id = :id');
    $statement->execute([':id' => $id]);
    $record = $statement->fetch();
}

// Stop here if the ID is invalid or no such record exists.
if (!$record) {
    http_response_code(404);
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Record not found</title>
        <!-- Optional styling shared by all five systems. The system still works without it. -->
        <link rel="stylesheet" href="../assets/crud.css">
    </head>
    <body class="case-3">
    <header>
        <?php if (file_exists(__DIR__ . '/../profile.php')): ?>
            <a class="portfolio-link" href="../index.php#case-3">&larr; Back to portfolio</a>
        <?php endif; ?>
        <p class="case-no">Case Study 3</p>
        <h1>Employee Information System</h1>
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
    <title>View Employee</title>
    <!-- Optional styling shared by all five systems. The system still works without it. -->
    <link rel="stylesheet" href="../assets/crud.css">
</head>
<body class="case-3">
<header>
    <?php if (file_exists(__DIR__ . '/../profile.php')): ?>
        <a class="portfolio-link" href="../index.php#case-3">&larr; Back to portfolio</a>
    <?php endif; ?>
    <p class="case-no">Case Study 3</p>
    <h1>Employee Information System</h1>
</header>
<main>
    <h2>Employee Details</h2>
    <dl>
        <dt>ID</dt>
        <dd><?= e($record['id']) ?></dd>
        <dt>Employee Number</dt>
        <dd><?= e($record['employee_number']) ?></dd>
        <dt>Full Name</dt>
        <dd><?= e($record['full_name']) ?></dd>
        <dt>Email Address</dt>
        <dd><?= e($record['email']) ?></dd>
        <dt>Department</dt>
        <dd><?= e($record['department']) ?></dd>
        <dt>Shift Number</dt>
        <dd><?= e($record['shift_number']) ?></dd>
    </dl>
    <p><a href="index.php">Back to list</a></p>
</main>
</body>
</html>
