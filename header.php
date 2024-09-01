<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="styles/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* HEADER */
        .header {
            background-color: #007bff;
            color: white;
            text-align: center;
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1000;
            border: none;=
        }

        .header a {
            color: #00aced;
            text-decoration: none;
        }

        .header a:hover {
            text-decoration: underline;
        }

        /* Adjust the top margin of the container to account for the fixed header */
        .container {
            margin-top: 60px;
        }

        .header-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 20px;
        }

        .header-right {
            display: flex;
            align-items: center;
        }

        .username {
            margin-right: 20px;
        }

        .logout-button {
            background-color: #f44336;
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
        }

        .logout-button:hover {
            background-color: #d32f2f;
        }

        /* Dropdown Menu */
        .dropdown {
            position: relative;
            display: inline-block;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #f9f9f9;
            min-width: 160px;
            box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
            z-index: 1;
        }

        .dropdown-content a {
            color: black;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
        }

        .dropdown-content a:hover {
            background-color: #f1f1f1;
        }

        .dropdown:hover .dropdown-content {
            display: block;
        }

        .dropdown:hover .dropbtn {
            background-color: #3e8e41;
        }

        .github-icon {
            font-size: 24px;
            color: white;
            margin-left: 20px;
        }

        .github-icon:hover {
            color: #00aced;
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="header-container">
            <a href="index.php" class="logo"><img src="images/logo.png" alt="Guelma Pad"></a>
            <div class="header-right">
                <?php if (basename($_SERVER['PHP_SELF']) == 'home.php' && isset($_SESSION['user_id'])): ?>
                    <span class="username">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></span>
                    <form method="POST" action="home.php" style="display:inline;">
                        <button type="submit" name="logout" class="logout-button">Logout</button>
                    </form>
                <?php else: ?>
                    <div class="dropdown">
                        <a href="home.php"><button class="dropbtn">Account</button></a>
                        <div class="dropdown-content">
                            <a href="login.php">Login</a>
                            <a href="register.php">Register</a>
                        </div>
                    </div>
                <?php endif; ?>
                <a href="https://github.com/oussxh" target="_blank" class="github-icon">
                    <i class="fab fa-github"></i>
                </a>
            </div>
        </div>
    </header>
</body>
</html>
