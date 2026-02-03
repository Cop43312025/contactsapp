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
      contactsContainer.innerHTML = "<div style=\"text-align: center; padding: 20px; grid-column: 1 / -1;\">No contacts found</div>";
    }
  } catch (err) {
    console.error("Error loading contacts:", err);
    const contactsContainer = document.getElementById("contactsContainer");
    contactsContainer.innerHTML = "<div style=\"text-align: center; padding: 20px; grid-column: 1 / -1;\">Error loading contacts</div>";
  }
}

// Display contacts
function displayContacts(contacts) {
  const contactsContainer = document.getElementById("contactsContainer");
  
  if (contacts.length === 0) {
    contactsContainer.innerHTML = "<div style=\"text-align: center; padding: 20px; grid-column: 1 / -1;\">No contacts found</div>";
    return;
  }

  contactsContainer.innerHTML = contacts.map(contact => {
    let name = contact.first_name;
    if (contact.last_name) name += ` ${contact.last_name}`;
    
    let details = '';
    if (contact.email) {
      details += `<p style="margin: 5px 0;"><strong>Email:</strong> <a href="mailto:${contact.email}" style="color: #007bff; text-decoration: none;">${contact.email}</a></p>`;
    }
    if (contact.phone) {
      // Remove all non-numeric characters for tel link
      const phoneDigits = contact.phone.replace(/\D/g, '');
      details += `<p style="margin: 5px 0;"><strong>Phone:</strong> <a href="tel:+${phoneDigits}" style="color: #007bff; text-decoration: none;">${contact.phone}</a></p>`;
    }
    
    return `
    <div style="border: 1px solid #ddd; border-radius: 8px; padding: 15px; background-color: #f9f9f9; box-shadow: 0 2px 4px rgba(0,0,0,0.1); display: flex; flex-direction: column;">
      <h3 style="margin: 0 0 10px 0;">${name}</h3>
      ${details}
      <p style="margin: 5px 0 15px 0; font-size: 12px; color: #666; flex-grow: 1;"><strong>Created:</strong> ${new Date(contact.creation_date.replace(' ', 'T') + 'Z').toLocaleString() || '-'}</p>
      <div style="display: flex; gap: 10px;">
        <button onclick="handleContactAction('edit', ${contact.id})" style="flex: 1; padding: 8px; background-color: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer;">Edit</button>
        <button onclick="handleContactAction('delete', ${contact.id})" style="flex: 1; padding: 8px; background-color: #dc3545; color: white; border: none; border-radius: 4px; cursor: pointer;">Delete</button>
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
