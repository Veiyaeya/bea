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

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Services - Washette Laundromat</title>
    <link rel="icon" type="image/png" href="/img/icon.png" />
    <!-- Bootstrap CSS -->
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css"
      rel="stylesheet"
      integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT"
      crossorigin="anonymous"
    />
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
        <div class="d-flex align-items-center gap-2">
          <span class="card-title fw-bold fs-4 mb-0">Premium Services</span>
          <a href="#" class="btn p-0 ms-2 service-action-btn" style="color:#395c58; font-size:1.7rem;" title="Add Premium Service" id="addPremiumBtn"
            onmousedown="this.classList.add('active')" onmouseup="this.classList.remove('active')" onmouseleave="this.classList.remove('active')">
            <i class="fa fa-plus-circle"></i>
          </a>
        </div>
        <a href="adminHome.php" class="back ms-3">
          <i class="fa-solid fa-circle-arrow-left"></i>&nbsp; Back
        </a>
      </div>
      <div class="table-responsive position-relative">
        <table class="transaction-table table-hover w-100" style="table-layout:fixed;">
          <thead class="table-light">
            <tr>
              <th style="width:28%;" class="text-nowrap">Service Name</th>
              <th style="width:44%;">Details</th>
              <th style="width:14%;" class="text-nowrap text-center">Edit</th>
              <th style="width:14%;" class="text-nowrap text-center">Delete</th>
            </tr>
          </thead>
          <tbody>
            <tr class="service-row" data-title="Dry Cleaning" data-desc="For delicate fabrics and garments. Includes stain removal and gentle care.">
              <td class="fw-bold align-middle" style="color:#395c58; border-radius:12px 0 0 12px;">Dry Cleaning</td>
              <td class="align-middle text-truncate" style="color:#395c58; max-width:1px;">
                For delicate fabrics and garments. Includes stain removal and gentle care.
              </td>
              <td class="text-center align-middle text-nowrap">
                <span class="service-action-icon" title="Edit" style="color:#395c58;">
                  <i class="fa-solid fa-pen-to-square"></i>
                </span>
              </td>
              <td class="text-center align-middle text-nowrap" style="border-radius:0 12px 12px 0;">
                <span class="service-action-icon" title="Delete" style="color:#395c58;">
                  <i class="fa-solid fa-trash"></i>
                </span>
              </td>
            </tr>
            <!-- Add more premium services here as needed -->
          </tbody>
        </table>
      </div>
    </div>
    <div class="card p-4 mb-4">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="d-flex align-items-center gap-2">
          <span class="card-title fw-bold fs-4 mb-0">Regular Services</span>
          <a href="#" class="btn p-0 ms-2 service-action-btn" style="color:#395c58; font-size:1.7rem;" title="Add Regular Service" id="addRegularBtn"
            onmousedown="this.classList.add('active')" onmouseup="this.classList.remove('active')" onmouseleave="this.classList.remove('active')">
            <i class="fa fa-plus-circle"></i>
          </a>
        </div>
      </div>
      <div class="table-responsive">
        <table class="transaction-table table-hover w-100" style="table-layout:fixed;">
          <thead class="table-light">
            <tr>
              <th style="width:28%;" class="text-nowrap">Service Name</th>
              <th style="width:44%;">Details</th>
              <th style="width:14%;" class="text-nowrap text-center">Edit</th>
              <th style="width:14%;" class="text-nowrap text-center">Delete</th>
            </tr>
          </thead>
          <tbody>
            <tr class="service-row" data-title="Wash & Fold" data-desc="Standard laundry service for everyday clothes. Washed, dried, and neatly folded.">
              <td class="fw-bold align-middle" style="color:#395c58; border-radius:12px 0 0 12px;">Wash & Fold</td>
              <td class="align-middle text-truncate" style="color:#395c58; max-width:1px;">
                Standard laundry service for everyday clothes. Washed, dried, and neatly folded.
              </td>
              <td class="text-center align-middle text-nowrap">
                <span class="service-action-icon" title="Edit" style="color:#395c58;">
                  <i class="fa-solid fa-pen-to-square"></i>
                </span>
              </td>
              <td class="text-center align-middle text-nowrap" style="border-radius:0 12px 12px 0;">
                <span class="service-action-icon" title="Delete" style="color:#395c58;">
                  <i class="fa-solid fa-trash"></i>
                </span>
              </td>
            </tr>
            <!-- Add more regular services here as needed -->
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Service Modal -->
  <div class="modal fade" id="serviceModal" tabindex="-1" aria-labelledby="serviceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="serviceModalLabel">Service Details</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <h5 id="modalServiceTitle" class="fw-bold mb-2"></h5>
          <div id="modalServiceDesc"></div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn" style="background:#395c58;color:#fff;border-radius:12px;" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Add Service Modal -->
  <div class="modal fade" id="addServiceModal" tabindex="-1" aria-labelledby="addServiceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <form id="addServiceForm" autocomplete="off">
          <div class="modal-header">
            <h5 class="modal-title" id="addServiceModalLabel" style="color:#395c58;">Add Service</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <label class="form-label fw-bold" id="addServiceTypeLabel" style="color:#395c58;"></label>
            </div>
            <div class="mb-3">
              <label for="serviceNameInput" class="form-label" style="color:#395c58;">Service Name</label>
              <input type="text" class="form-control" id="serviceNameInput" name="serviceName" placeholder="Enter service name" required />
            </div>
            <div class="mb-3">
              <label for="serviceDescInput" class="form-label" style="color:#395c58;">Details</label>
              <textarea class="form-control" id="serviceDescInput" name="serviceDesc" rows="3" placeholder="Enter details" required></textarea>
            </div>
          </div>
          <div class="modal-footer">
            <button type="submit"
              class="btn btn-washette"
              style="width:100%;background:#395c58;color:#fff;">
              Add Service
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <script src="userscript.js"></script>
  <script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO"
    crossorigin="anonymous"
  ></script>
  <script src="https://cdn.jsdelivr.net/npm/tsparticles@2.11.1/tsparticles.bundle.min.js"></script>
  <script src="/particles.js"></script>
  <script>
    document.addEventListener("DOMContentLoaded", function () {
      document.body.classList.remove("modal-blur-fadeout");
      var modals = document.querySelectorAll(".modal");
      modals.forEach(function (modal) {
        modal.addEventListener("show.bs.modal", function () {
          document.body.classList.remove("modal-blur-fadeout");
          document.body.classList.add("modal-blur");
        });
        modal.addEventListener("hide.bs.modal", function () {
          setTimeout(function () {
            if (!document.querySelectorAll(".modal.show").length) {
              document.body.classList.remove("modal-blur");
              document.body.classList.add("modal-blur-fadeout");
              setTimeout(function () {
                document.body.classList.remove("modal-blur-fadeout");
              }, 50);
            }
          }, 10);
        });
        modal.addEventListener("hidden.bs.modal", function () {
          if (!document.querySelector(".modal.show")) {
            document.body.classList.remove("modal-blur");
          }
        });
      });

      const observer = new MutationObserver(function () {
        if (document.body.classList.contains("modal-open")) {
          document.body.classList.remove("modal-open");
        }
        if (document.body.style.overflow === "hidden") {
          document.body.style.overflow = "";
        }
        if (
          document.body.style.paddingRight &&
          document.body.style.paddingRight !== "0px"
        ) {
          document.body.style.paddingRight = "";
        }
      });
      observer.observe(document.body, {
        attributes: true,
        attributeFilter: ["class", "style"],
      });
    });

    // Show modal with service info when row is clicked (not on action icons)
    document.addEventListener("DOMContentLoaded", function () {
      document.querySelectorAll(".service-row").forEach(function(row) {
        row.addEventListener("click", function(e) {
          // Prevent modal if clicking edit/delete icons
          if (e.target.closest('.service-action-icon')) return;
          document.getElementById("modalServiceTitle").textContent = row.getAttribute("data-title");
          document.getElementById("modalServiceDesc").textContent = row.getAttribute("data-desc");
          var modal = new bootstrap.Modal(document.getElementById("serviceModal"));
          modal.show();
        });
      });
    });

    // Add Service Modal logic
    document.addEventListener("DOMContentLoaded", function () {
      var addServiceModal = new bootstrap.Modal(document.getElementById("addServiceModal"));
      var addServiceTypeLabel = document.getElementById("addServiceTypeLabel");

      document.getElementById("addPremiumBtn").addEventListener("click", function (e) {
        e.preventDefault();
        addServiceTypeLabel.textContent = "Add Premium Service";
        addServiceModal.show();
      });
      document.getElementById("addRegularBtn").addEventListener("click", function (e) {
        e.preventDefault();
        addServiceTypeLabel.textContent = "Add Regular Service";
        addServiceModal.show();
      });

      // Optionally, clear form on open
      document.getElementById("addServiceModal").addEventListener("show.bs.modal", function () {
        document.getElementById("addServiceForm").reset();
      });

      // Prevent actual submit (demo only)
      document.getElementById("addServiceForm").addEventListener("submit", function (e) {
        e.preventDefault();
        addServiceModal.hide();
        // You can add your AJAX or PHP logic here
      });
    });
  </script>
</body>
</html>
