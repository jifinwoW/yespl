<?php

// Increase execution time and memory limit
ini_set('max_execution_time', 0);
ini_set("memory_limit", "-1");

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Include necessary Vtiger files
include_once 'config.php';
include_once 'vtlib/Vtiger/Module.php';
include_once 'includes/main/WebUI.php';
include_once 'modules/Contacts/Contacts.php';

// Ensure the current user is initialized if not already
global $current_user;
if (!$current_user) {
    $current_user = Users::getActiveAdminUser(); // Fetch active admin user
}

// Create a new Contact entity instance
$contact = CRMEntity::getInstance('Contacts');

// Assign values to the contact fields
$contact->column_fields['assigned_user_id'] = $current_user->id;  // Assigning current user dynamically
$contact->column_fields["firstname"] = 'John';  // First name (required)
$contact->column_fields["lastname"] = 'Doe';   // Last name (required)
$contact->column_fields["email"] = 'john.doe@example.com'; // Email (optional but useful)

// Optional: You can also add more fields here if needed (e.g., phone number, address, etc.)

try {
    // Save the contact to the database
    $contact->save("Contacts");

    echo "Contact created successfully!";
} catch (Exception $e) {
    // Handle any errors during the save process
    echo "Error: " . $e->getMessage();
}
?>
