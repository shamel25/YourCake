const body = document.querySelector("body"),
      sidebar = body.querySelector(".sidebar"),
      toggle = body.querySelector(".toggle"),
      searchBtn = body.querySelector(".search-box"),
      modeSwtich = body.querySelector(".toggle-switch"),
      modeText = body.querySelector(".mode-text");

      // Load sidebar state on page load
document.addEventListener("DOMContentLoaded", () => {
  const isClosed = localStorage.getItem("sidebar-closed");
  if (isClosed === "true") {
    sidebar.classList.add("close");
  } else {
    sidebar.classList.remove("close");
  }
});

// Toggle sidebar and save state
toggle.addEventListener("click", () => {
  sidebar.classList.toggle("close");
  localStorage.setItem("sidebar-closed", sidebar.classList.contains("close"));
});
      