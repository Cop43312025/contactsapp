<?php
$title = "Contact Details";
$contact_id = $_GET['id'] ?? null;

// TODO: Fetch actual contact data from database
// For now, using placeholder data
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
    <a href="/?page=contact_edit&id=<?php echo $contact_id; ?>">Edit</a> |
    <a href="/?page=contact_delete&id=<?php echo $contact_id; ?>">Delete</a>
  </nav>
  <hr>

  <h1><?php echo $title; ?></h1>

  <?php if ($contact_id): ?>
    <p><strong>ID:</strong> <?php echo htmlspecialchars($contact['id']); ?></p>
    <p><strong>Name:</strong> <?php echo htmlspecialchars($contact['name']); ?></p>
    <p><strong>Email:</strong> <?php echo htmlspecialchars($contact['email']); ?></p>
    <p><strong>Phone:</strong> <?php echo htmlspecialchars($contact['phone']); ?></p>
  <?php else: ?>
    <p>No contact ID provided.</p>
  <?php endif; ?>
</body>
</html>