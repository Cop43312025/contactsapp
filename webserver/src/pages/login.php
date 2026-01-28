<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8" />
  <title>Login</title>
</head>
<body>
  <h1>Login</h1>

  <form id="loginForm">
    <label>Username</label><br>
    <input id="username" type="text" required /><br><br>

    <label>Password</label><br>
    <input id="password" type="password" required /><br><br>

    <button type="submit">Login</button>
  </form>


  <button onclick="window.location.href='register.php'">Create Account</button>

  <p id="msg"></p>

<script src="/js/login.js"></script>

</body>
</html>
