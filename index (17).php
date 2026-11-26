<?php
require_once 'config.php';

function e($value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

$statement = $pdo->query('SELECT id, service_name, category, fee, available_slots FROM services ORDER BY id DESC');
$records = $statement->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Service Information System</title>
    <link rel="stylesheet" href="../assets/crud.css">
</head>
<body class="case-5">
<header>
    <?php if (file_exists(__DIR__ . '/../profile.php')): ?>
        <a class="portfolio-link" href="../index.php#case-5">&larr; Back to portfolio</a>
    <?php endif; ?>
    <p class="case-no">Case Study 5</p>
    <h1>Service Information System</h1>
</header>
<main>
    <p><a class="button" href="create.php">Add New Service</a></p>

    <?php if (count($records) === 0): ?>
        <p class="empty">No records found.</p>
    <?php else: ?>
        <div class="table-wrap">
        <table border="1" cellpadding="6">
            <tr>
                <th>ID</th>
                <th>Service Name</th>
                <th>Category</th>
                <th>Fee</th>
                <th>Available Slots</th>
                <th>Actions</th>
            </tr>
            <?php foreach ($records as $record): ?>
            <tr>
                <td data-label="ID"><?= e($record['id']) ?></td>
                <td data-label="Service Name"><?= e($record['service_name']) ?></td>
                <td data-label="Category"><?= e($record['category']) ?></td>
                <td data-label="Fee"><?= e(number_format((float) $record['fee'], 2)) ?></td>
                <td data-label="Available Slots"><?= e($record['available_slots']) ?></td>
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
