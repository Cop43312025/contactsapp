<?php $title = "Register"; ?>


<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8" />
  <title><?php echo $title; ?></title>
</head>
<body>
  <nav>
    <a href="/?page=login">Login</a> |
    <a href="/?page=register">Create User</a> |
    <a href="/?page=contact_list">Contacts</a> |
    <a href="/?page=contact_create_and_edit">Create/Edit Contact</a>
  </nav>
  <hr>

  <h1><?php echo $title; ?></h1>

  <form method="post" action="">
    <p>
      <label>
        Username:<br>
        <input id="names" name="username" type="text" required>
    </p>

    <p>
      <label>
        Email:<br>
        <input id="email" name="email" type="email" required>
      </label>
    </p>

    <p>
      <label>
        Password:<br>
        <input type="password" name="password" minlength="8" pattern="(?=.*[A-Za-z])(?=.*[^A-Za-z0-9]).+" title="must be 8 character, and include a letter, special character, and a title" required>
      </label>
    </p>

    <p>
      <label>
        Confirm Password:<br>
        <input type="password" name="confirm_password" required>
      </label>
    </p>

    <p>
      <input type="submit" value="Register">
    </p>
  </form>

</body>
</html>
