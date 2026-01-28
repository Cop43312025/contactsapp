<?php $title = "Contacts"; ?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8" />
  <title><?php echo $title; ?></title>
</head>
<body>
  <hr>

  <h1><?php echo $title; ?></h1>

  <ul>
    <li>John Doe — 
      <select onchange="if(this.value) window.location.href=this.value">
        <option value="">Actions</option>
        <option value="/?page=contact_info&id=1">View</option>
        <option value="/?page=contact_edit&id=1">Edit</option>
        <option value="/?page=contact_delete&id=1">Delete</option>
      </select>
    </li>
    <li>Jane Smith — 
      <select onchange="if(this.value) window.location.href=this.value">
        <option value="">Actions</option>
        <option value="/?page=contact_info&id=1">View</option>
        <option value="/?page=contact_edit&id=1">Edit</option>
        <option value="/?page=contact_delete&id=1">Delete</option>
      </select>
    </li>
    <li>Alex Johnson — 
      <select onchange="if(this.value) window.location.href=this.value">
        <option value="">Actions</option>
        <option value="/?page=contact_info&id=1">View</option>
        <option value="/?page=contact_edit&id=1">Edit</option>
        <option value="/?page=contact_delete&id=1">Delete</option>
      </select>
    </li>
  </ul>
</body>
</html>