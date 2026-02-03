// Check if user has a valid token on page load
async function checkExistingToken() {
  try {
    const res = await fetch("/api/users/login", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({}),
    });

    const data = await res.json();

    if (data.success && data.data && data.data.length > 0) {
      // Valid token, redirect to contacts
      location.href = "/contacts.html";
    }
    // If invalid or expired, silently fail and show login form
  } catch (err) {
    // Silently fail on error
    console.debug("Token check failed, showing login form");
  }
}

// Check for valid token on page load
checkExistingToken();

$("loginForm").addEventListener("submit", async (e) => {
  e.preventDefault();

  const msg = $("msg");
  msg.textContent = "Logging in...";

  const username = $("username").value.trim();
  const password = $("password").value;

  try {
    const res = await fetch("/api/users/login", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ username, password }),
    });

    const text = await res.text();
    let data = null;
    try { data = JSON.parse(text); } catch {}

    if (!data) {
      msg.textContent = "Login failed";
      return;
    }

    // If API says no
    if (!res.ok || data.success === false) {
      msg.textContent = data.message || data.error || `Login failed (${res.status})`;
      return;
    }

    msg.textContent = "Logged in!";
    location.href = "/contacts.html";
  } catch (err) {
    msg.textContent = "Login failed";
  }
});
