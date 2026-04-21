

const toast = document.getElementById("toast");
const menuToggle = document.getElementById("menuToggle");
const mainNav = document.getElementById("mainNav");
const currentYear = document.getElementById("currentYear");
const bookingServiceSelect = document.getElementById("bookingServiceSelect");
const serviceGrid = document.getElementById("serviceGrid");


if (currentYear) {
    currentYear.textContent = new Date().getFullYear();
}


function showToast(message, type) {
    if (!toast || !message) {
        return;
    }

    toast.textContent = message;
    toast.className = "toast " + (type || "success") + " show";

    if (showToast.timeoutId) {
        clearTimeout(showToast.timeoutId);
    }

    showToast.timeoutId = setTimeout(function () {
        toast.className = "toast";
    }, 3200);
}

function activateTab(tabName) {
    const tabButtons = document.querySelectorAll(".tab-btn");
    const forms = document.querySelectorAll(".auth-form");

    tabButtons.forEach(function (button) {
        button.classList.toggle("active", button.dataset.tab === tabName);
    });

    forms.forEach(function (form) {
        form.classList.toggle("active", form.id === tabName + "Form");
    });
}


function showMessageFromUrl() {
    const urlParams = new URLSearchParams(window.location.search);
    const status = urlParams.get("status");
    const message = urlParams.get("message");
    const tab = urlParams.get("tab");

    // =====  حفظ حالة تسجيل الدخول أو الخروج =====
    if (status === "success" && message === "Login successful.") {
        localStorage.setItem("userToken", "true");
    } else if (status === "success" && message === "Logged out successfully.") {
        localStorage.removeItem("userToken"); 
    }
    // =========================================================

    if (document.getElementById("loginForm")) {
        if (tab === "register") {
            activateTab("register");
        } else {
            activateTab("login");
        }
    }

    if (status && message) {
        showToast(message, status);
        history.replaceState(null, document.title, window.location.pathname + window.location.hash);
    }
}


function getBookingBackendPath() {
    return "backend/process_booking.php";
}


function loadServices() {
    if (!serviceGrid && !bookingServiceSelect) {
        return;
    }

    fetch(getBookingBackendPath() + "?action=get_services")
        .then(function (response) {
            return response.json();
        })
        .then(function (result) {
            if (!result || result.status !== "success" || !Array.isArray(result.data)) {
                showToast("Could not load services from database.", "error");
                return;
            }

            if (serviceGrid) {
                serviceGrid.innerHTML = "";

                result.data.forEach(function (service) {
                    const article = document.createElement("article");
                    article.className = "service-card";
                    article.innerHTML =
                        '<div class="service-card-top">' +
                        '<span class="service-tag">Service</span>' +
                        '<h3>' + service.service_name_en + '</h3>' +
                        '</div>' +
                        '<p>' + service.description_en + '</p>' +
                        '<strong class="price">' + Number(service.price).toFixed(0) + ' EGP</strong>' +
                        '<button class="btn btn-secondary service-book-btn" data-service-id="' + service.service_id + '" type="button">Book This Service</button>';
                    serviceGrid.appendChild(article);
                });
            }

            if (bookingServiceSelect) {
                bookingServiceSelect.innerHTML = '<option value="">Choose a service</option>';

                result.data.forEach(function (service) {
                    const option = document.createElement("option");
                    option.value = service.service_id;
                    option.textContent = service.service_name_en + ' - ' + Number(service.price).toFixed(0) + ' EGP';
                    bookingServiceSelect.appendChild(option);
                });

                const urlParams = new URLSearchParams(window.location.search);
                const selectedServiceId = urlParams.get("service_id");
                if (selectedServiceId) {
                    bookingServiceSelect.value = selectedServiceId;
                }
            }
        })
        .catch(function () {
            showToast("Server connection failed while loading services.", "error");
        });
}


if (menuToggle) {
    menuToggle.addEventListener("click", function () {
        if (mainNav) {
            mainNav.classList.toggle("open");
        }
    });
}


document.querySelectorAll(".tab-btn").forEach(function (button) {
    button.addEventListener("click", function () {
        activateTab(button.dataset.tab);
    });
});


document.addEventListener("click", function (event) {
    if (event.target.classList.contains("service-book-btn")) {
        // فحص تسجيل الدخول
        const isLoggedIn = localStorage.getItem("userToken");

        if (isLoggedIn) {
            const serviceId = event.target.dataset.serviceId;
            window.location.href = "booking.html?service_id=" + serviceId;
        } else {

            showToast("You must log in first to make a reservation.", "error");
            setTimeout(function() {
                window.location.href = "auth_login.html?tab=login";
            }, 2000);
        }
        return;
    }

    if (event.target.closest(".nav a") && mainNav) {
        mainNav.classList.remove("open");
    }
});


document.addEventListener("DOMContentLoaded", function () {
    showMessageFromUrl();
    loadServices();


    const isLoggedIn = localStorage.getItem("userToken");
    const navLogin = document.getElementById("navLogin");
    const navLogout = document.getElementById("navLogout");

    if (isLoggedIn) {
        if (navLogin) navLogin.style.display = "none";
        if (navLogout) navLogout.style.display = "inline-block";
    } else {
        if (navLogin) navLogin.style.display = "inline-block";
        if (navLogout) navLogout.style.display = "none";
    }
});
