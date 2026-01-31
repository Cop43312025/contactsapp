<?php
$title = "Edit Contact";
$contact_id = $_GET['id'] ?? null;

// TODO: Fetch actual contact data from database
$contact = [
  'id' => $contact_id,
  'name' => 'John Doe',
  'email' => 'john@example.com',
  'phone' => '555-555-5555'
];
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8" />
  <title><?php echo $title; ?></title>
</head>
<body>
  <nav>
    <a href="/?page=contact_list">Back to Contacts</a> |
    <a href="/?page=contact_info&id=<?php echo $contact_id; ?>">View</a>
  </nav>
  <hr>

  <h1><?php echo $title; ?></h1>

  <?php if ($contact_id): ?>
    <form method="post" action="">
      <p>
        <label>
          Name:<br>
          <input type="text" name="name" value="<?php echo htmlspecialchars($contact['name']); ?>" required>
        </label>
      </p>

      <p>
        <label>
          Email:<br>
          <input type="email" name="email" value="<?php echo htmlspecialchars($contact['email']); ?>" required>
        </label>
      </p>

      <p>
        <label>
          Phone:<br>
          <input type="text" name="phone" value="<?php echo htmlspecialchars($contact['phone']); ?>">
        </label>
      </p>

      <p>
        <button type="submit">Save Changes</button>
        <button type="button" onclick="window.location.href='/?page=contact_info&id=<?php echo $contact_id; ?>'">Cancel</button>
      </p>
    </form>

    <?php
    // TODO: Handle form submission and update database
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      echo '<p style="color: green;">Contact updated successfully! (Not actually saved yet - connect to API)</p>';
    }
    ?>
  <?php else: ?>
    <p>No contact ID provided.</p>
  <?php endif; ?>
</body>
</html>