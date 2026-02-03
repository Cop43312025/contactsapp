// Common utility functions used across the app

function $(id) { return document.getElementById(id); }

// Fetch and display user info on page load
async function loadUserInfo() {
  try {
    const res = await fetch("/api/users/login", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({}),
    });

    const data = await res.json();

    if (data.success && data.data && data.data.length > 0) {
      const username = data.data[0].username;
      const userInfoEl = $("userInfo");
      if (userInfoEl) {
        userInfoEl.textContent = `Welcome, ${username}!`;
      }
    } else {
      // No valid session, redirect to login
      location.href = "/";
    }
  } catch (err) {
    console.error("Error loading user info:", err);
    location.href = "/";
  }
}
