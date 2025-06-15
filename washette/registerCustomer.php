<?php

    require_once('classes/database.php');
    $con = new database();

    $sweetAlertConfig = "";

    if (isset($_POST['register'])){
     
      $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
      $firstname = $_POST['first_name'];
      $lastname = $_POST['last_name'];

      $userID = $con->signupCustomer($password, $firstname, $lastname);
      
      if ($userID) {
        $sweetAlertConfig = "
        <script>
        Swal.fire({
          icon: 'success',
          title: 'Registration Successful',
          text: 'You have successfully registered a new customer.',
          confirmButtonText: 'OK'
        }).then(() => {
          window.location.href = 'adminHome.html';
        });
        </script>
        ";
      } else {
        $sweetAlertConfig = "
         <script>
        Swal.fire({
          icon: 'error',
          title: 'Registration Failed',
          text: 'An error occurred during registration. Please try again.',
          confirmButtonText: 'OK'
        });
        </script>"
        
        ;
      }
    }
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Register Customer - Washette Laundromat</title>
    <link rel="icon" type="image/png" href="img/icon.png" />

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous" />
    
    <!-- SweetAlert2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet" />
    
    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- FontAwesome Icons -->
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
      integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
      crossorigin="anonymous"
      referrerpolicy="no-referrer"
    />
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Arimo:ital,wght@0,400..700;1,400..700&family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap"
      rel="stylesheet"
    />
    <!-- Custom CSS -->
    <link rel="stylesheet" href="style.css" />
  </head>

  <body>
    <!-- particles background -->
    <div
      id="tsparticles"
      style="
        position: fixed;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        z-index: -1;
      "
    ></div>
    <!-- register form -->
    <div class="glass-card text-center">
      <h2>Register Customer</h2>
      <!-- Registration Form -->
      <!-- Start of Form -->
      <form id="registrationForm" method="POST" action="" >
        <!-- Input fields for registration -->
        <!-- First Name -->
        <div class="mb-3 text-start">
          <input name="first_name" type="text" class="form-control" id="firstname" placeholder="Firstname" required />
        </div>
        <!-- Last Name -->
        <div class="mb-3 text-start">
          <input name="last_name" type="text" class="form-control" id="lastname" placeholder="Last name" required
          />
        </div>
        <!-- Password input with toggle visibility -->
        <div class="mb-3 text-start position-relative">
          <input type="password" class="form-control" id="password" placeholder="Password" required />
          <button type="button" id="togglePassword" tabindex="-1" style="position: absolute; top: 50%; right: 12px; transform: translateY(-50%); background: none; border: none; padding: 0; outline: none;">
            <i class="fa-regular fa-eye" id="eyeIconPassword" style="color: #6c8b8b; font-size: 1.2em;"></i>
          </button>
        </div>
        <!-- Confirm Password input with toggle visibility -->
        <div class="mb-4 text-start position-relative">
          <input name="password" type="password" class="form-control" id="confirmPassword" placeholder="Confirm password" required />
          <button type="button" id="toggleConfirmPassword" tabindex="-1"
            style="position: absolute; top: 50%; right: 12px; transform: translateY(-50%); background: none; border: none; padding: 0; outline: none;">
            <i class="fa-regular fa-eye" id="eyeIconConfirm" style="color: #6c8b8b; font-size: 1.2em;"></i>
          </button>
        </div>
        <!-- Submit button -->
        <button name="register" type="submit" class="btn btn-washette mt-1">Register</button>
      <!-- End of Form -->
      </form>
    </div>

    <!-- scripts for frontend functionality -->
    <script src="userscript.js"></script>
    <script>
      // Toggle password visibility for password
      const passwordInput = document.getElementById('password');
      const togglePassword = document.getElementById('togglePassword');
      const eyeIconPassword = document.getElementById('eyeIconPassword');
      togglePassword.addEventListener('click', function () {
        const type = passwordInput.type === 'password' ? 'text' : 'password';
        passwordInput.type = type;
        eyeIconPassword.classList.toggle('fa-eye');
        eyeIconPassword.classList.toggle('fa-eye-slash');
      });

      // Toggle password visibility for confirm password
      const confirmPasswordInput = document.getElementById('confirmPassword');
      const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
      const eyeIconConfirm = document.getElementById('eyeIconConfirm');
      toggleConfirmPassword.addEventListener('click', function () {
        const type = confirmPasswordInput.type === 'password' ? 'text' : 'password';
        confirmPasswordInput.type = type;
        eyeIconConfirm.classList.toggle('fa-eye');
        eyeIconConfirm.classList.toggle('fa-eye-slash');
      });
    </script>

    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO"
      crossorigin="anonymous"
    ></script>

    <script src="https://cdn.jsdelivr.net/npm/tsparticles@2.11.1/tsparticles.bundle.min.js"></script>
    <script src="/particles.js"></script>

    <!-- SweetAlert2 -->
    <?php echo $sweetAlertConfig; ?>
  </body>
</html>
