<?php
// db_query.php

// ===== CONFIG =====
$host = "localhost"; // your DB host
$user = "root";      // your DB user
$pass = "root";          // your DB password
$db   = "yespl_staging";      // default database

// show all PHP errors on page
error_reporting(E_ALL);
ini_set('display_errors', 1);

// connect
$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("<div style='color:red'>❌ Connection failed: " . htmlspecialchars($conn->connect_error) . "</div>");
}

// handle query
$result = null;
$error = null;
$query = null;
if (isset($_POST['query'])) {
    $query = trim($_POST['query']);
    if ($query !== "") {
        $res = $conn->query($query);
        if ($res === TRUE) {
            $result = "✅ Query executed successfully.";
        } elseif ($res instanceof mysqli_result) {
            $result = $res;
        } else {
            $error = $conn->error;
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Mini DB Query Tool</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; background:#f4f4f4;}
        textarea { width: 100%; height: 120px; font-family: monospace; }
        table { border-collapse: collapse; margin-top: 20px; background: #fff;}
        th, td { border: 1px solid #ccc; padding: 6px 10px; }
        th { background: #eee; }
        .error { color: red; margin-top:10px;}
        .success { color: green; margin-top:10px;}
        .container { max-width: 900px; margin: auto; background:#fff; padding:20px; border-radius:8px; box-shadow:0 2px 5px rgba(0,0,0,0.1);}
    </style>
</head>
<body>
<div class="container">
    <h2>Mini SQL Runner</h2>
    <form method="post">
        <textarea name="query" placeholder="Write your SQL query here..."><?php echo $query ? htmlspecialchars($query) : ''; ?></textarea><br><br>
        <button type="submit">Run Query</button>
    </form>

    <?php if ($error): ?>
        <div class="error">❌ SQL Error: <?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <?php if ($result && $result instanceof mysqli_result): ?>
        <table>
            <tr>
                <?php foreach ($result->fetch_fields() as $field): ?>
                    <th><?php echo htmlspecialchars($field->name); ?></th>
                <?php endforeach; ?>
            </tr>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <?php foreach ($row as $val): ?>
                        <td><?php echo htmlspecialchars($val ?? 'NULL'); ?></td>
                    <?php endforeach; ?>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php elseif (is_string($result)): ?>
        <div class="success"><?php echo $result; ?></div>
    <?php endif; ?>
</div>
</body>
</html>
