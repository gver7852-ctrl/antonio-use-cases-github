<?php
// index.php - lists every employee record, newest first.
require_once 'config.php';

// e() makes saved text safe to print inside HTML (stops injected tags/scripts).
function e($value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

$statement = $pdo->query('SELECT id, employee_number, full_name, email, department, shift_number FROM employees ORDER BY id DESC');
$records = $statement->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Employee Information System</title>
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
    <p><a class="button" href="create.php">Add New Employee</a></p>

    <?php if (count($records) === 0): ?>
        <p class="empty">No records found.</p>
    <?php else: ?>
        <div class="table-wrap">
        <table border="1" cellpadding="6">
            <tr>
                <th>ID</th>
                <th>Employee Number</th>
                <th>Full Name</th>
                <th>Email Address</th>
                <th>Department</th>
                <th>Shift Number</th>
                <th>Actions</th>
            </tr>
            <?php foreach ($records as $record): ?>
            <tr>
                <td data-label="ID"><?= e($record['id']) ?></td>
                <td data-label="Employee Number"><?= e($record['employee_number']) ?></td>
                <td data-label="Full Name"><?= e($record['full_name']) ?></td>
                <td data-label="Email Address"><?= e($record['email']) ?></td>
                <td data-label="Department"><?= e($record['department']) ?></td>
                <td data-label="Shift Number"><?= e($record['shift_number']) ?></td>
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
