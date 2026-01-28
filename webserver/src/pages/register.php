<?php $title = "Register"; ?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8" />
  <title><?php echo $title; ?></title>
</head>
<body>
  <hr>

  <h1><?php echo $title; ?></h1>

  <form method="post" action="">
    <p>
      <label>
        Username:<br>
        <input type="text" name="username">
      </label>
    </p>

    <p>
      <label>
        Email:<br>
        <input type="email" name="email">
      </label>
    </p>

    <p>
      <label>
        Password:<br>
        <input type="password" name="password">
      </label>
    </p>

    <p>
      <label>
        Confirm Password:<br>
        <input type="password" name="confirm_password">
      </label>
    </p>

    <p>
      <input type="submit" value="Register">
    </p>
  </form>

</body>
</html>
