<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        .error {
            color: red;
            font-family: arial;
            font-size: 14px;
        }
        .form {
            box-shadow: 15px 20px black;
            background-color: cyan;
            width: 500px;
            height: 500px;
            text-align: center;
            font-size: 26px;
            font-family: 'Algerian', cursive;
            margin: 50px auto;
            text-shadow: 5px 5px 5px rgba(7, 243, 172, 0.322);
            border: 5px solid red;
            border-radius: 50px;
            font-weight: lighter;
            color: blueviolet;
            border-style: groove;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }
    </style>
</head>
<body>
    <?php
    $nameerr = $gmailerr = $passworderr = $gendererr = "";
    $name = $gmail = $password = $gender = "";

    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        // Form validation
        $name = !empty($_POST['fname']) ? htmlspecialchars($_POST['fname']) : $nameerr = "Name is required";
        $gmail = !empty($_POST['gmail']) ? filter_var($_POST['gmail'], FILTER_SANITIZE_EMAIL) : $gmailerr = "Gmail is required";
        $password = !empty($_POST['password']) ? htmlspecialchars($_POST['password']) : $passworderr = "Password is required";
        $gender = !empty($_POST['gen']) ? htmlspecialchars($_POST['gen']) : $gendererr = "Gender is required";

        // Validate email format
        if (!filter_var($_POST['gmail'], FILTER_VALIDATE_EMAIL)) {
            $gmailerr = "Invalid Gmail format";
        }

        // If no errors, insert data into the database
        if (empty($nameerr) && empty($gmailerr) && empty($passworderr) && empty($gendererr)) {
            // Database connection and insertion
            $con = new mysqli("localhost", "root", "Jahid@111", "student");

            if ($con->connect_errno) {
                echo "Failed to connect: " . $con->connect_error;
                exit();
            }

            $stmt = $con->prepare("INSERT INTO student2 (Name, Gmail, Password, Gender) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $name, $gmail, $password, $gender);

            if ($stmt->execute()) {
                echo "Value inserted successfully";
            } else {
                echo "Error inserting value: " . $stmt->error;
            }

            $stmt->close();
            $con->close();
        }
    }
    ?>

    <div class="form">
        <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" onsubmit="return validateForm()">
            <h2>LOGIN FORM</h2>
            <div>
                <label for="fname">Name:</label>
                <input type="text" id="fname" name="fname">
                <span class="error">*<?php echo $nameerr ?></span><br><br>
            </div>
            <div>
                <label for="gmail">Gmail:</label>
                <input type="text" id="gmail" name="gmail">
                <span class="error">*<?php echo $gmailerr ?></span><br><br>
            </div>
            <div>
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" oninput="checkPassword()">
                <span class="error">*<?php echo $passworderr ?></span><br><br>
            </div>
            <div>
                <label for="gender">Gender:</label>
                <input type="radio" name="gen" id="Male" value="Male">
                <label for="Male">Male</label>
                <input type="radio" name="gen" id="Female" value="Female">
                <label for="Female">Female</label>
                <span class="error">*<?php echo $gendererr ?></span><br><br>
            </div>
            <div>
                <input type="submit" value="Submit" name="submit">
            </div>
        </form>
    </div>

    <script>
        function checkPassword() {
            var pass = document.getElementById("password").value;
            var len = pass.length;
            var special = /[!@#$%^&*()_+\-={};':"|,.<>?]/;
            var num = /\d/;
            var upp = /[A-Z]/;
            var low = /[a-z]/;

            var hasSpecial = special.test(pass);
            var hasNum = num.test(pass);
            var hasUpp = upp.test(pass);
            var hasLow = low.test(pass);

            if (len < 8 || !hasSpecial || !hasNum || !hasUpp || !hasLow) {
                document.getElementById("password").style.border = "3px solid red";
                return false;
            } else {
                document.getElementById("password").style.borderColor = "";
                return true;
            }
        }

        function validateForm() {
            return checkPassword(); // Ensure password validation before form submission
        }
    </script>
</body>
</html>
