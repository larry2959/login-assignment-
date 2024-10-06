<?php
class contents{
    public function RegisterForm(){
?>
<div class="container d-flex flex-column align-items-center justify-content-center text-center">
    <div class="col-4 bg-secondary p-5 rounded">
        <h2 class="text-white">User Registration</h2>
        <form method="post" action="processes.php">
            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="username" placeholder="Enter your username" name="username" required>
                <label for="username"><i class="fas fa-user"></i> Username</label>
            </div>
            <div class="form-floating mb-3">
                <input type="email" class="form-control" id="email" placeholder="name@example.com" name="email" required>
                <label for="email"><i class="fas fa-envelope"></i> Email address</label>
            </div>
            <div class="form-floating mb-3">
                <input type="password" class="form-control" id="password" placeholder="Enter your password" name="password" required>
                <label for="password"><i class="fas fa-lock"></i> Password</label>
            </div>
            <div class="form-floating mb-3">
                <input type="password" class="form-control" id="confirmPassword" placeholder="Confirm your password" name="confirm_password" required>
                <label for="confirmPassword"><i class="fas fa-lock"></i> Confirm Password</label>
            </div>
            <div class="d-grid">
                <button type="submit" class="btn btn-primary btn-lg" name="reg-user">Register</button>
            </div>
        </form>
    </div>
</div>
        <?php
    }
    public function table(){
        include '../pdo.php';
        ?>
        <div class="container">
            <div class="row">
                <table class="table table-striped col-4">
                    <thead>
                        <th>User ID</th>
                        <th>Full Name</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>2FA Status</th>
                    </thead>
                    <tbody>
                    <?php
                        try {
                            // Assuming you have a valid PDO connection instance in $pdo
                            $sql = "SELECT * FROM users";
                            $stmt = $conn->query($sql);
        
                            if ($stmt->rowCount() > 0) {
                                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                    $state = "Disabled";
                                    $color = "bg-danger text-white rounded p-1 fw-bold";
        
                                    // Check the user's status
                                    if ($row['2FAStatus'] == 1) {  // Use == for comparison
                                        $state = "Enabled";
                                        $color = "bg-success text-white rounded p-1 fw-bold";
                                    }
                                    
                                    // Output your table row here, for example:
                                    echo "<tr'>
                                    <td>{$row['UserID']}</td>
                                    <td>{$row['FullName']}</td>
                                    <td>{$row['UserName']}</td>
                                    <td>{$row['Email']}</td>
                                    <td><span class='".$color."'>$state</span></td>
                                    </tr>";
                                }
                            } else {
                                // No rows found
                                echo "<tr><td colspan='5'>No users found</td></tr>";
                            }
                        } catch (PDOException $e) {
                            // Catch and display any PDO errors
                            echo 'Query failed: ' . $e->getMessage();
                            die();
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php
    }
    public function LoginForm(){
        ?>
        <div class="container d-flex flex-column align-items-center justify-content-center text-center">
            <div class="col-4 bg-secondary p-5 rounded">
                <h2 class="text-white">User Login</h2>
                <form method="post" action="processes.php">
                    <div class="form-floating mb-3">
                        <input type="email" class="form-control" id="email" placeholder="name@example.com" name="email" required>
                        <label for="email"><i class="fas fa-envelope"></i>Enter your Email address</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="password" class="form-control" id="password" placeholder="Enter your password" name="password" required>
                        <label for="password"><i class="fas fa-lock"></i>Enter your Password</label>
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-lg" name="login-user">Login</button>
                    </div>
                </form>
            </div>
        </div>
        <?php
    }
    public function TwoFAForm(){
        ?>
        <div class="container d-flex flex-column align-items-center justify-content-center text-center">
            <div class="col-4 bg-secondary p-5 rounded">
                <h2 class="text-white">Two-Factor Authentication</h2>
                <form method="post" action="processes.php">
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="code" placeholder="Enter your 6-digit code" name="code" required>
                        <label for="code"><i class="fas fa-key"></i> Enter your 6-digit code</label>
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-lg" name="2fa-user">Verify</button>
                    </div>
                </form>
            </div>
        </div>
        <?php
    }
}
