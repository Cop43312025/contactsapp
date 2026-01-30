const $ = (id) => document.getElementById(id);

const setCookie = (name, value, maxAge) =>
  (document.cookie = `${name}=${encodeURIComponent(value)}; Path=/; Max-Age=${maxAge}; SameSite=Lax`);

$("loginForm").addEventListener("submit", async (e) => {
  e.preventDefault();

  const msg = $("msg");
  msg.textContent = "Logging in...";

  const username = $("username").value.trim();
  const password = $("password_hash").value;
  
  if (username === "" || password === "") {
    msg.textContent = "Please fill in all fields";
    return;
  }

  try {
    const res = await fetch("/api/auth.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ login_type: "credential", username, password }),
    });

    const data = await res.json().catch(() => null);
    const err = !res.ok ? `HTTP ${res.status}` : data?.error;

    if (!data || err) return (msg.textContent = err || "Login failed");

    setCookie("token", data.token, 86400);
    msg.textContent = "Login success!";
    location.href = "/?page=contact_list";
  } catch {
    msg.textContent = "Request failed";
  }
});
