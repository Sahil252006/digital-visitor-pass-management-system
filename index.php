<?php
require_once "config/database.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Digital Visitor Pass Management System</title>

    <link rel="stylesheet" href="css/style.css">
</head>

<body>

    <div class="container">

        <h1>Digital Visitor Pass Management System</h1>

        <p class="subtitle">Visitor Registration</p>

        <form action="register_visitor.php" method="POST">

            <label for="full_name">Full Name</label>
            <input
                type="text"
                id="full_name"
                name="full_name"
                placeholder="Enter visitor name"
                required
            >

            <label for="email">Email</label>
            <input
                type="email"
                id="email"
                name="email"
                placeholder="Enter email address"
                required
            >

            <label for="phone">Phone Number</label>
            <input
                type="tel"
                id="phone"
                name="phone"
                placeholder="Enter phone number"
                required
            >

            <label for="purpose">Purpose of Visit</label>
            <input
                type="text"
                id="purpose"
                name="purpose"
                placeholder="Enter purpose of visit"
                required
            >

            <label for="visit_date">Visit Date</label>
            <input
                type="date"
                id="visit_date"
                name="visit_date"
                required
            >

            <button type="submit">Register Visitor</button>

        </form>

    </div>

</body>
</html>