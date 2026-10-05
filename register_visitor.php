<?php

include "db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $full_name = $_POST["full_name"];
    $email = $_POST["email"];
    $phone = $_POST["phone"];
    $purpose = $_POST["purpose"];
    $visit_date = $_POST["visit_date"];

    $sql = "INSERT INTO visitors 
            (full_name, email, phone, purpose, visit_date)
            VALUES 
            ('$full_name', '$email', '$phone', '$purpose', '$visit_date')";

    if ($conn->query($sql) === TRUE) {

        $visitor_id = $conn->insert_id;

        ?>

        <!DOCTYPE html>
        <html>
        <head>

            <title>Registration Successful</title>

            <style>

                body {
                    font-family: Arial, sans-serif;
                    background: #f1f5f9;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    height: 100vh;
                    margin: 0;
                }

                .success-box {
                    background: white;
                    width: 450px;
                    padding: 40px;
                    text-align: center;
                    border-radius: 15px;
                    box-shadow: 0 10px 30px rgba(0,0,0,0.12);
                }

                .success-icon {
                    font-size: 60px;
                    color: #22c55e;
                }

                h1 {
                    color: #1e3a5f;
                    margin-bottom: 10px;
                }

                p {
                    color: #555;
                }

                .details {
                    text-align: left;
                    background: #f8fafc;
                    padding: 20px;
                    border-radius: 10px;
                    margin-top: 20px;
                }

                .details p {
                    margin: 10px 0;
                }

                .btn {
                    display: inline-block;
                    margin-top: 20px;
                    padding: 12px 25px;
                    background: #1e3a5f;
                    color: white;
                    text-decoration: none;
                    border-radius: 6px;
                }

                .btn:hover {
                    background: #162d4a;
                }

            </style>

        </head>

        <body>

            <div class="success-box">

                <div class="success-icon">✓</div>

                <h1>Registration Successful!</h1>

                <p>Your visitor registration has been completed successfully.</p>

                <div class="details">

                    <p>
                        <strong>Visitor ID:</strong>
                        <?php echo $visitor_id; ?>
                    </p>

                    <p>
                        <strong>Name:</strong>
                        <?php echo htmlspecialchars($full_name); ?>
                    </p>

                    <p>
                        <strong>Visit Date:</strong>
                        <?php echo htmlspecialchars($visit_date); ?>
                    </p>

                </div>

                <a href="visitor_pass.php?id=<?php echo $visitor_id; ?>" class="btn">
                    🎫 Generate Visitor Pass
                </a>

                <br><br>

                <a href="index.php" class="btn">
                     Register Another Visitor
                </a>

            </div>

        </body>

        </html>

        <?php

    } else {

        echo "Error: " . $conn->error;

    }

}

?>