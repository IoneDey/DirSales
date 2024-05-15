/*!
 * Start Bootstrap - SB Admin v7.0.7 (https://startbootstrap.com/template/sb-admin)
 * Copyright 2013-2023 Start Bootstrap
 * Licensed under MIT (https://github.com/StartBootstrap/startbootstrap-sb-admin/blob/master/LICENSE)
 */
//
// Scripts
//

// window.addEventListener("DOMContentLoaded", (event) => {
//     // Toggle the side navigation
//     const sidebarToggle = document.body.querySelector("#sidebarToggle");
//     if (sidebarToggle) {
//         sidebarToggle.addEventListener("click", (event) => {
//             event.preventDefault();
//             document.body.classList.toggle("sb-sidenav-toggled");
//             localStorage.setItem(
//                 "sb|sidebar-toggle",
//                 document.body.classList.contains("sb-sidenav-toggled")
//             );
//         });
//     }
// });

window.addEventListener("DOMContentLoaded", (event) => {
    // Toggle the side navigation
    const sidebarToggle = document.body.querySelector("#sidebarToggle");
    if (sidebarToggle) {
        // Check if sidebarToggle is closed in local storage
        const isSidebarToggled = localStorage.getItem("sb|sidebar-toggle") === "true";

        // Tambahkan kondisi untuk menetapkan kelas CSS jika isSidebarToggled adalah true
        if (isSidebarToggled) {
            document.body.classList.add("sb-sidenav-toggled");
        }

        sidebarToggle.addEventListener("click", (event) => {
            event.preventDefault();
            document.body.classList.toggle("sb-sidenav-toggled");
            localStorage.setItem(
                "sb|sidebar-toggle",
                document.body.classList.contains("sb-sidenav-toggled")
            );
        });
    }
});
