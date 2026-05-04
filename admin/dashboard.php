<?php
session_start();
// TODO: Add admin role check later
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard - Street Cred</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <h1>Admin Dashboard</h1>
<p>Welcome, Admin</p>

<div class="container">

    <div class="card">
        <h2>Reports</h2>

        <table>
            <tr> // Report Management 
                <th>Problem</th>
                <th>Location</th>
                <th>Status</th>
                <th>Action</th>
            </tr>

          
            <tr>
                <td>No data yet</td>  // Problem Column
                <td>No data yet</td>  // Location Column
                <td><span class="status pending">Pending</span></td>   // Status Tracking System 
                <td><button>Update</button></td>
            </tr>

        </table>
    </div>

</div>


<h1>Admin Dashboard</h1>
<p>Welcome, Admin</p>


    

</body>
</html>
