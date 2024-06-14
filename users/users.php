<?php


//start database connection
$host = 'localhost'; 
$dbname = 'your_database_name'; 
$username = 'your_username'; 
$password = 'your_password';

try {
    // Connecting  to database
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Prep my SQL query
    $sql = "SELECT
                u.username AS User_Name,
                u.registration_time AS Registration_Time,
                COUNT(o.id) AS Number_of_Orders,
                MAX(o.order_date) AS Last_Order_Date,
                CASE
                    WHEN COUNT(o.id) >= 5 THEN 'active'
                    ELSE 'inactive'
                END AS Status
            FROM
                users u
            LEFT JOIN
                orders o ON u.id = o.user_id
            GROUP BY
                u.id
            ORDER BY
                Number_of_Orders DESC";

    // Execute query
    $stmt = $pdo->query($sql);
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Error executing query: " . $e->getMessage());
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Order Statistics</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evSXLWZRyI7SLtAoQHuAIzgjIWlwcNWdzBTUhBPXrzZQzbasey/7zGgMvWEz2kDa" crossorigin="anonymous">
</head>
<body>
    <div class="container mt-5">
        <h1>User Order Statistics</h1>
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="table-light">
                    <tr>
                        <th>User Name</th>
                        <th>Registration Time</th>
                        <th>Number of Orders</th>
                        <th>Last Order Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?= htmlspecialchars($user['User_Name']) ?></td>
                            <td><?= htmlspecialchars($user['Registration_Time']) ?></td>
                            <td><?= htmlspecialchars($user['Number_of_Orders']) ?></td>
                            <td><?= htmlspecialchars($user['Last_Order_Date']) ?></td>
                            <td class="<?= $user['Status'] == 'active' ? 'text-success' : 'text-danger' ?>">
                                <?= htmlspecialchars(ucfirst($user['Status'])) ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <!-- Bootstrap JS and dependencies (optional) -->
    <!-- Optional: include JavaScript to enable Bootstrap features like tooltips, popovers, etc. -->
    <!-- <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-lEWE0j5jx0n4fNfX3bLh0c8GUJHOb47Oy/z1V81B3KM+GfJT8ikyA4A3wF2twHbs" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.min.js" integrity="sha384-p0y8RODChLG6l4W1dbPLZNRX+coC5s6HCECtbkGi1R4YNTldY7b4Y76fJCB4wvq/" crossorigin="anonymous"></script> -->
</body>
</html>
