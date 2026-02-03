// Get contact ID from URL parameters
const urlParams = new URLSearchParams(window.location.search);
const contactId = urlParams.get('id');

// Load contact data on page load
async function loadContact() {
  if (!contactId) {
    $("msg").textContent = "No contact ID provided";
    return;
  }

  try {
    const res = await fetch(`/api/contacts/view?id=${contactId}`, {
      method: "GET",
      headers: { "Content-Type": "application/json" },
    });

    const data = await res.json();

    if (data.success && data.data && data.data.length > 0) {
      const contact = data.data[0];
      $("firstName").value = contact.first_name;
      $("lastName").value = contact.last_name;
      $("email").value = contact.email;
      $("phone").value = contact.phone;
    } else {
      $("msg").textContent = "Contact not found";
    }
  } catch (err) {
    console.error("Error loading contact:", err);
    $("msg").textContent = "Error loading contact";
  }
}

// Handle form submission
let isSubmitting = false;

$("editContactForm").addEventListener("submit", async (e) => {
  e.preventDefault();

  // Prevent multiple submissions
  if (isSubmitting) return;
  isSubmitting = true;

  const msg = $("msg");
  msg.textContent = "Saving changes...";
  const button = $("editContactForm").querySelector('button[type="submit"]');
  button.disabled = true;

  const first_name = $("firstName").value.trim();
  const last_name = $("lastName").value.trim();
  const email = $("email").value.trim();
  const phone = $("phone").value.trim();

  try {
    const res = await fetch(`/api/contacts/update/${contactId}`, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ first_name, last_name, email, phone }),
    });

    const text = await res.text();
    let data = null;
    try { data = JSON.parse(text); } catch {}

    if (!data) {
      msg.textContent = "Failed to save changes";
      isSubmitting = false;
      button.disabled = false;
      return;
    }

    // If API says no
    if (!res.ok || data.success === false) {
      msg.textContent = data.message || data.error || `Failed to save changes (${res.status})`;
      isSubmitting = false;
      button.disabled = false;
      return;
    }

    msg.textContent = "Changes saved! Redirecting...";
    setTimeout(() => {
      location.href = "/contacts.html";
    }, 1000);
  } catch (err) {
    console.error("Error saving contact:", err);
    msg.textContent = "Failed to save changes";
    isSubmitting = false;
    button.disabled = false;
  }
});

// Load contact data on page load
loadUserInfo().catch(err => console.error("Error:", err));
loadContact();
