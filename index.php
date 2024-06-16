<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <title>Login</title>
</head>
<body>
<div class="container">
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Login</h5>
            <form action="php/login.php" method="post" >
            <div class="form-group">
                    <label for="username">Username:</label>
                        <input type="text" name="username" class="form-control" placeholder="Enter an username" required>
                    <label for="password">Password</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Enter Your Password "required> </div>
                <button type="submit" class="btn btn-primary">Login</button>
            </form>
            <div class="text-center mt-3">
                <a href="register.php">Create an Account</a>
            </div>
        </div>
    </div>
</div>

    <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
