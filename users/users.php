<?php


//start database connection
$host = 'localhost'; 
$dbname = 'your_database_name'; 
$username = 'your_username'; 
$password = 'your_password';


class Database {
    private $host;
    private $dbname;
    private $username;
    private $password;
    private $pdo;

    public function __construct($host, $dbname, $username, $password) {
        $this->host = $host;
        $this->dbname = $dbname;
        $this->username = $username;
        $this->password = $password;
    }

    public function connect() {
        try {
            $this->pdo = new PDO("mysql:host={$this->host};dbname={$this->dbname}", $this->username, $this->password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }

    public function executeQuery($sql) {
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Error executing query: " . $e->getMessage());
        }
    }
}

class UserData {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getUsersWithOrderStatistics() {
        $sql = "SELECT 
                    u.username,
                    u.registration_time,
                    COUNT(o.user_id) AS num_orders,
                    MAX(o.order_date) AS last_order_date
                FROM 
                    users u
                LEFT JOIN 
                    orders o ON u.id = o.user_id
                GROUP BY 
                    u.id
                ORDER BY 
                    num_orders DESC";

        return $this->db->executeQuery($sql);
    }
}

//Instantiate db connection
$db = new Database($host, $dbname, $username, $password);
$db->connect();

$userData = new UserData($db);
$users = $userData->getUsersWithOrderStatistics();

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
