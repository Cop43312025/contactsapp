<?php $title = "Contacts"; ?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8" />
  <title><?php echo $title; ?></title>
</head>
<body>
  <hr>

  <h1><?php echo $title; ?></h1>

  <ul>
    <li>John Doe — <a href="/?page=contact_list&id=1">View</a></li>
    <li>Jane Smith — <a href="/?page=contact_list&id=2">View</a></li>
    <li>Alex Johnson — <a href="/?page=contact_list&id=3">View</a></li>
  </ul>

  <?php if (isset($_GET['id'])): ?>
    <hr>
    <h2>Viewing Contact ID: <?php echo htmlspecialchars($_GET['id']); ?></h2>
    <p><b>Name:</b> Placeholder Name</p>
    <p><b>Email:</b> placeholder@email.com</p>
    <p><b>Phone:</b> 555-555-5555</p>
    <p><a href="/?page=contact_create_and_edit&id=<?php echo htmlspecialchars($_GET['id']); ?>">Edit this contact</a></p>
  <?php endif; ?>
</body>
</html>