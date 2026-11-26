<?php
// edit.php - GET shows the saved values; POST validates then updates only that ID.
require_once 'config.php';

// e() makes saved text safe to print inside HTML (stops injected tags/scripts).
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

// The ID comes from the URL on GET and from the hidden input on POST.
$requested_id = $_SERVER['REQUEST_METHOD'] === 'POST' ? ($_POST['id'] ?? '') : ($_GET['id'] ?? '');

// Accept only a positive whole number as the ID.
$id = filter_var($requested_id ?? '', FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
$record = false;

if ($id !== false) {
    $statement = $pdo->prepare('SELECT * FROM employees WHERE id = :id');
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
    <body class="case-3">
    <header>
        <?php if (file_exists(__DIR__ . '/../profile.php')): ?>
            <a class="portfolio-link" href="../index.php#case-3">&larr; Back to portfolio</a>
        <?php endif; ?>
        <p class="case-no">Case Study 3</p>
        <h1>Employee Information System</h1>
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
    'employee_number' => (string) $record['employee_number'],
    'full_name' => (string) $record['full_name'],
    'email' => (string) $record['email'],
    'department' => (string) $record['department'],
    'shift_number' => (string) $record['shift_number'],
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
    // The dropdown can be tampered with, so check the value again here.
    if (!isset($errors['shift_number']) && !in_array($record_input['shift_number'], ['1', '2', '3', '4'], true)) {
        $errors['shift_number'] = 'Shift Number must be 1, 2, 3, or 4.';
    }

    // 4. Update only the selected row (WHERE id = :id).
    if (!$errors) {
        $statement = $pdo->prepare(
            'UPDATE employees
             SET employee_number = :employee_number,
                 full_name = :full_name,
                 email = :email,
                 department = :department,
                 shift_number = :shift_number
             WHERE id = :id'
        );
        $statement->execute([
            ':employee_number' => $record_input['employee_number'],
            ':full_name' => $record_input['full_name'],
            ':email' => $record_input['email'],
            ':department' => $record_input['department'],
            ':shift_number' => $record_input['shift_number'],
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
    <title>Edit Employee</title>
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
    <h2>Edit Employee #<?= e($id) ?></h2>
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
            <button type="submit">Save Changes</button>
            <a href="index.php">Cancel and go back</a>
        </p>
    </form>
</main>
</body>
</html>
