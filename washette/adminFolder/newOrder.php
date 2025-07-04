<?php

// Start the session
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Checks if there is a user logged in
if (!isset($_SESSION['adminID']) || isset($_SESSION['CustomerID'])) {
  
  // If not logged in, redirect to login page
  if (!isset($_SESSION['adminID'])) {
    header("Location: ../washette/loginAdmin.php");
    exit();
  } else {
    // If a customer is logged in, redirect to user home page
    header("Location: ../washette/userFolder/userHome.php");
    exit();
  }

}

// Connect to Database
require_once('../classes/database.php');
$con = new database();

// Set Customer Name
$_SESSION['adminName'] = $_SESSION['adminFN'] . " " . $_SESSION['adminLN'];

// Initialize a variable to hold the SweetAlert configuration
$sweetAlertConfig = "";

// Check if the confirm order form is submitted
if (isset($_POST['placeOrderBtn'])) {
     
      // Get the form data
      $customerID = $_POST['selectedCustomerId'];
      $admin_id = $_SESSION['admin_ID'];
      $paymentMethodID = $_POST['selectedPaymentMethodId'];
      $subtotal = $_POST['subtotal'];
      $discount = $_POST['discount'];
      $totalAmount = $_POST['total_amount'];

      // Insert into the orders table
      $userID = $con->newOrder($customerID, $admin_id, $paymentMethodID, $subtotal, $discount, $totalAmount);

      // Get the latest transaction ID for the customer (Last inserted order)
      $transactionID = $con->getLatestTransactionID($customerID);
      $transactionID = $transactionID['TransactionID'];

      // For each selected service, insert into the transaction details
      foreach (json_decode($_POST['selectedServices'], true) as $service) {
        $serviceID = $service['id'];
        $quantity = $service['quantity'];
        $price = $service['price'];
        
        // Insert into transaction details
        $userID2 = $con->insertTransactionDetails($transactionID, $serviceID, $quantity, $price);
      }

      if ($userID && $transactionID && $userID2) {
        $sweetAlertConfig = "
        <script>
        Swal.fire({
          icon: 'success',
          title: 'Order Placed Successfully',
          text: 'You have successfully placed a new order.',
          confirmButtonText: 'OK'
        }).then(() => {
          window.location.href = '/adminFolder/adminHome.php';
        });
        </script>
        ";
      } else {
        $sweetAlertConfig = "
         <script>
        Swal.fire({
          icon: 'error',
          title: 'Order Placement Failed',
          text: 'An error occurred while placing the order. Please try again.',
          confirmButtonText: 'OK'
        });
        </script>
        ";
      }
    }

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>New Order - Washette Laundromat</title>
    <link rel="icon" type="image/png" href="/img/icon.png" />
    <!-- Bootstrap CSS -->
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css"
      rel="stylesheet"
      integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT"
      crossorigin="anonymous"
    />
    <link rel="stylesheet" href="bootstrap-5.3.3-dist/css/bootstrap.css">
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="package/dist/sweetalert2.css">
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
    <link rel="stylesheet" href="admin.css" />
    <style>
      .dropdown-arrow-wrapper {
        position: relative;
        display: flex;
        align-items: center;
      }
      .dropdown-arrow-wrapper input[type="text"], 
      .dropdown-arrow-wrapper input.form-control {
        padding-right: 2.5rem;
      }
      .dropdown-btn {
        background: none;
        border: none;
        position: absolute;
        right: 0.5rem;
        top: 50%;
        transform: translateY(-50%);
        z-index: 2;
        color: #395C58;
        font-size: 1.1em;
        padding: 0;
        cursor: pointer;
      }
      .custom-dropdown-list {
        display: none;
        position: absolute;
        left: 0;
        right: 0;
        top: 110%;
        z-index: 10;
        background: #fff;
        border: 1px solid #ccc;
        border-radius: 0.5rem;
        max-height: 180px;
        overflow-y: auto;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
      }
      .custom-dropdown-list.show {
        display: block;
      }
      .custom-dropdown-list .dropdown-item {
        padding: 0.5rem 1rem;
        cursor: pointer;
        white-space: nowrap;
      }
      .custom-dropdown-list .dropdown-item:hover {
        background: #e6f7f5;
      }
    </style>
  </head>
  <body>
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
    <div class="order-card-container">
      <div class="card p-4 mb-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <div class="card-title mb-0">
            <i class="fas fa-plus-circle me-2"></i>New Laundry Order
          </div>
          <button
            type="button"
            class="btn btn-sm filter-btn"
            style="background: #395c58; color: #baebe6"
            onclick="window.history.back();"
          >
            <i class="fas fa-arrow-left me-1"></i>Back
          </button>
        </div>

        <!-- New Order Form -->
        <form id="orderForm" method="POST" action="">
          <div class="mb-3">

            <!-- Customer Selection -->
            <label for="customerInput" class="form-label fw-bold">Customer Selection</label>
            <div class="dropdown-arrow-wrapper" style="position:relative;">
              <input class="form-control custom-dropdown" list="customerList" id="customerInput" placeholder="Type to search customer" autocomplete="off">
              <button type="button" class="dropdown-btn" tabindex="-1" id="customerDropdownBtn">
                <i class="fa fa-chevron-down"></i>
              </button>
              <div class="custom-dropdown-list" id="customerDropdownList">

                <?php
                // Fetch customers from the database
                $customers = $con->getAllCustomers();
                if ($customers) {
                ?>
                  
                <?php
                foreach ($customers as $customer) {
                    echo '<div class="dropdown-item" 
                              data-customer-id="' . htmlspecialchars($customer['CustomerID']) . '">'
                              . htmlspecialchars($customer['CustomerID']) . ' - '
                              . htmlspecialchars($customer['FullName']) 
                              . '</div>';
                }
                } else {
                  echo '<div class="dropdown-item">No customers available.</div>';
                }
                ?>
              </div>
            </div>

            <!-- Hidden input to store selected customer ID -->
            <input type="hidden" name="selectedCustomerId" id="selectedCustomerIdInput">

            <!-- End of Customer Selection -->
          </div>

          <!-- Service Type Selection -->
          <div class="mb-3">
            <label class="form-label fw-bold">Service Type(s)</label>
            <div id="serviceSelectors"></div>
            <button type="button" id="addServiceBtn" class="btn btn-outline-primary btn-sm mt-2">
              <i class="fa fa-plus"></i> Add Another Service
            </button>
          </div>

          <!-- Hidden input to store selected services -->
          <input type="hidden" name="selectedServices" id="selectedServicesInput">

          <div class="mb-3">

            <!-- Payment Method Selection -->
            <label for="paymentMethodInput" class="form-label fw-bold">Payment Method</label>
            <div class="dropdown-arrow-wrapper" style="position:relative;">
              <input class="form-control custom-dropdown" list="paymentMethodList" id="paymentMethodInput" placeholder="Type to search payment method" autocomplete="off">
              <button type="button" class="dropdown-btn" tabindex="-1" id="paymentMethodDropdownBtn">
                <i class="fa fa-chevron-down"></i>
              </button>
              <div class="custom-dropdown-list" id="paymentMethodDropdownList">

                <?php
                // Fetch payment methods from the database
                $paymentMethods = $con->getAllPaymentMethods();
                if ($paymentMethods) {
                ?>
                  <datalist id="paymentMethodList">
                    <?php
                    foreach ($paymentMethods as $method) {
                        echo '<option value="' . htmlspecialchars($method['PaymentMethodID']) . '">' . htmlspecialchars($method['PaymentMethodName']) . '</option>';
                    }
                    ?>
                  </datalist>
                <?php
                } else {
                  echo '<div class="dropdown-item">No payment methods available.</div>';
                }
                ?>
              </div>
            </div>

            <!-- Hidden input to store selected payment method ID -->
            <input type="hidden" name="selectedPaymentMethodId" id="selectedPaymentMethodIdInput">

            <!-- End of Payment Method Selection -->
          </div>

          <!-- Subtotal Display -->
          <div class="mb-3">
            <label class="form-label fw-bold">Subtotal</label>
            <div>
              <span id="orderSubtotal" name="subtotal" class="fs-5 fw-semibold">₱0.00</span>
            </div>
          </div>

            <!-- Hidden input to store subtotal -->
            <input type="hidden" name="subtotal" id="subtotalInput">

          <!-- End of Subtotal Display -->

          <!-- Discount Input -->
          <div class="mb-3">
            <label for="discountInput" class="form-label fw-bold">Discount (₱)</label>
            <input type="number" class="form-control" id="discountInput" name="discount" value="0" min="0" step="0.01" placeholder="Enter discount amount">
          </div>
          <!-- End of Discount Input -->

          <!-- Total Amount Display -->
          <div class="mb-3">
            <label class="form-label fw-bold">Total Amount</label>
            <div>
              <span id="totalAmount" name="total_amount" class="fs-5 fw-semibold">₱0.00</span>
            </div>
          </div>

          <!-- Hidden input to store total amount -->
          <input type="hidden" name="total_amount" id="totalAmountInput">
          <!-- End of Total Amount Display -->

          <!-- Confirm Order Button -->
          <button type="submit" id="confirmOrderBtn" class="btn btn-success w-100 fw-bold" style="font-size:1.15rem;" disabled>
            Confirm Order
          </button>
          <!-- End of Confirm Order Button -->
          
          <!-- Include Bootstrap JS and SweetAlert2 -->
          <script src="./bootstrap-5.3.3-dist/js/bootstrap.js"></script>
          <script src="./package/dist/sweetalert2.js"></script>
          <?php echo $sweetAlertConfig; ?>

        </form>
        <!-- End of New Order Form -->

      </div>
    </div>
    
    <script src="userscript.js"></script>

    <script>

      // Dropdown logic for customer
      const customerInput = document.getElementById('customerInput');
      const customerDropdownBtn = document.getElementById('customerDropdownBtn');

      // Create dropdown list element
      // selectedCustomerId will hold the ID of the selected customer
      let selectedCustomerId = null;
      // Get the dropdown list element
      customerDropdownList.addEventListener('mousedown', function(e) {
        // Check if the clicked element is a dropdown item
        // If so, update the input value and close the dropdown
        if (e.target.classList.contains('dropdown-item')) {
          customerInput.value = e.target.textContent;
          // Set the selected customer ID from the data attribute
          selectedCustomerId = e.target.getAttribute('data-customer-id');
          customerDropdownList.classList.remove('show');
          customerInput.dispatchEvent(new Event('input'));
        }
      });

      // Handle click on dropdown button to toggle visibility
      customerDropdownBtn.addEventListener('click', function(e) {
        customerDropdownList.classList.toggle('show');
      });

      // Close dropdown when clicking outside
      document.addEventListener('mousedown', function(e) {
        if (!customerDropdownList.contains(e.target) && e.target !== customerDropdownBtn && e.target !== customerInput) {
          customerDropdownList.classList.remove('show');
        }
      });

              // Optionally, filter dropdown as user types
              customerInput.addEventListener('input', function() {
                const val = this.value.toLowerCase();
                Array.from(customerDropdownList.children).forEach(function(item) {
                  item.style.display = item.textContent.toLowerCase().includes(val) ? '' : 'none';
                });
              });

      // Dropdown logic for service
      const servicesData = [
        <?php
          // Output a JS array of services for use in dropdowns
          $services = $con->getAllServices();
          $jsArray = [];
          if ($services) {
            foreach ($services as $service) {
              $typeName = '';
              if ($service['ServiceType'] == 1) $typeName = 'Full Service & Drop-Off';
              elseif ($service['ServiceType'] == 2) $typeName = 'Self-Service';
              $jsArray[] = [
                'id' => $service['LaundryID'],
                'name' => $service['ServiceName'] ?? '',
                'type' => $typeName,
                'price' => $service['Price'] ?? 0
              ];
            }
          }
          echo implode(",", array_map(function($s) {
            return json_encode($s);
          }, $jsArray));
        ?>
      ];
      
      // Create service selectors dynamically
      const serviceSelectorsDiv = document.getElementById('serviceSelectors');
      const addServiceBtn = document.getElementById('addServiceBtn');
      const orderSubtotal = document.getElementById('orderSubtotal');
      
      // Function to create a new service selector
      function createServiceSelector(idx) {
      const wrapper = document.createElement('div');
      wrapper.className = 'input-group mb-2 service-selector-group';
    
      // Service dropdown
      const select = document.createElement('select');
      select.className = 'form-select service-select';
      select.style.maxWidth = '60%';
      select.innerHTML = '<option value="">Select a service...</option>' +
        servicesData.map((s, i) =>
          `<option value="${i}"><strong>${s.type}</strong> | ${s.name} (₱${parseFloat(s.price).toFixed(2)})</option>`
        ).join('');
    
      // Quantity input
      const qtyInput = document.createElement('input');
      qtyInput.type = 'number';
      qtyInput.className = 'form-control ms-2 service-qty';
      qtyInput.min = 1;
      qtyInput.value = 1;
      qtyInput.style.maxWidth = '80px';
    
      // Remove button
      const removeBtn = document.createElement('button');
      removeBtn.type = 'button';
      removeBtn.className = 'btn btn-danger btn-sm ms-2';
      removeBtn.innerHTML = '<i class="fa fa-times"></i>';
      removeBtn.onclick = function() {
        wrapper.remove();
        updateSubtotal();
        validateOrderForm();
      };
    
      wrapper.appendChild(select);
      wrapper.appendChild(qtyInput);
      wrapper.appendChild(removeBtn);
    
      // Update subtotal when service or quantity changes
      select.addEventListener('change', updateSubtotal);
      qtyInput.addEventListener('input', updateSubtotal);
    
      return wrapper;
    }
      
      // Function to update subtotal based on selected services
      function updateSubtotal() {
        let subtotal = 0;
        const selectedIndexes = [];
        document.querySelectorAll('.service-selector-group').forEach(group => {
          const select = group.querySelector('.service-select');
          const qtyInput = group.querySelector('.service-qty');
          const idx = select.value;
          const qty = parseInt(qtyInput.value) || 1;
          if (idx !== '' && !selectedIndexes.includes(idx)) {
            subtotal += (parseFloat(servicesData[idx].price) || 0) * qty;
            selectedIndexes.push(idx);
          }
        });
        orderSubtotal.textContent = '₱' + subtotal.toFixed(2);
        updateTotalAmount();
      }

      // Update total amount when discount changes
      function updateTotalAmount() {
        // Get subtotal as a number
        const subtotal = parseFloat(orderSubtotal.textContent.replace(/[^\d.]/g, '')) || 0;
        // Get discount as a number
        const discount = parseFloat(document.getElementById('discountInput').value) || 0;
        // Calculate total (never less than 0)
        const total = Math.max(subtotal - discount, 0);
        // Update total amount display and hidden input
        document.getElementById('totalAmount').textContent = '₱' + total.toFixed(2);
        document.getElementById('totalAmountInput').value = total.toFixed(2);
      }
      
      // Add initial selector
      serviceSelectorsDiv.appendChild(createServiceSelector(0));
      
      // Add new selector on button click
      addServiceBtn.addEventListener('click', function() {
        serviceSelectorsDiv.appendChild(createServiceSelector());
      });

      // Enable confirm button if at least one service is selected
      const confirmOrderBtn = document.getElementById('confirmOrderBtn');
      
      function validateOrderForm() {
        // Check customer
        const customerValid = customerInput.value.trim() !== '';
      
        // Check at least one valid service selected
        let serviceValid = false;
        document.querySelectorAll('.service-select').forEach(select => {
          if (select.value !== '') serviceValid = true;
        });
      
        confirmOrderBtn.disabled = !(customerValid && serviceValid);
      }
      
      // Listen for changes on customer input and service selects
      customerInput.addEventListener('input', validateOrderForm);
      serviceSelectorsDiv.addEventListener('change', validateOrderForm);
      
      // Also validate on add/remove service selector
      addServiceBtn.addEventListener('click', function() {
        setTimeout(validateOrderForm, 0);
      });
      serviceSelectorsDiv.addEventListener('click', function(e) {
        if (e.target.classList.contains('btn-danger')) {
          setTimeout(validateOrderForm, 0);
        }
      });
      
      // Initial validation
      validateOrderForm();

      // Handle form submission
      document.getElementById('orderForm').addEventListener('submit', function(e) {
    
      // Get selected customer
      const customerText = customerInput.value || '';
    
      // Get all selected services with quantities
      const selectedServicesArr = [];
      document.querySelectorAll('.service-selector-group').forEach(group => {
        const select = group.querySelector('.service-select');
        const qtyInput = group.querySelector('.service-qty');
        if (select.value !== '') {
          const idx = select.value;
          selectedServicesArr.push({
            ...servicesData[idx],
            quantity: parseInt(qtyInput.value) || 1
          });
        }
      });
    
      // Store as JSON in hidden input
      document.getElementById('selectedServicesInput').value = JSON.stringify(selectedServicesArr);

      // Get values from inputs
      document.getElementById('selectedCustomerIdInput').value = selectedCustomerId;
      document.getElementById('subtotalInput').value = orderSubtotal.textContent.replace(/[^\d.]/g, '');
      document.getElementById('totalAmountInput').value = document.getElementById('totalAmount').textContent.replace(/[^\d.]/g, '');
      document.getElementById('selectedPaymentMethodIdInput').value = document.getElementById('paymentMethodInput').value;
    
      });

    </script>

    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO"
      crossorigin="anonymous"
    ></script>
    <script src="https://cdn.jsdelivr.net/npm/tsparticles@2.11.1/tsparticles.bundle.min.js"></script>
    <script src="/unused/particles.js"></script>

  </body>
</html>