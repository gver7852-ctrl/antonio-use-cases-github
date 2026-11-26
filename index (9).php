<?php
require_once 'config.php';

function e($value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

$statement = $pdo->query('SELECT id, customer_name, vehicle_plate, rental_start, return_date FROM rentals ORDER BY id DESC');
$records = $statement->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Vehicle Rental Information System</title>
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
    <p><a class="button" href="create.php">Add New Rental</a></p>

    <?php if (count($records) === 0): ?>
        <p class="empty">No records found.</p>
    <?php else: ?>
        <div class="table-wrap">
        <table border="1" cellpadding="6">
            <tr>
                <th>ID</th>
                <th>Customer Name</th>
                <th>Vehicle Plate Number</th>
                <th>Rental Start Date</th>
                <th>Return Date</th>
                <th>Actions</th>
            </tr>
            <?php foreach ($records as $record): ?>
            <tr>
                <td data-label="ID"><?= e($record['id']) ?></td>
                <td data-label="Customer Name"><?= e($record['customer_name']) ?></td>
                <td data-label="Vehicle Plate Number"><?= e($record['vehicle_plate']) ?></td>
                <td data-label="Rental Start Date"><?= e($record['rental_start']) ?></td>
                <td data-label="Return Date"><?= e($record['return_date']) ?></td>
                <td class="actions">
                    <a href="view.php?id=<?= e($record['id']) ?>">View</a>
                    <a href="edit.php?id=<?= e($record['id']) ?>">Edit</a>
                    <a class="danger" href="delete.php?id=<?= e($record['id']) ?>">Delete</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        </div>
    <?php endif; ?>
</main>
</body>
</html>
