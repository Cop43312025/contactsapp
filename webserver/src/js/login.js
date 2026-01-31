function $(id) { return document.getElementById(id); }

function setCookie(name, value, maxAge) {
  document.cookie =
    name + "=" + encodeURIComponent(value) +
    "; Path=/; Max-Age=" + maxAge +
    "; SameSite=Lax";
}

$("loginForm").addEventListener("submit", async (e) => {
  e.preventDefault();

  const msg = $("msg");
  msg.textContent = "Logging in...";

  const username = $("username").value.trim();
  const password = $("password").value;

  try {
    const res = await fetch("/api/auth_controller.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ login_type: "credential", username, password }),
    });

    const text = await res.text();
    let data = null;
    try { data = JSON.parse(text); } catch {}

    if (!data) {
      msg.textContent = "Login failed";
      return;
    }

    // If API says no
    if (!res.ok || data.success === false || data.token == null) {
      msg.textContent = data.message || data.error || `Login failed (${res.status})`;
      return;
    }

    // remembers the user for 1 day cause why not, just here so that i can see if it works
    setCookie("token", data.token, 86400);

    msg.textContent = "Logged in!";
    location.href = "/?page=contact_list";
  } catch (err) {
    msg.textContent = "Login failed";
  }
});
