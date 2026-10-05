<?php

include "db.php";

if (!isset($_GET["id"])) {
    die("Visitor ID is missing.");
}

$visitor_id = intval($_GET["id"]);

$sql = "SELECT * FROM visitors WHERE id = $visitor_id";

$result = $conn->query($sql);

if ($result->num_rows == 0) {
    die("Visitor not found.");
}

$visitor = $result->fetch_assoc();

?>

<!DOCTYPE html>
<html>

<head>

    <title>Digital Visitor Pass</title>

    <style>

        body {
            font-family: Arial, sans-serif;
            background: #eef3f8;
            margin: 0;
            padding: 40px;
        }

        .pass {
            width: 500px;
            margin: auto;
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
        }

        .header {
            background: #1e3a5f;
            color: white;
            text-align: center;
            padding: 25px;
        }

        .header h1 {
            margin: 0;
            font-size: 25px;
        }

        .header p {
            margin: 8px 0 0;
        }

        .pass-body {
            padding: 30px;
        }

        .visitor-id {
            text-align: center;
            background: #e8f5e9;
            color: #15803d;
            padding: 10px;
            border-radius: 8px;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .detail {
            display: flex;
            justify-content: space-between;
            border-bottom: 1px solid #ddd;
            padding: 12px 0;
        }

        .label {
            font-weight: bold;
            color: #555;
        }

        .value {
            color: #222;
        }

        .status {
            text-align: center;
            margin-top: 25px;
        }

        .active {
            display: inline-block;
            background: #22c55e;
            color: white;
            padding: 8px 25px;
            border-radius: 20px;
            font-weight: bold;
        }

        .footer {
            text-align: center;
            padding: 15px;
            background: #f8fafc;
            color: #777;
            font-size: 13px;
        }

        .btn {
            display: block;
            width: 200px;
            margin: 25px auto 0;
            padding: 12px;
            background: #1e3a5f;
            color: white;
            text-decoration: none;
            text-align: center;
            border-radius: 6px;
        }

        .btn:hover {
            background: #162d4a;
        }

    </style>

</head>

<body>

    <div class="pass">

        <div class="header">

            <h1>DIGITAL VISITOR PASS</h1>

            <p>Visitor Pass Management System</p>

        </div>

        <div class="pass-body">

            <div class="visitor-id">

                Visitor ID: #<?php echo $visitor["id"]; ?>

            </div>

            <div class="detail">
                <span class="label">Full Name</span>
                <span class="value">
                    <?php echo htmlspecialchars($visitor["full_name"]); ?>
                </span>
            </div>

            <div class="detail">
                <span class="label">Email</span>
                <span class="value">
                    <?php echo htmlspecialchars($visitor["email"]); ?>
                </span>
            </div>

            <div class="detail">
                <span class="label">Phone</span>
                <span class="value">
                    <?php echo htmlspecialchars($visitor["phone"]); ?>
                </span>
            </div>

            <div class="detail">
                <span class="label">Purpose</span>
                <span class="value">
                    <?php echo htmlspecialchars($visitor["purpose"]); ?>
                </span>
            </div>

            <div class="detail">
                <span class="label">Visit Date</span>
                <span class="value">
                    <?php echo htmlspecialchars($visitor["visit_date"]); ?>
                </span>
            </div>

            <div class="status">

                <span class="active">
                    ACTIVE
                </span>

            </div>

            <a href="index.php" class="btn">
                Register Another Visitor
            </a>

        </div>

        <div class="footer">

            Please show this digital pass at the entrance.

        </div>

    </div>

</body>

</html>