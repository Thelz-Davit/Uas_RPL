<?php
$server = "localhost";
$user = "root";
$pass = "";
$database   = "kelek";

$koneksi = mysqli_connect($server, $user, $pass, $database);
if (!$koneksi) { //cek koneksi
    die("Tidak bisa terkoneksi ke database");
}


if (isset($_SESSION['logged_in'])) {
    header('location: index.php');
    exit;
}

if (isset($_POST['login_btn'])) {
    $username = $_POST['username'];
    $upassword = ($_POST['upassword']);

    $query = "SELECT id, username, email, upassword, notelp, alamat FROM adminn
    WHERE username = ? AND upassword = ? LIMIT 1";

    $stmt_login = $koneksi->prepare($query);
    $stmt_login->bind_param('ss', $username, $upassword);

    if ($stmt_login->execute()) {
        $stmt_login->bind_result(
            $id,
            $username,
            $email,
            $upassword,
            $notelp,
            $alamat
        );
        $stmt_login->store_result();

        if ($stmt_login->num_rows() == 1) {
            $stmt_login->fetch();

            $_SESSION['id'] = $id;
            $_SESSION['username'] = $username;
            $_SESSION['email'] = $email;
            $_SESSION['notelp'] = $notelp;
            $_SESSION['alamat'] = $alamat;
            $_SESSION['logged_in'] = true;

            header('location:landingpage.php?message=Logged in successfully');
        } else {
            header('location:login.php?error=Could not verify your account');
        }
    } else {
        // Error
        header('location: login.php?error=Something went wrong!');
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LOGIN FORM</title>
    <link rel="stylesheet" href="login.css">
</head>

<body>
    <section>
        <div class="form-box">
            <div class="form-value">
                <form id="login-form" method="POST" action="login.php">
                    <h2>Login</h2>
                    <!-- Input Area -->
                    <div class="inputbox">
                        
                        <input type="username" name="username" required>
                        <label for="">Username</label>
                    </div>

                    <div class="inputbox">
                        
                        <input type="password" name="upassword" id="myInput" required>
                        <label for="">Password</label>
                        <script>
                            function myFunction() {
                                var x = document.getElementById("myInput");
                                if (x.type === "password") {
                                    x.type = "text";
                                } else {
                                    x.type = "password";
                                }

                            }
                        </script>
                        <i class="bx bx-key"></i>
                    </div>

                    <button name="login_btn">Log in</button>
                    <div class="register">
                        <p>Don't have a account <a href="register.php">Register</a></p>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>

</body>

</html>