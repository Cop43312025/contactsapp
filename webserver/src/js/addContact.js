// Load user info on page load
loadUserInfo().catch(err => console.error("Error:", err));

let isSubmitting = false;

$("addContactForm").addEventListener("submit", async (e) => {
  e.preventDefault();

  // Prevent multiple submissions
  if (isSubmitting) return;
  isSubmitting = true;

  const msg = $("msg");
  msg.textContent = "Adding contact...";
  const button = $("addContactForm").querySelector('button[type="submit"]');
  button.disabled = true;

  const first_name = $("firstName").value.trim();
  const last_name = $("lastName").value.trim();
  const email = $("email").value.trim();
  const phone = $("phone").value.trim();

  try {
    const res = await fetch("/api/contacts/create", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ first_name, last_name, email, phone }),
    });

    const text = await res.text();
    let data = null;
    try { data = JSON.parse(text); } catch {}

    if (!data) {
      msg.textContent = "Failed to add contact";
      isSubmitting = false;
      button.disabled = false;
      return;
    }

    // If API says no
    if (!res.ok || data.success === false) {
      msg.textContent = data.message || data.error || `Failed to add contact (${res.status})`;
      isSubmitting = false;
      button.disabled = false;
      return;
    }

    msg.textContent = "Contact added! Redirecting...";
    setTimeout(() => {
      location.href = "/contacts.html";
    }, 1000);
  } catch (err) {
    console.error("Error adding contact:", err);
    msg.textContent = "Failed to add contact";
    isSubmitting = false;
    button.disabled = false;
  }
});
