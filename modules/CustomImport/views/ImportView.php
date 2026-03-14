<?php
// ImportView.php - Form to upload CSV file and select modules
global $current_user;

$modules = Vtiger_Module_Model::getAllModules(); // Get all available modules in Vtiger
?>

<form enctype="multipart/form-data" method="post" action="index.php?module=CustomImport&action=Import">
    <label for="csv_file">Upload CSV File:</label>
    <input type="file" name="csv_file" required />

    <label for="primary_module">Primary Module (e.g., Account):</label>
    <select name="primary_module">
        <option value="Accounts">Accounts</option>
        <option value="Leads">Leads</option>
        <option value="Opportunities">Opportunities</option>
        <!-- Add more modules as needed -->
    </select>

    <label for="secondary_module">Secondary Module (e.g., Contact):</label>
    <select name="secondary_module">
        <option value="Contacts">Contacts</option>
        <option value="Potentials">Potentials</option>
        <!-- Add more related modules as needed -->
    </select>

    <label for="tertiary_module">Tertiary Module (e.g., Potentials):</label>
    <select name="tertiary_module">
        <option value="Potentials">Potentials</option>
        <!-- Add other related modules as needed -->
    </select>

    <button type="submit">Import</button>
</form>
