<?php
// Enable error reporting at the top of the file
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Add logging
function logError($message) {
    error_log(date('Y-m-d H:i:s') . ": " . $message . "\n", 3, 'import_errors.log');
}

try {
    require_once 'vendor/autoload.php';
    include_once 'config.php';
    include_once 'include/Webservices/Relation.php';
    include_once 'vtlib/Vtiger/Module.php';
    include_once 'includes/main/WebUI.php';
    global $adb;
global $current_user;
$current_user = CRMEntity::getInstance('Users');
$userid = Users::getActiveAdminId();
$current_user->retrieveCurrentUserInfoFromFile($userid);

    // Check if required globals are available
    if (!isset($adb)) {
        throw new Exception('Database connection not available');
    }
    if (!isset($current_user)) {
        throw new Exception('Current user not initialized');
    }

    // Basic HTML form for testing
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        ?>
        <form method="post" enctype="multipart/form-data">
            <input type="file" name="csv_file" required>
            <input type="submit" name="upload" value="Upload CSV">
        </form>
        <?php
        exit;
    }

    // File upload handling with validation
    if (!isset($_POST['upload']) || !isset($_FILES['csv_file'])) {
        throw new Exception('No file uploaded');
    }

    $file = $_FILES['csv_file'];
    
    // Detailed file upload error checking
    switch ($file['error']) {
        case UPLOAD_ERR_OK:
            break;
        case UPLOAD_ERR_INI_SIZE:
            throw new Exception('File exceeds PHP.ini upload_max_filesize');
        case UPLOAD_ERR_FORM_SIZE:
            throw new Exception('File exceeds form MAX_FILE_SIZE');
        case UPLOAD_ERR_PARTIAL:
            throw new Exception('File was only partially uploaded');
        case UPLOAD_ERR_NO_FILE:
            throw new Exception('No file was uploaded');
        default:
            throw new Exception('Unknown upload error');
    }

    $fileTmpName = $file['tmp_name'];
    $fileName = $file['name'];

    // Validate file type
    $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    if ($fileExtension !== 'csv') {
        throw new Exception("Invalid file type. Please upload a CSV file.");
    }

    // Check if file exists and is readable
    if (!file_exists($fileTmpName) || !is_readable($fileTmpName)) {
        throw new Exception("Cannot read uploaded file");
    }

    // Open file with error checking
    $handle = fopen($fileTmpName, 'r');
    if ($handle === FALSE) {
        throw new Exception("Failed to open file");
    }

    // Initialize counters for reporting
    $rowCount = 0;
    $successCount = 0;
    $errorCount = 0;

    // Skip header row
    fgetcsv($handle, 1000, ',');

    // Process each row
    while (($row = fgetcsv($handle, 1000, ',')) !== FALSE) {
        try {
            $rowCount++;
            
            // Validate row data
            if (count($row) < 6) {
                throw new Exception("Row $rowCount: Invalid number of columns");
            }

            // Extract and validate data
            $email = filter_var($row[0], FILTER_VALIDATE_EMAIL);
            if (!$email) {
                throw new Exception("Row $rowCount: Invalid email format");
            }

            $firstname = trim($row[1]);
            $lastname = trim($row[2]);
            $accountname = trim($row[3]);
            $phone = trim($row[4]);
            $ticket_title = trim($row[5]);

            // Log the data being processed
            logError("Processing row $rowCount: Email=$email, Name=$firstname $lastname");

            // Check if account and contact exist
            $accval = isaccRecordExits($email, $accountname);
            $val = isRecordExits($email, $firstname, $lastname);

            // Create/Update Account
            if (empty($accval)) {
                $Accounts = CRMEntity::getInstance('Accounts');
                if (!$Accounts) {
                    throw new Exception("Failed to create Accounts instance");
                }
                $Accounts->column_fields['assigned_user_id'] = $current_user->id; // Use current user instead of hardcoded 1
                $Accounts->column_fields['accountname'] = $accountname;
                $Accounts->column_fields['phone'] = $phone;
                $Accounts->column_fields['email1'] = $email;
                $Accounts->save('Accounts');
                $AccountsModuleId = $Accounts->id;
                logError("Created new account: $accountname");
            } else {
                $recordModel = Accounts_Record_Model::getInstanceById($accval);
                $recordModel->set('mode', 'edit');
                $recordModel->set("accountname", $accountname);
                $recordModel->set("phone", $phone);
                $recordModel->set("email1", $email);
                $recordModel->save();
                $AccountsModuleId = $accval;
                logError("Updated existing account: $accountname");
            }

            // Similar verbose logging for Contacts and HelpDesk...
            
            $successCount++;

        } catch (Exception $e) {
            $errorCount++;
            logError("Error processing row $rowCount: " . $e->getMessage());
            continue; // Skip to next row on error
        }
    }

    fclose($handle);

    // Output results
    echo "<h2>Import Results</h2>";
    echo "Total rows processed: $rowCount<br>";
    echo "Successful imports: $successCount<br>";
    echo "Errors: $errorCount<br>";
    echo "Check import_errors.log for details";

} catch (Exception $e) {
    logError("Critical error: " . $e->getMessage());
    echo "<h2>Error</h2>";
    echo "An error occurred: " . htmlspecialchars($e->getMessage());
    echo "<br>Check import_errors.log for details";
}