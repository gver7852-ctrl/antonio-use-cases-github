<?php
// view.php - shows all fields of one rental selected by ?id=
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
    $statement = $pdo->prepare('SELECT * FROM rentals WHERE id = :id');
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
    <body class="case-4">
    <header>
        <?php if (file_exists(__DIR__ . '/../profile.php')): ?>
            <a class="portfolio-link" href="../index.php#case-4">&larr; Back to portfolio</a>
        <?php endif; ?>
        <p class="case-no">Case Study 4</p>
        <h1>Vehicle Rental Information System</h1>
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
    <title>View Rental</title>
    <!-- Optional styling shared by all five systems. The system still works without it. -->
    <link rel="stylesheet" href="../assets/crud.css">
</head>
<body class="case-4">
<header>
    <?php if (file_exists(__DIR__ . '/../profile.php')): ?>
        <a class="portfolio-link" href="../index.php#case-4">&larr; Back to portfolio</a>
    <?php endif; ?>
    <p class="case-no">Case Study 4</p>
    <h1>Vehicle Rental Information System</h1>
</header>
<main>
    <h2>Rental Details</h2>
    <dl>
        <dt>ID</dt>
        <dd><?= e($record['id']) ?></dd>
        <dt>Customer Name</dt>
        <dd><?= e($record['customer_name']) ?></dd>
        <dt>Vehicle Plate Number</dt>
        <dd><?= e($record['vehicle_plate']) ?></dd>
        <dt>Rental Start Date</dt>
        <dd><?= e($record['rental_start']) ?></dd>
        <dt>Return Date</dt>
        <dd><?= e($record['return_date']) ?></dd>
    </dl>
    <p><a href="index.php">Back to list</a></p>
</main>
</body>
</html>
