<?php

require_once('modules/CustomImport/CustomImportController.php');

class CustomImport extends Vtiger_Module {

    public function processImport() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['csv_file'])) {
            // Get the uploaded CSV file and module selections
            $csvFile = $_FILES['csv_file']['tmp_name'];
            $primaryModule = $_POST['primary_module'];
            $secondaryModule = $_POST['secondary_module'];
            $tertiaryModule = $_POST['tertiary_module'];

            // Instantiate the CustomImportController and process the import
            $controller = new CustomImportController();
            $result = $controller->handleImport($csvFile, $primaryModule, $secondaryModule, $tertiaryModule);

            if ($result) {
                echo "Import successful!";
            } else {
                echo "Error occurred during import.";
            }
        }
    }
}
?>
