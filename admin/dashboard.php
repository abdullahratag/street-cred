<?php
session_start();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard - Street Cred</title>
    <link rel="stylesheet" href="../SteetCred/style.css">
</head>
<body>

<div class="admin-dashboard">

    <header class="admin-header">
        <div class="container">
            <h1>Admin Dashboard</h1>
            <p>Manage submitted infrastructure reports</p>
        </div>
    </header>

    <section class="admin-content">
        <div class="container">

            <div class="card">
                <h2>Reports</h2>

                <table>
                    <tr>
                        <th>Problem</th>
                        <th>Location</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>

                    <tr>
                        <td>No data yet</td>
                        <td>No data yet</td>
                        <td>
                            <span class="status-badge submitted">
                                Submitted
                            </span>
                        </td>
                        <td>
                            <button class="btn-primary">
                                Update
                            </button>
                        </td>
                    </tr>

                </table>

            </div>

        </div>
    </section>

</div>

</body>
</html>