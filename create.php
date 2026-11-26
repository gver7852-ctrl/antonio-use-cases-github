<?php
require_once 'config.php';

function e($value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

$labels = [
    'employee_number' => 'Employee Number',
    'full_name' => 'Full Name',
    'email' => 'Email Address',
    'department' => 'Department',
    'shift_number' => 'Shift Number',
];

$record_input = [
    'employee_number' => '',
    'full_name' => '',
    'email' => '',
    'department' => '',
    'shift_number' => '',
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

    if (!isset($errors['employee_number']) && mb_strlen($record_input['employee_number']) > 30) {
        $errors['employee_number'] = 'Employee Number must be 30 characters or fewer.';
    }
    if (!isset($errors['full_name']) && mb_strlen($record_input['full_name']) > 100) {
        $errors['full_name'] = 'Full Name must be 100 characters or fewer.';
    }
    if (!isset($errors['email']) && !filter_var($record_input['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Enter a valid email address.';
    }
    if (!isset($errors['email']) && mb_strlen($record_input['email']) > 100) {
        $errors['email'] = 'Email Address must be 100 characters or fewer.';
    }
    if (!isset($errors['department']) && mb_strlen($record_input['department']) > 100) {
        $errors['department'] = 'Department must be 100 characters or fewer.';
    }
    if (!isset($errors['shift_number']) && !in_array($record_input['shift_number'], ['1', '2', '3', '4'], true)) {
        $errors['shift_number'] = 'Shift Number must be 1, 2, 3, or 4.';
    }

    if (!$errors) {
        $statement = $pdo->prepare(
            'INSERT INTO employees (employee_number, full_name, email, department, shift_number)
             VALUES (:employee_number, :full_name, :email, :department, :shift_number)'
        );
        $statement->execute([
            ':employee_number' => $record_input['employee_number'],
            ':full_name' => $record_input['full_name'],
            ':email' => $record_input['email'],
            ':department' => $record_input['department'],
            ':shift_number' => $record_input['shift_number']
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
    <title>Add Employee</title>
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
    <h2>Add New Employee</h2>
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
            <label for="employee_number">Employee Number</label><br>
            <input type="text" id="employee_number" name="employee_number" maxlength="30" placeholder="e.g. EMP-001" value="<?= e($record_input['employee_number']) ?>" required>
        </p>
        <p>
            <label for="full_name">Full Name</label><br>
            <input type="text" id="full_name" name="full_name" maxlength="100" placeholder="e.g. Jordan Garcia" value="<?= e($record_input['full_name']) ?>" required>
        </p>
        <p>
            <label for="email">Email Address</label><br>
            <input type="email" id="email" name="email" maxlength="100" placeholder="e.g. jordan@example.com" value="<?= e($record_input['email']) ?>" required>
        </p>
        <p>
            <label for="department">Department</label><br>
            <input type="text" id="department" name="department" maxlength="100" placeholder="e.g. Customer Support" value="<?= e($record_input['department']) ?>" required>
        </p>
        <p>
            <label for="shift_number">Shift Number</label><br>
            <select id="shift_number" name="shift_number" required>
                <option value="">-- Select --</option>
                <?php foreach (['1', '2', '3', '4'] as $option): ?>
                    <option value="<?= e($option) ?>" <?= $record_input['shift_number'] === $option ? 'selected' : '' ?>><?= e($option) ?></option>
                <?php endforeach; ?>
            </select>
        </p>
        <p>
            <button type="submit">Save Employee</button>
            <a href="index.php">Cancel and go back</a>
        </p>
    </form>
</main>
</body>
</html>
