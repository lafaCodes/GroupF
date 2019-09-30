<?php
// Initialize the session
session_start();

// Check if the user is already logged in, if yes then redirect him to welcome page
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    header('location: login.php');
    exit;
}

// Include config file
require_once 'config.php';

// Define variables and initialize with empty values
$username = $password = '';
$username_err = $password_err = '';

// Processing form data when form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Check if username is empty
    if (empty(trim($_POST['username']))) {
        $username_err = 'Please enter username.';
    } else {
        $username = trim($_POST['username']);
    }

    // Check if password is empty
    if (empty(trim($_POST['password']))) {
        $password_err = 'Please enter your password.';
    } else {
        $password = trim($_POST['password']);
    }

    // Validate credentials
    if (empty($username_err) && empty($password_err)) {
        // Prepare a select statement
        $sql = 'SELECT id, username, password FROM users WHERE username = ?';

        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, 's', $param_username);

            // Set parameters
            $param_username = $username;

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                // Store result
                mysqli_stmt_store_result($stmt);

                // Check if username exists, if yes then verify password
                if (mysqli_stmt_num_rows($stmt) === 1) {
                    // Bind result variables
                    mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password);
                    if (mysqli_stmt_fetch($stmt)) {
                        if (password_verify($password, $hashed_password)) {
                            // Password is correct, so start a new session
                            session_start();

                            // Store data in session variables
                            $_SESSION['loggedin'] = true;
                            $_SESSION['id'] = $id;
                            $_SESSION['username'] = $username;

                            // Redirect user to welcome page
                            header('location: login.php');
                        } else {
                            // Display an error message if password is not valid
                            $password_err = 'The password you entered was not valid.';
                        }
                    }
                } else {
                    // Display an error message if username doesn't exist
                    $username_err = 'No account found with that username.';
                }
            } else {
                echo 'Oops! Something went wrong. Please try again later.';
            }
        }

        // Close statement
        mysqli_stmt_close($stmt);
    }

    // Close connection
    mysqli_close($link);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Wholesalers Combined</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>

<h1>Welcome to Wholesalers Combined</h1>
<div class="imgcontainer">
    <img align="left" src="wholesalers.jpg" alt="Avatar" class="avatar">
    <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
</div>
<div class="container">
    <h2>Already a Member?</h2>
    <button onclick="document.getElementById('id01').style.display='block'" style="width:auto;">Login</button> &nbsp;&nbsp;
    <h2>Lets be a Family.</h2>
    <button onclick="document.getElementById('id02').style.display='block'" style="width:auto;">Register</button>
</div>
<div id="id01" class="modal">
    <!--Login form-->
    <form class="modal-content animate" action="/action_page.php">
        <div class="imgcontainer">
            <span onclick="document.getElementById('id01').style.display='none'" class="close" title="Close">x</span>
            <img src="ch1.png" alt="Avatar" class="avatar">
        </div>

        <div class="container">
            <p>
                <b>Username</b>
                <label for="uname">
                    <input type="text" placeholder="Enter Username" name="uname" id="uname" required>
                </label>
            </p>
            <p>
                <b>Password</b>
                <label for="passwd">
                    <input type="password" placeholder="Enter Password" name="psw" id="passwd" required>
                </label>
            </p>
            <label>
                <input type="checkbox" checked="checked">
                Remember me
            </label>
        </div>

        <div class="container" style="background-color:#f1f1f1">
            <button type="submit">Login</button>
            <button type="button" onclick="document.getElementById('id01').style.display='none'" class="cancelbtn">
                Cancel
            </button>
            <span class="psw">Forgot <a href="#">password?</a></span>
        </div>
    </form>

</div>
<div id="id02" class="modal">

    <!--Register form-->
    <form class="modal-content animate" action="/action_page.php">
        <div class="imgcontainer">
            <span onclick="document.getElementById('id02').style.display='none'" class="close" title="Close">x</span>
            <img src="wholesalers.jpg" alt="Avatar" class="avatar">
        </div>

        <div class="container">

            <h1>Sign Up</h1>
            <p>Please fill in this form to create an account.</p>
            <hr>

            <p>
                <b>Username</b>
                <label for="username">
                    <input type="text" placeholder="Enter Username" name="username" id="username" required>
                </label>
            </p>

            <p>
                <b>Full Name</b>
                <label for="name">
                    <input type="text" placeholder="Enter Full Name" name="name" id="name" required>
                </label>
            </p>

            <p>
                <b>Email</b>
                <label for="email">
                    <input type="text" placeholder="Enter Email" name="email" id="email" required>
                </label>
            </p>

            <p>
                <b>Password</b>
                <label for="psw">
                    <input type="password" placeholder="Enter Password" name="psw" id="psw" required>
                </label>
            </p>

            <p>
                <b>Repeat Password</b>
                <label for="psw-repeat">
                    <input type="password" placeholder="Repeat Password" name="psw-repeat" id="psw-repeat" required>
                </label>
            </p>

            <p>
                <b>Date of Birth</b>
                <label for="bday">
                    <input type="date" name="bday" id="bday" min="1900-01-01" max="2007-01-01" >
                </label>
            </p>

            <p>
                <b>Gender</b>
                <br>
                <label for="gender-male">
                    <input type="radio" name="gender" value="male" id="gender-male" checked> Male
                </label>
                <br>
                <label for="gender-female">
                    <input type="radio" name="gender" value="female"id="gender-female"> Female
                </label>
                <br>
                <label for="gender-other">
                    <input type="radio" name="gender" value="other"id="gender-other"> Other
                </label>
            </p>

            <p>
                <label>
                    <input type="checkbox" name="terms">
                    By creating an account you agree to our
                    <a href="#" style="color:dodgerblue">Terms  & Privacy</a>.
                </label>
            </p>
        </div>

        <div class="container" style="background-color:#f1f1f1">
            <button type="submit" class="signupbtn">Sign Up</button>
            <button type="button" onclick="document.getElementById('id02').style.display='none'" class="cancelbtn">
                Cancel
            </button>
        </div>
    </form>

</div>

<script>
    const modal = document.getElementById('id01');
    window.onclick = function (event) {
        if (event.target === modal) {
            modal.style.display = "none";
        }
    }
</script>
</body>

</html>
