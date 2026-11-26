<?php
// create.php - GET shows an empty form; POST validates then inserts a new rental.
require_once 'config.php';

// e() makes saved text safe to print inside HTML (stops injected tags/scripts).
function e($value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

// True only for a real calendar date written as YYYY-MM-DD.
function is_valid_date(string $value): bool
{
    if (!preg_match('/^[1-9]\d{3}-\d{2}-\d{2}$/D', $value)) {
        return false;
    }
    [$year, $month, $day] = array_map('intval', explode('-', $value));
    return checkdate($month, $day, $year);
}

$labels = [
    'customer_name' => 'Customer Name',
    'vehicle_plate' => 'Vehicle Plate Number',
    'rental_start' => 'Rental Start Date',
    'return_date' => 'Return Date',
];

// Start with empty form values and no errors.
$record_input = [
    'customer_name' => '',
    'vehicle_plate' => '',
    'rental_start' => '',
    'return_date' => '',
];
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. Read and trim every field (anything that is not plain text becomes empty).
    foreach (array_keys($record_input) as $field) {
        $value = $_POST[$field] ?? '';
        $record_input[$field] = is_string($value) ? trim($value) : '';
    }

    // 2. Every field is required. "0" is not empty, so zero stays valid.
    foreach ($labels as $field => $label) {
        if ($record_input[$field] === '') {
            $errors[$field] = "$label is required.";
        }
    }

    // 3. Rules for this case study.
    if (!isset($errors['customer_name']) && mb_strlen($record_input['customer_name']) > 100) {
        $errors['customer_name'] = 'Customer Name must be 100 characters or fewer.';
    }
    if (!isset($errors['vehicle_plate']) && mb_strlen($record_input['vehicle_plate']) > 20) {
        $errors['vehicle_plate'] = 'Vehicle Plate Number must be 20 characters or fewer.';
    }
    // The date must be YYYY-MM-DD and exist on the calendar (rejects 2026-02-30).
    if (!isset($errors['rental_start']) && !is_valid_date($record_input['rental_start'])) {
        $errors['rental_start'] = 'Rental Start Date must be a real date in YYYY-MM-DD format.';
    }
    if (!isset($errors['return_date']) && !is_valid_date($record_input['return_date'])) {
        $errors['return_date'] = 'Return Date must be a real date in YYYY-MM-DD format.';
    }
    // YYYY-MM-DD text sorts in calendar order, so it can be compared directly.
    if (!isset($errors['rental_start']) && !isset($errors['return_date'])
        && $record_input['return_date'] <= $record_input['rental_start']) {
        $errors['return_date'] = 'Return Date must be later than the Rental Start Date.';
    }

    // 4. Save only when there are no errors. MySQL creates the id.
    if (!$errors) {
        $statement = $pdo->prepare(
            'INSERT INTO rentals (customer_name, vehicle_plate, rental_start, return_date)
             VALUES (:customer_name, :vehicle_plate, :rental_start, :return_date)'
        );
        $statement->execute([
            ':customer_name' => $record_input['customer_name'],
            ':vehicle_plate' => $record_input['vehicle_plate'],
            ':rental_start' => $record_input['rental_start'],
            ':return_date' => $record_input['return_date']
        ]);

        // Redirect so refreshing the page does not save twice.
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
    <title>Add Rental</title>
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
    <h2>Add New Rental</h2>
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
            <label for="customer_name">Customer Name</label><br>
            <input type="text" id="customer_name" name="customer_name" maxlength="100" placeholder="e.g. Avery Tan" value="<?= e($record_input['customer_name']) ?>" required>
        </p>
        <p>
            <label for="vehicle_plate">Vehicle Plate Number</label><br>
            <input type="text" id="vehicle_plate" name="vehicle_plate" maxlength="20" placeholder="e.g. ABC-1234" value="<?= e($record_input['vehicle_plate']) ?>" required>
        </p>
        <p>
            <label for="rental_start">Rental Start Date</label><br>
            <input type="date" id="rental_start" name="rental_start" value="<?= e($record_input['rental_start']) ?>" required>
        </p>
        <p>
            <label for="return_date">Return Date</label><br>
            <input type="date" id="return_date" name="return_date" value="<?= e($record_input['return_date']) ?>" required>
        </p>
        <p>
            <button type="submit">Save Rental</button>
            <a href="index.php">Cancel and go back</a>
        </p>
    </form>
</main>
</body>
</html>
