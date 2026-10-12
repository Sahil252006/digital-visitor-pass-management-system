
<?php
require_once 'config/database.php';

// Get filter values
$search = trim($_GET['search'] ?? '');
$visitDate = trim($_GET['visit_date'] ?? '');
$purpose = trim($_GET['purpose'] ?? '');

// Build the visitor query
$sql = "SELECT id, full_name, email, phone, purpose,
               visit_date, created_at
        FROM visitors
        WHERE 1=1";

$params = [];
$types = "";

// Search by visitor ID, name, email or phone
if ($search !== '') {
    $sql .= " AND (
        CAST(id AS CHAR) LIKE ?
        OR full_name LIKE ?
        OR email LIKE ?
        OR phone LIKE ?
    )";

    $term = "%" . $search . "%";

    $params[] = $term;
    $params[] = $term;
    $params[] = $term;
    $params[] = $term;

    $types .= "ssss";
}

// Filter by visit date
if ($visitDate !== '') {
    $sql .= " AND visit_date = ?";
    $params[] = $visitDate;
    $types .= "s";
}

// Filter by purpose
if ($purpose !== '') {
    $sql .= " AND purpose = ?";
    $params[] = $purpose;
    $types .= "s";
}

// Show newest visitors first
$sql .= " ORDER BY id DESC";

// Prepare and execute the query
$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("Unable to prepare visitor query.");
}

if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

if (!$stmt->execute()) {
    die("Unable to fetch visitors.");
}

$result = $stmt->get_result();

// Count visitors matching the current filters
$totalVisitors = $result->num_rows;

// Get available purposes for the dropdown
$purposeResult = $conn->query(
    "SELECT DISTINCT purpose
     FROM visitors
     WHERE purpose IS NOT NULL AND purpose != ''
     ORDER BY purpose"
);

if (!$purposeResult) {
    die("Unable to load visitor purposes.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Visitor Management Dashboard</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 30px;
            font-family: Arial, sans-serif;
            background: #f1f5f9;
            color: #1e293b;
        }

        .container {
            max-width: 1200px;
            margin: auto;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
            flex-wrap: wrap;
            margin-bottom: 25px;
        }

        h1 {
            margin: 0 0 8px;
            color: #1e3a5f;
        }

        .subtitle {
            color: #64748b;
            margin: 0;
        }

        .btn {
            display: inline-block;
            padding: 11px 16px;
            border-radius: 6px;
            background: #1e3a5f;
            color: white;
            text-decoration: none;
            border: none;
            cursor: pointer;
            font-size: 14px;
        }

        .btn:hover {
            background: #294d78;
        }

        .summary {
            background: white;
            padding: 22px;
            border-radius: 10px;
            box-shadow: 0 4px 15px #0000000a;
            margin-bottom: 25px;
            max-width: 280px;
        }

        .summary p {
            margin: 0 0 10px;
            color: #64748b;
        }

        .count {
            font-size: 32px;
            font-weight: bold;
            color: #1e3a5f;
        }

        .table-card {
            background: white;
            padding: 22px;
            border-radius: 10px;
            box-shadow: 0 4px 15px #0000000a;
        }

        .table-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
            flex-wrap: wrap;
            margin-bottom: 18px;
        }

        .table-top h2 {
            margin: 0;
        }

        .filter-form {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .filter-form input,
        .filter-form select {
            padding: 11px;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            background: white;
            font-family: Arial, sans-serif;
            font-size: 13px;
            min-width: 150px;
        }

        .filter-form input[name="search"] {
            width: 240px;
        }

        .table-wrap {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            white-space: nowrap;
        }

        th, td {
            padding: 14px 12px;
            text-align: left;
            border-bottom: 1px solid #e2e8f0;
            font-size: 14px;
        }

        th {
            background: #1e3a5f;
            color: white;
        }

        tbody tr:hover {
            background: #f8fafc;
        }

        .empty {
            padding: 25px;
            text-align: center;
            color: #64748b;
        }

        .results-info {
            margin: 0 0 15px;
            color: #64748b;
            font-size: 14px;
        }

        @media (max-width: 700px) {
            body {
                padding: 15px;
            }

            .table-card {
                padding: 12px;
            }

            .filter-form {
                width: 100%;
            }

            .filter-form input,
            .filter-form select,
            .filter-form input[name="search"] {
                width: 100%;
            }
        }
    </style>
</head>

<body>
<div class="container">

    <div class="header">
        <div>
            <h1>Visitor Dashboard</h1>
            <p class="subtitle">
                Digital Visitor Pass Management System
            </p>
        </div>

        <a href="index.php" class="btn">+ Register Visitor</a>
    </div>

    <div class="summary">
        <p>Matching Visitors</p>
        <div class="count">
            <?php echo $totalVisitors; ?>
        </div>
    </div>

    <div class="table-card">

        <div class="table-top">
            <h2>All Visitors</h2>

            <form method="GET"
                  action="dashboard.php"
                  class="filter-form">

                <input
                    type="text"
                    name="search"
                    placeholder="Search name, email, phone or ID"
                    value="<?php echo htmlspecialchars($search, ENT_QUOTES, 'UTF-8'); ?>"
                >

                <input
                    type="date"
                    name="visit_date"
                    aria-label="Filter by visit date"
                    value="<?php echo htmlspecialchars($visitDate, ENT_QUOTES, 'UTF-8'); ?>"
                >

                <select name="purpose" aria-label="Filter by purpose">
                    <option value="">All Purposes</option>

                    <?php while ($p = $purposeResult->fetch_assoc()): ?>
                        <option
                            value="<?php echo htmlspecialchars($p['purpose'], ENT_QUOTES, 'UTF-8'); ?>"
                            <?php echo ($purpose === $p['purpose']) ? 'selected' : ''; ?>
                        >
                            <?php echo htmlspecialchars($p['purpose'], ENT_QUOTES, 'UTF-8'); ?>
                        </option>
                    <?php endwhile; ?>
                </select>

                <button type="submit" class="btn">
                    Apply Filters
                </button>

                <a href="dashboard.php" class="btn">
                    Reset
                </a>

            </form>
        </div>

        <p class="results-info">
            Showing <?php echo $totalVisitors; ?> matching visitor(s).
        </p>

        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Visitor ID</th>
                        <th>Full Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Purpose</th>
                        <th>Visit Date</th>
                        <th>Registered At</th>
                        <th>Digital Pass</th>
                    </tr>
                </thead>

                <tbody>
                <?php if ($totalVisitors > 0): ?>

                    <?php while ($visitor = $result->fetch_assoc()): ?>
                        <tr>
                            <td>
                                <?php echo (int) $visitor['id']; ?>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($visitor['full_name'], ENT_QUOTES, 'UTF-8'); ?>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($visitor['email'], ENT_QUOTES, 'UTF-8'); ?>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($visitor['phone'], ENT_QUOTES, 'UTF-8'); ?>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($visitor['purpose'], ENT_QUOTES, 'UTF-8'); ?>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($visitor['visit_date'], ENT_QUOTES, 'UTF-8'); ?>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($visitor['created_at'], ENT_QUOTES, 'UTF-8'); ?>
                            </td>

                            <td>
                                <a
                                    class="btn"
                                    href="visitor_pass.php?id=<?php echo (int) $visitor['id']; ?>"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                >View Pass</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>

                <?php else: ?>

                    <tr>
                        <td colspan="8" class="empty">
                            No visitors match your filters.
                            Try changing your search or click Reset.
                        </td>
                    </tr>

                <?php endif; ?>
                </tbody>
            </table>
        </div>

    </div>

</div>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
