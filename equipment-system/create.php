<?php
// create.php - GET shows an empty form; POST validates then inserts a new equipment.
require_once 'config.php';

// e() makes saved text safe to print inside HTML (stops injected tags/scripts).
function e($value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

$labels = [
    'equipment_name' => 'Equipment Name',
    'brand' => 'Brand',
    'storage_location' => 'Storage Location',
    'quantity' => 'Quantity',
];

// Start with empty form values and no errors.
$record_input = [
    'equipment_name' => '',
    'brand' => '',
    'storage_location' => '',
    'quantity' => '',
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
    if (!isset($errors['equipment_name']) && mb_strlen($record_input['equipment_name']) > 150) {
        $errors['equipment_name'] = 'Equipment Name must be 150 characters or fewer.';
    }
    if (!isset($errors['brand']) && mb_strlen($record_input['brand']) > 100) {
        $errors['brand'] = 'Brand must be 100 characters or fewer.';
    }
    if (!isset($errors['storage_location']) && mb_strlen($record_input['storage_location']) > 50) {
        $errors['storage_location'] = 'Storage Location must be 50 characters or fewer.';
    }
    // ctype_digit() is true only for digits, so -1 and 1.5 are rejected.
    if (!isset($errors['quantity']) && (!ctype_digit($record_input['quantity']) || (float) $record_input['quantity'] > 2147483647)) {
        $errors['quantity'] = 'Quantity must be a whole number of 0 or more.';
    }

    // 4. Save only when there are no errors. MySQL creates the id.
    if (!$errors) {
        $statement = $pdo->prepare(
            'INSERT INTO equipment (equipment_name, brand, storage_location, quantity)
             VALUES (:equipment_name, :brand, :storage_location, :quantity)'
        );
        $statement->execute([
            ':equipment_name' => $record_input['equipment_name'],
            ':brand' => $record_input['brand'],
            ':storage_location' => $record_input['storage_location'],
            ':quantity' => $record_input['quantity']
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
    <title>Add Equipment</title>
    <!-- Optional styling shared by all five systems. The system still works without it. -->
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
    <h2>Add New Equipment</h2>
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
            <label for="equipment_name">Equipment Name</label><br>
            <input type="text" id="equipment_name" name="equipment_name" maxlength="150" placeholder="e.g. Digital Multimeter" value="<?= e($record_input['equipment_name']) ?>" required>
        </p>
        <p>
            <label for="brand">Brand</label><br>
            <input type="text" id="brand" name="brand" maxlength="100" placeholder="e.g. TestLab" value="<?= e($record_input['brand']) ?>" required>
        </p>
        <p>
            <label for="storage_location">Storage Location</label><br>
            <input type="text" id="storage_location" name="storage_location" maxlength="50" placeholder="e.g. Laboratory 2" value="<?= e($record_input['storage_location']) ?>" required>
        </p>
        <p>
            <label for="quantity">Quantity</label><br>
            <input type="number" id="quantity" name="quantity" min="0" step="1" value="<?= e($record_input['quantity']) ?>" required>
        </p>
        <p>
            <button type="submit">Save Equipment</button>
            <a href="index.php">Cancel and go back</a>
        </p>
    </form>
</main>
</body>
</html>
