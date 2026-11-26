<?php
// index.php - lists every registration record, newest first.
require_once 'config.php';

// e() makes saved text safe to print inside HTML (stops injected tags/scripts).
function e($value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

$statement = $pdo->query('SELECT id, registration_number, participant_name, activity_name, event_period, status FROM registrations ORDER BY id DESC');
$records = $statement->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Activity Registration Information System</title>
    <!-- Optional styling shared by all five systems. The system still works without it. -->
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
    <p><a class="button" href="create.php">Add New Registration</a></p>

    <?php if (count($records) === 0): ?>
        <p class="empty">No records found.</p>
    <?php else: ?>
        <div class="table-wrap">
        <table border="1" cellpadding="6">
            <tr>
                <th>ID</th>
                <th>Registration Number</th>
                <th>Participant Name</th>
                <th>Activity Name</th>
                <th>Event Period</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
            <?php foreach ($records as $record): ?>
            <tr>
                <td data-label="ID"><?= e($record['id']) ?></td>
                <td data-label="Registration Number"><?= e($record['registration_number']) ?></td>
                <td data-label="Participant Name"><?= e($record['participant_name']) ?></td>
                <td data-label="Activity Name"><?= e($record['activity_name']) ?></td>
                <td data-label="Event Period"><?= e($record['event_period']) ?></td>
                <td data-label="Status"><?= e($record['status']) ?></td>
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
