<?php include ('./config/conn.php') ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login System with Email Verification</title>
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
 <link rel="stylesheet" href="./css/login-form.css">
</head>
<body class="">
    
    <div class="main">

        <!-- Login Area -->
        <div class="login-container">
            <div class="login-form" id="loginForm">
                <h2 class="text-center">Welcome Back!</h2>
                <p class="text-center">Fill your login details.</p>
                <form action="./controller/login.php" method="POST">
                    <div class="form-group">
                        <label for="username">Username:</label>
                        <input type="text" class="form-control" id="username" name="username">
                    </div>
                    <div class="form-group">
                        <label for="password">Password:</label>
                        <input type="password" class="form-control" id="password" name="password">
                    </div>
                    <p>No Account? Register <span  class="switch-form-link" onclick="showRegistrationForm()">Here.</span></p>
                    <button type="submit" class="btn btn-dark login-btn form-control" >Login</button>
                </form>
            </div>

        </div>



        <!-- Registration Area -->
        <div class="registration-form" id="registrationForm">
            <h2 class="text-center">Registration Form</h2>
            <p class="text-center">Fill in you personal details.</p>
            <form action="./controller/add-user.php" method="POST">
                
                <div class="form-group registration row">
                    
                    <div class="col-6">
                        <label for="firstName">First Name:</label>
                        <input type="text" class="form-control" id="firstName" name="first_name">
                    </div>
                    
                    <div class="col-6">
                        <label for="lastName">Last Name:</label>
                        <input type="text" class="form-control" id="lastName" name="last_name">
                    </div>
                </div>
                <div class="form-group registration row">
                    <div class="col-5">
                        <label for="contactNumber">Contact Number:</label>
                        <input type="number" class="form-control" id="contactNumber" name="contact_number" maxlength="11">
                    </div>
                    <div class="col-7">
                        <label for="email">Email:</label>
                        <input type="text" class="form-control" id="email" name="email">
                    </div>
                </div>
                <div class="form-group registration">
                    <label for="registerUsername">Username:</label>
                    <input type="text" class="form-control" id="registerUsername" name="username">
                </div>
                <div class="form-group registration">
                    <label for="registerPassword">Password:</label>
                    <input type="password" class="form-control" id="registerPassword" name="password">
                </div>
                <p>Already have an account? Login <span class="switch-form-link" onclick="showLoginForm()">Here.</span></p>
                <button type="submit" class="btn btn-dark login-register form-control" name="register">Register</button>
            </form>
        </div>
    </div>
<script src="./javascript/login-form.js"></script>

    <!-- Bootstrap Js -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"></script>

</body>
</html>