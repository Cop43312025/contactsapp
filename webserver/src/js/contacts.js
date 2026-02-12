// Fetch and display contacts
async function loadContacts(searchQuery = '') {
  try {
    let url = "/api/contacts/view";
    if (searchQuery) {
      url += `?first_name=${encodeURIComponent(searchQuery)}&last_name=${encodeURIComponent(searchQuery)}&email=${encodeURIComponent(searchQuery)}&phone=${encodeURIComponent(searchQuery)}`;
    }
    
    const res = await fetch(url, {
      method: "GET",
      headers: { "Content-Type": "application/json" },
    });

    const data = await res.json();

    if (data.success && data.data) {
      displayContacts(data.data);
    } else {
      const contactsContainer = document.getElementById("contactsContainer");
      contactsContainer.innerHTML = "<div class='empty-state'>No contacts found</div>";
    }
  } catch (err) {
    console.error("Error loading contacts:", err);
    const contactsContainer = document.getElementById("contactsContainer");
    contactsContainer.innerHTML = "<div class='empty-state'>Error loading contacts</div>";
  }
}

// Display contacts
function displayContacts(contacts) {
  const contactsContainer = document.getElementById("contactsContainer");
  
  if (contacts.length === 0) {
    contactsContainer.innerHTML = "<div class='empty-state'>No contacts found</div>";
    return;
  }

  contactsContainer.innerHTML = contacts.map(contact => {
    let name = contact.first_name;
    if (contact.last_name) name += ` ${contact.last_name}`;
    
    let details = '';
    if (contact.email) {
      details += `<p class="contact-field"><span class="field-label">Email</span> <a href="mailto:${contact.email}">${contact.email}</a></p>`;
    }
    if (contact.phone) {
      const d = contact.phone.replace(/\D/g, '');
      const formatted = `(${d.slice(0,3)})-${d.slice(3,6)}-${d.slice(6)}`;
      details += `<p class="contact-field"><span class="field-label">Phone</span> <a href="tel:+1${d}">${formatted}</a></p>`;
    }
    
    return `
    <div class="contact-card">
      <h3 class="contact-name">${name}</h3>
      ${details}
      <p class="contact-date"><span class="field-label">Created</span> ${new Date(contact.creation_date.replace(' ', 'T') + 'Z').toLocaleString() || '-'}</p>
      <div class="contact-actions">
        <button class="btn-edit" onclick="handleContactAction('edit', ${contact.id})">Edit</button>
        <button class="btn-delete" onclick="handleContactAction('delete', ${contact.id})">Delete</button>
      </div>
    </div>
  `;
  }).join("");
}

// Handle contact actions
async function handleContactAction(action, contactId) {
  if (!action) return;

  switch (action) {
    case "delete":
        if (!confirm("Are you sure you want to delete this contact?")) return;
        
        try {
        const res = await fetch(`/api/contacts/delete/${contactId}`, {
            method: "POST",
            headers: { "Content-Type": "application/json" },
        });

        const data = await res.json();

        if (data.success) {
            loadContacts();
        } else {
            alert(data.message || "Error deleting contact");
        }
        } catch (err) {
        console.error("Error deleting contact:", err);
        alert("Error deleting contact");
        }
        break;
    case "edit":
        location.href = `/editContact.html?id=${contactId}`;
        break;
    default:
        console.error("Unknown action:", action);
  }
}

// Handle logout
$("logoutLink").addEventListener("click", async () => {
  try {
    const res = await fetch("/api/users/logout", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
    });

    const data = await res.json();

    if (data.success) {
      location.href = "/";
    }
  } catch (err) {
    console.error("Error logging out:", err);
  }
});

// Handle add contact button
$("addContactBtn").addEventListener("click", () => {
  location.href = "/addContact.html";
});

// Handle search
$("searchInput").addEventListener("input", (e) => {
  const searchQuery = e.target.value.trim();
  loadContacts(searchQuery);
});

// Load data on page load
loadUserInfo();
loadContacts();