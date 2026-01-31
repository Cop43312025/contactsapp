<?php
$title = "Delete Contact";
$contact_id = $_GET['id'] ?? null;

// TODO: Fetch actual contact data from database
$contact = [
  'id' => $contact_id,
  'name' => 'John Doe',
  'email' => 'john@example.com'
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
    <p><strong>Are you sure you want to delete this contact?</strong></p>
    <p>Name: <?php echo htmlspecialchars($contact['name']); ?></p>
    <p>Email: <?php echo htmlspecialchars($contact['email']); ?></p>

    <form method="post" action="">
      <button type="submit" name="confirm_delete" style="background-color: #d9534f; color: white;">Yes, Delete</button>
      <button type="button" onclick="window.location.href='/?page=contact_info&id=<?php echo $contact_id; ?>'">Cancel</button>
    </form>

    <?php
    // TODO: Handle delete confirmation and remove from database
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_delete'])) {
      echo '<p style="color: green;">Contact deleted successfully! (Not actually deleted yet - connect to API)</p>';
      echo '<p><a href="/?page=contact_list">Return to contact list</a></p>';
    }
    ?>
  <?php else: ?>
    <p>No contact ID provided.</p>
  <?php endif; ?>
</body>
</html>