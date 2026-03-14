<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload CSV</title>
    <!-- Link to Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
        }

        .container {
            max-width: 600px;
            margin-top: 50px;
        }

        .card {
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .form-label {
            font-size: 16px;
        }

        .form-control {
            margin-bottom: 15px;
        }

        .btn-primary {
            width: 100%;
            padding: 12px;
        }

        .upload-btn {
            margin-top: 20px;
        }

        .upload-btn input {
            width: 100%;
            padding: 12px;
        }

        .upload-btn input[type="submit"] {
            background-color: #007bff;
            color: white;
            border: none;
            font-size: 16px;
        }

        .upload-btn input[type="submit"]:hover {
            background-color: #0056b3;
            cursor: pointer;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="card">
        <h3 class="text-center">Upload CSV File</h3>
        <form action="import_csv.php" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="csv_file" class="form-label">Choose CSV File:</label>
                <input type="file" name="csv_file" id="csv_file" class="form-control" accept=".csv" required>
            </div>
            <div class="upload-btn">
                <input type="submit" name="upload" value="Upload" class="btn btn-primary">
            </div>
        </form>
    </div>
</div>

<!-- Link to Bootstrap JS and Popper.js -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>

</body>
</html>
