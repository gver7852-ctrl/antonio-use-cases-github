<?php
// edit.php - GET shows the saved values; POST validates then updates only that ID.
require_once 'config.php';

// e() makes saved text safe to print inside HTML (stops injected tags/scripts).
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

// The ID comes from the URL on GET and from the hidden input on POST.
$requested_id = $_SERVER['REQUEST_METHOD'] === 'POST' ? ($_POST['id'] ?? '') : ($_GET['id'] ?? '');

// Accept only a positive whole number as the ID.
$id = filter_var($requested_id ?? '', FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
$record = false;

if ($id !== false) {
    $statement = $pdo->prepare('SELECT * FROM services WHERE id = :id');
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
    <body class="case-5">
    <header>
        <?php if (file_exists(__DIR__ . '/../profile.php')): ?>
            <a class="portfolio-link" href="../index.php#case-5">&larr; Back to portfolio</a>
        <?php endif; ?>
        <p class="case-no">Case Study 5</p>
        <h1>Service Information System</h1>
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

// Fill the form with the saved values.
$record_input = [
    'service_name' => (string) $record['service_name'],
    'category' => (string) $record['category'],
    'fee' => (string) $record['fee'],
    'available_slots' => (string) $record['available_slots'],
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
    if (!isset($errors['service_name']) && mb_strlen($record_input['service_name']) > 100) {
        $errors['service_name'] = 'Service Name must be 100 characters or fewer.';
    }
    if (!isset($errors['category']) && mb_strlen($record_input['category']) > 50) {
        $errors['category'] = 'Category must be 50 characters or fewer.';
    }
    // Digits with an optional point and 1 or 2 decimals. Rejects -5 and 12.345.
    if (!isset($errors['fee']) && (!preg_match('/^(\d+(\.\d{1,2})?|\.\d{1,2})$/D', $record_input['fee']) || (float) $record_input['fee'] > 99999999.99)) {
        $errors['fee'] = 'Fee must be a number from 0 to 99999999.99 with at most two decimal places.';
    }
    // ctype_digit() is true only for digits, so -1 and 1.5 are rejected.
    if (!isset($errors['available_slots']) && (!ctype_digit($record_input['available_slots']) || (float) $record_input['available_slots'] > 2147483647)) {
        $errors['available_slots'] = 'Available Slots must be a whole number of 0 or more.';
    }

    // 4. Update only the selected row (WHERE id = :id).
    if (!$errors) {
        $statement = $pdo->prepare(
            'UPDATE services
             SET service_name = :service_name,
                 category = :category,
                 fee = :fee,
                 available_slots = :available_slots
             WHERE id = :id'
        );
        $statement->execute([
            ':service_name' => $record_input['service_name'],
            ':category' => $record_input['category'],
            ':fee' => $record_input['fee'],
            ':available_slots' => $record_input['available_slots'],
            ':id' => $id
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
    <title>Edit Service</title>
    <!-- Optional styling shared by all five systems. The system still works without it. -->
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
    <h2>Edit Service #<?= e($id) ?></h2>
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
        <input type="hidden" name="id" value="<?= e($id) ?>">

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
            <button type="submit">Save Changes</button>
            <a href="index.php">Cancel and go back</a>
        </p>
    </form>
</main>
</body>
</html>
