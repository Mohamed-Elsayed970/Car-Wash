// شرح تفصيلي: هذا الملف مسؤول عن أي سلوك بسيط في الواجهة مثل القائمة الصغيرة,
// ورسائل النجاح والخطأ, وتحميل الخدمات من قاعدة البيانات داخل الصفحة الرئيسية.

// شرح تفصيلي: هنا نأخذ العناصر المهمة من الصفحة حتى نتعامل معها لاحقاً لو كانت موجودة.
const toast = document.getElementById("toast");
const menuToggle = document.getElementById("menuToggle");
const mainNav = document.getElementById("mainNav");
const currentYear = document.getElementById("currentYear");
const bookingServiceSelect = document.getElementById("bookingServiceSelect");
const serviceGrid = document.getElementById("serviceGrid");

// شرح تفصيلي: نعرض السنة الحالية تلقائياً في الفوتر بدل ما نكتبها يدويًا كل سنة.
if (currentYear) {
    currentYear.textContent = new Date().getFullYear();
}

// شرح تفصيلي: هذه الدالة تعرض رسالة مؤقتة للمستخدم مثل نجاح الحجز أو وجود خطأ.
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

// شرح تفصيلي: هذه الدالة تغيّر بين فورم login وفورم register في صفحة auth_login.html.
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

// شرح تفصيلي: هذه الدالة تقرأ الرسائل القادمة من PHP عبر الرابط مثل:
// index.html?status=success&message=Booking+saved
function showMessageFromUrl() {
    const urlParams = new URLSearchParams(window.location.search);
    const status = urlParams.get("status");
    const message = urlParams.get("message");
    const tab = urlParams.get("tab");

    if (tab === "register") {
        activateTab("register");
    } else if (document.getElementById("loginForm")) {
        activateTab("login");
    }

    if (status && message) {
        showToast(message, status);
        history.replaceState(null, document.title, window.location.pathname + window.location.hash);
    }
}

// شرح تفصيلي: هذه الدالة تحمل الخدمات من ملف PHP ثم تضعها داخل كروت الخدمات
// وأيضاً داخل القائمة المنسدلة في نموذج الحجز.
function loadServices() {
    if (!serviceGrid && !bookingServiceSelect) {
        return;
    }

    fetch("backend/process_booking.php?action=get_services")
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
                    option.textContent = service.service_name_en + " - " + Number(service.price).toFixed(0) + " EGP";
                    bookingServiceSelect.appendChild(option);
                });

                // شرح تفصيلي: لو جاء المستخدم من زر خدمة معينة, نقرأ رقم الخدمة من الرابط ونحددها تلقائياً.
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

// شرح تفصيلي: هذا الجزء يفتح ويغلق القائمة في الموبايل.
if (menuToggle) {
    menuToggle.addEventListener("click", function () {
        if (mainNav) {
            mainNav.classList.toggle("open");
        }
    });
}

// شرح تفصيلي: عند الضغط على أزرار Login أو Register نظهر الفورم المناسب.
document.querySelectorAll(".tab-btn").forEach(function (button) {
    button.addEventListener("click", function () {
        activateTab(button.dataset.tab);
    });
});

// شرح تفصيلي: عند الضغط على زر Book This Service داخل كروت الخدمات,
// ننتقل لنفس الصفحة عند جزء الحجز ونختار الخدمة تلقائيًا.
document.addEventListener("click", function (event) {
    if (event.target.classList.contains("service-book-btn")) {
        const serviceId = event.target.dataset.serviceId;

        if (window.location.pathname.toLowerCase().includes("auth_login.html")) {
            window.location.href = "index.html?service_id=" + serviceId + "#booking";
            return;
        }

        if (bookingServiceSelect && serviceId) {
            bookingServiceSelect.value = serviceId;
        }

        const bookingSection = document.getElementById("booking");
        if (bookingSection) {
            bookingSection.scrollIntoView({ behavior: "smooth" });
        }
    }

    if (event.target.closest(".nav a") && mainNav) {
        mainNav.classList.remove("open");
    }
});

// شرح تفصيلي: عند تحميل الصفحة, ننفذ الأشياء الأساسية مرة واحدة فقط.
document.addEventListener("DOMContentLoaded", function () {
    showMessageFromUrl();
    loadServices();
});
