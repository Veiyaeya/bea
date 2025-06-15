document.addEventListener("DOMContentLoaded", function () {
  // Modal toggle function
  const openModal = (modalId) => {
    const modal = bootstrap.Modal.getOrCreateInstance(document.getElementById(modalId));
    modal.show();
    return modal;
  };

  // Filter button animation
  const filterBtn = document.querySelector(".filter-btn");
  if (filterBtn) {
    ["mouseenter", "click"].forEach((event) =>
      filterBtn.addEventListener(event, () => {
        filterBtn.classList.remove("bounce-animate");
        void filterBtn.offsetWidth;
        filterBtn.classList.add("bounce-animate");
      })
    );
  }

  // Filter orders logic
  const filterOrders = (tableId, modalId) => {
    const checkedStatuses = Array.from(document.querySelectorAll(`#${modalId} .status-checkbox:checked`)).map((cb) => cb.value);
    const table = document.getElementById(tableId);
    if (!table) return;
    table.querySelectorAll("tbody tr").forEach((row) => {
      const badge = row.querySelector(".glass-badge") || row.querySelector(".badge");
      const status = badge ? badge.textContent.trim() : "";
      row.style.display = checkedStatuses.includes(status) ? "" : "none";
    });
  };

  const applyBtn = document.getElementById("applyFilterBtn");
  if (applyBtn) {
    applyBtn.addEventListener("click", () => {
      filterOrders("ordersTable", "filterModal");
      openModal("filterModal").hide();
    });
  }

  const orderListApplyBtn = document.getElementById("orderListApplyFilterBtn");
  if (orderListApplyBtn) {
    orderListApplyBtn.addEventListener("click", () => {
      filterOrders("orderListTable", "orderListFilterModal");
      openModal("orderListFilterModal").hide();
    });
  }

  // Search functionality
  const searchInput = document.getElementById("searchInput");
  if (searchInput) {
    searchInput.addEventListener("input", () => {
      const searchTerm = searchInput.value.toLowerCase();
      const table = document.getElementById("ordersTable");
      if (!table) return;
      table.querySelectorAll("tbody tr").forEach((row) => {
        const cells = row.querySelectorAll("td");
        const rowText = Array.from(cells)
          .map((cell) => cell.textContent.toLowerCase())
          .join(" ");
        row.style.display = rowText.includes(searchTerm) ? "" : "none";
      });
    });
  }

  const orderListSearchInput = document.getElementById("orderListSearchInput");
  if (orderListSearchInput) {
    orderListSearchInput.addEventListener("input", () => {
      const searchTerm = orderListSearchInput.value.toLowerCase();
      const table = document.getElementById("orderListTable");
      if (!table) return;
      table.querySelectorAll("tbody tr").forEach((row) => {
        const cells = row.querySelectorAll("td");
        const rowText = Array.from(cells)
          .map((cell) => cell.textContent.toLowerCase())
          .join(" ");
        row.style.display = rowText.includes(searchTerm) ? "" : "none";
      });
    });
  }

  // Modal blur effect
  const mainContent = document.getElementById("mainContent");
  mainContent.classList.remove("modal-blur-fadeout");
  document.querySelectorAll(".modal").forEach((modal) => {
    modal.addEventListener("show.bs.modal", () => {
      mainContent.classList.remove("modal-blur-fadeout");
      mainContent.classList.add("modal-blur");
    });
    modal.addEventListener("hide.bs.modal", () => {
      setTimeout(() => {
        if (!document.querySelectorAll(".modal.show").length) {
          mainContent.classList.remove("modal-blur");
          mainContent.classList.add("modal-blur-fadeout");
          setTimeout(() => mainContent.classList.remove("modal-blur-fadeout"), 50);
        }
      }, 10);
    });
    modal.addEventListener("hidden.bs.modal", () => {
      if (!document.querySelector(".modal.show")) {
        mainContent.classList.remove("modal-blur");
      }
    });
  });

  // Mutation observer for modal cleanup
  new MutationObserver(() => {
    if (document.body.classList.contains("modal-open")) {
      document.body.classList.remove("modal-open");
    }
    if (document.body.style.overflow === "hidden") {
      document.body.style.overflow = "";
    }
    if (document.body.style.paddingRight && document.body.style.paddingRight !== "0px") {
      document.body.style.paddingRight = "";
    }
  }).observe(document.body, { attributes: true, attributeFilter: ["class", "style"] });
});