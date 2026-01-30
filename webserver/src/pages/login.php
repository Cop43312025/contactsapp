<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8" />
  <title>Login</title>
</head>
<body>
  <h1>Login</h1>

  <form id="loginForm" action="#" method="post">
    <label>Username</label><br>
    <input id="username" type="text" required /><br><br>

    <label>Password</label><br>
    <input id="password_hash" type="password" required /><br><br>

    <button type="submit">Login</button>
  </form>


  <a href="/?page=register">Register Account</a>

  <p id="msg"></p>


<script src="/js/login.js"></script> <!--this works for now i believe but i def need to test more -->
<!--just saying this so i can push it with reprocussion :D -->

</body>
</html>
