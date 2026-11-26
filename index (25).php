<?php
require_once 'config.php';

function e($value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

$statement = $pdo->query('SELECT id, equipment_name, brand, storage_location, quantity FROM equipment ORDER BY id DESC');
$records = $statement->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Equipment Information System</title>
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
    <p><a class="button" href="create.php">Add New Equipment</a></p>

    <?php if (count($records) === 0): ?>
        <p class="empty">No records found.</p>
    <?php else: ?>
        <div class="table-wrap">
        <table border="1" cellpadding="6">
            <tr>
                <th>ID</th>
                <th>Equipment Name</th>
                <th>Brand</th>
                <th>Storage Location</th>
                <th>Quantity</th>
                <th>Actions</th>
            </tr>
            <?php foreach ($records as $record): ?>
            <tr>
                <td data-label="ID"><?= e($record['id']) ?></td>
                <td data-label="Equipment Name"><?= e($record['equipment_name']) ?></td>
                <td data-label="Brand"><?= e($record['brand']) ?></td>
                <td data-label="Storage Location"><?= e($record['storage_location']) ?></td>
                <td data-label="Quantity"><?= e($record['quantity']) ?></td>
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
