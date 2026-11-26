<?php
// edit.php - GET shows the saved values; POST validates then updates only that ID.
require_once 'config.php';

// e() makes saved text safe to print inside HTML (stops injected tags/scripts).
function e($value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

$labels = [
    'registration_number' => 'Registration Number',
    'participant_name' => 'Participant Name',
    'activity_name' => 'Activity Name',
    'event_period' => 'Event Period',
    'status' => 'Status',
];

// The ID comes from the URL on GET and from the hidden input on POST.
$requested_id = $_SERVER['REQUEST_METHOD'] === 'POST' ? ($_POST['id'] ?? '') : ($_GET['id'] ?? '');

// Accept only a positive whole number as the ID.
$id = filter_var($requested_id ?? '', FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
$record = false;

if ($id !== false) {
    $statement = $pdo->prepare('SELECT * FROM registrations WHERE id = :id');
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
    <body class="case-2">
    <header>
        <?php if (file_exists(__DIR__ . '/../profile.php')): ?>
            <a class="portfolio-link" href="../index.php#case-2">&larr; Back to portfolio</a>
        <?php endif; ?>
        <p class="case-no">Case Study 2</p>
        <h1>Activity Registration Information System</h1>
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
    'registration_number' => (string) $record['registration_number'],
    'participant_name' => (string) $record['participant_name'],
    'activity_name' => (string) $record['activity_name'],
    'event_period' => (string) $record['event_period'],
    'status' => (string) $record['status'],
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
    if (!isset($errors['registration_number']) && mb_strlen($record_input['registration_number']) > 30) {
        $errors['registration_number'] = 'Registration Number must be 30 characters or fewer.';
    }
    if (!isset($errors['participant_name']) && mb_strlen($record_input['participant_name']) > 100) {
        $errors['participant_name'] = 'Participant Name must be 100 characters or fewer.';
    }
    if (!isset($errors['activity_name']) && mb_strlen($record_input['activity_name']) > 100) {
        $errors['activity_name'] = 'Activity Name must be 100 characters or fewer.';
    }
    if (!isset($errors['event_period']) && mb_strlen($record_input['event_period']) > 50) {
        $errors['event_period'] = 'Event Period must be 50 characters or fewer.';
    }
    // The dropdown can be tampered with, so check the value again here.
    if (!isset($errors['status']) && !in_array($record_input['status'], ['Registered', 'Withdrawn'], true)) {
        $errors['status'] = 'Status must be Registered or Withdrawn.';
    }

    // 4. Update only the selected row (WHERE id = :id).
    if (!$errors) {
        $statement = $pdo->prepare(
            'UPDATE registrations
             SET registration_number = :registration_number,
                 participant_name = :participant_name,
                 activity_name = :activity_name,
                 event_period = :event_period,
                 status = :status
             WHERE id = :id'
        );
        $statement->execute([
            ':registration_number' => $record_input['registration_number'],
            ':participant_name' => $record_input['participant_name'],
            ':activity_name' => $record_input['activity_name'],
            ':event_period' => $record_input['event_period'],
            ':status' => $record_input['status'],
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
    <title>Edit Registration</title>
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
    <h2>Edit Registration #<?= e($id) ?></h2>
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
            <label for="registration_number">Registration Number</label><br>
            <input type="text" id="registration_number" name="registration_number" maxlength="30" placeholder="e.g. REG-001" value="<?= e($record_input['registration_number']) ?>" required>
        </p>
        <p>
            <label for="participant_name">Participant Name</label><br>
            <input type="text" id="participant_name" name="participant_name" maxlength="100" placeholder="e.g. Casey Ramos" value="<?= e($record_input['participant_name']) ?>" required>
        </p>
        <p>
            <label for="activity_name">Activity Name</label><br>
            <input type="text" id="activity_name" name="activity_name" maxlength="100" placeholder="e.g. Basic Photography" value="<?= e($record_input['activity_name']) ?>" required>
        </p>
        <p>
            <label for="event_period">Event Period</label><br>
            <input type="text" id="event_period" name="event_period" maxlength="50" placeholder="e.g. November 2026" value="<?= e($record_input['event_period']) ?>" required>
        </p>
        <p>
            <label for="status">Status</label><br>
            <select id="status" name="status" required>
                <option value="">-- Select --</option>
                <?php foreach (['Registered', 'Withdrawn'] as $option): ?>
                    <option value="<?= e($option) ?>" <?= $record_input['status'] === $option ? 'selected' : '' ?>><?= e($option) ?></option>
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
