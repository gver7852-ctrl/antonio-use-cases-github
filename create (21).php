<?php
require_once 'config.php';

function e($value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

$labels = [
    'service_name' => 'Service Name',
    'category' => 'Category',
    'fee' => 'Fee',
    'available_slots' => 'Available Slots',
];

$record_input = [
    'service_name' => '',
    'category' => '',
    'fee' => '',
    'available_slots' => '',
];
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach (array_keys($record_input) as $field) {
        $value = $_POST[$field] ?? '';
        $record_input[$field] = is_string($value) ? trim($value) : '';
    }

    foreach ($labels as $field => $label) {
        if ($record_input[$field] === '') {
            $errors[$field] = "$label is required.";
        }
    }

    if (!isset($errors['service_name']) && mb_strlen($record_input['service_name']) > 100) {
        $errors['service_name'] = 'Service Name must be 100 characters or fewer.';
    }
    if (!isset($errors['category']) && mb_strlen($record_input['category']) > 50) {
        $errors['category'] = 'Category must be 50 characters or fewer.';
    }
    if (!isset($errors['fee']) && (!preg_match('/^(\d+(\.\d{1,2})?|\.\d{1,2})$/D', $record_input['fee']) || (float) $record_input['fee'] > 99999999.99)) {
        $errors['fee'] = 'Fee must be a number from 0 to 99999999.99 with at most two decimal places.';
    }
    if (!isset($errors['available_slots']) && (!ctype_digit($record_input['available_slots']) || (float) $record_input['available_slots'] > 2147483647)) {
        $errors['available_slots'] = 'Available Slots must be a whole number of 0 or more.';
    }

    if (!$errors) {
        $statement = $pdo->prepare(
            'INSERT INTO services (service_name, category, fee, available_slots)
             VALUES (:service_name, :category, :fee, :available_slots)'
        );
        $statement->execute([
            ':service_name' => $record_input['service_name'],
            ':category' => $record_input['category'],
            ':fee' => $record_input['fee'],
            ':available_slots' => $record_input['available_slots']
        ]);

        header('Location: index.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Add Service</title>
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
    <h2>Add New Service</h2>
    <?php if ($errors): ?>
        <div class="errors" role="alert">
            <strong>Please fix the following:</strong>
            <ul>
                <?php foreach ($errors as $message): ?>
                    <li><?= e($message) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="post">
        <p>
            <label for="service_name">Service Name</label><br>
            <input type="text" id="service_name" name="service_name" maxlength="100" placeholder="e.g. Basic Haircut" value="<?= e($record_input['service_name']) ?>" required>
        </p>
        <p>
            <label for="category">Category</label><br>
            <input type="text" id="category" name="category" maxlength="50" placeholder="e.g. Hair Care" value="<?= e($record_input['category']) ?>" required>
        </p>
        <p>
            <label for="fee">Fee</label><br>
            <input type="number" id="fee" name="fee" min="0" step="0.01" value="<?= e($record_input['fee']) ?>" required>
        </p>
        <p>
            <label for="available_slots">Available Slots</label><br>
            <input type="number" id="available_slots" name="available_slots" min="0" step="1" value="<?= e($record_input['available_slots']) ?>" required>
        </p>
        <p>
            <button type="submit">Save Service</button>
            <a href="index.php">Cancel and go back</a>
        </p>
    </form>
</main>
</body>
</html>
