$("registerForm").addEventListener("submit", async (e) => {
  e.preventDefault();

  const msg = $("msg");
  msg.textContent = "Registering...";

  const username = $("username").value.trim();
  const password = $("password").value;
  const confirmPassword = $("confirmPassword").value;

  if (password !== confirmPassword) {
    msg.textContent = "Passwords do not match";
    return;
  }

  try {
    const res = await fetch("/api/users/signup", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ username, password }),
    });

    const text = await res.text();
    let data = null;
    try { data = JSON.parse(text); } catch {}

    if (!data) {
      msg.textContent = "Registration failed";
      return;
    }

    // If API says no
    if (!res.ok || data.success === false) {
      msg.textContent = data.message || data.error || `Registration failed (${res.status})`;
      return;
    }

    msg.textContent = "Registration successful! Redirecting...";
    location.href = "/contacts.html";
  } catch (err) {
    msg.textContent = "Registration failed";
  }
});
