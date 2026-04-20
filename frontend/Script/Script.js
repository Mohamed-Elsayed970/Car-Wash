/* --- Configuration & Data --- */

/**
 * Translation Dictionary: Contains all text strings for English and Arabic.
 * Used for multi-language support across the UI.
 */
/*
const translations = {
    en: {
        brandTag: "Car Wash & Detailing",
        navHome: "Home",
        navAbout: "About",
        navServices: "Services",
        navBooking: "Booking",
        navLogin: "Login",
        navContact: "Contact",
        bookNow: "Book Now",
        heroEyebrow: "Fast, Clean, Professional",
        heroTitle: "Give your car the shine it deserves",
        heroText: "ShineHub helps customers explore services, create accounts, and book car wash appointments in minutes.",
        exploreServices: "Explore Services",
        createAccount: "Create Account",
        statYears: "Years Experience",
        statCustomers: "Happy Customers",
        statServices: "Service Options",
        heroCardBadge: "Open today from 8:00 AM to 11:00 PM",
        heroCardTitle: "Easy online booking",
        heroFeature1: "Choose a service package",
        heroFeature2: "Pick your preferred date and time",
        heroFeature3: "Track confirmations instantly",
        miniCard1Label: "Most booked",
        miniCard1Value: "Premium Wash",
        miniCard2Label: "Starting from",
        aboutEyebrow: "About Us",
        aboutTitle: "A trusted car wash built on quality and care",
        aboutText: "ShineHub is a customer-focused car wash dedicated to keeping vehicles clean, protected, and road-ready with reliable service and modern equipment.",
        visionTitle: "Our Vision",
        visionText: "To become the most trusted local destination for smart, eco-aware, and detail-driven car care.",
        missionTitle: "Our Mission",
        missionText: "To deliver fast service, consistent results, and a smooth digital booking experience for every customer.",
        goalsTitle: "Future Goals",
        goalsText: "To expand mobile detailing, loyalty rewards, and advanced paint protection services across more branches.",
        aboutText: "ShineHub is a customer-focused car wash dedicated to keeping vehicles clean, protected, and road-ready with reliable service and modern equipment.",
        visionTitle: "Our Vision",
        visionText: "To become the most trusted local destination for smart, eco-aware, and detail-driven car care.",
        missionTitle: "Our Mission",
        missionText: "To deliver fast service, consistent results, and a smooth digital booking experience for every customer.",
        goalsTitle: "Future Goals",
        goalsText: "To expand mobile detailing, loyalty rewards, and advanced paint protection services across more branches.",
        teamTitle: "Our Team",
        teamText: "Experienced washers, detailers, and support staff work together to give every vehicle premium attention.",
        teamMember1: "Detailing Specialists",
        teamMember2: "Interior Cleaning Crew",
        teamMember3: "Customer Support",
        testimonialTitle: "Customer Reviews",
        review1: "“Very clean finish, friendly staff, and the booking process was super easy.”",
        review2: "“I booked online in less than two minutes and the polishing result was excellent.”",
        contactTitle: "Contact Information",
        contactAddressLabel: "Address:",
        contactAddressValue: "12 Nile Street, Cairo, Egypt",
        contactPhoneLabel: "Phone:",
        contactEmailLabel: "Email:",
        contactHoursLabel: "Hours:",
        contactHoursValue: "Daily, 8:00 AM to 11:00 PM",
        servicesEyebrow: "Services",
        servicesTitle: "Clear packages, fair prices, professional results",
        servicesText: "Choose from our most requested services. Each option is shown with pricing and a short description.",
        serviceTagPopular: "Popular",
        serviceTagBest: "Best Value",
        serviceTagShine: "Premium",
        serviceTagProtect: "Protection",
        serviceTagDeep: "Deep Care",
        bookThisService: "Book This Service",
        bookingEyebrow: "Booking",
        bookingTitle: "Schedule your next wash in a few steps",
        bookingText: "Fill in the form below to request an appointment. Logged-in users can enjoy faster booking and booking history in future updates.",
        bookingPoint1Title: "Simple",
        bookingPoint1Text: "Pick a service, date, and time in one place.",
        bookingPoint2Title: "Flexible",
        bookingPoint2Text: "Suitable for standard washes, detailing, and premium packages.",
        bookingPoint3Title: "Reliable",
        bookingPoint3Text: "Receive clear success and error feedback after every action.",
        bookingFormTitle: "Book an Appointment",
        fullNameLabel: "Full Name",
        emailLabel: "Email Address",
        phoneLabel: "Phone Number",
        carModelLabel: "Car Model",
        serviceLabel: "Service",
        chooseService: "Choose a service",
        dateLabel: "Preferred Date",
        timeLabel: "Preferred Time",
        notesLabel: "Notes",
        submitBooking: "Submit Booking",
        authEyebrow: "Account Access",
        authTitle: "Login or create your account",
        authText: "Use your account to manage future bookings and enjoy a faster checkout experience.",
        socialLabel: "Optional social sign-in",
        loginTab: "Login",
        registerTab: "Register",
        loginIdentityLabel: "Username or Email",
        passwordLabel: "Password",
        forgotPassword: "Forgot your password?",
        loginBtn: "Login",
        confirmPasswordLabel: "Confirm Password",
        registerBtn: "Create Account",
        footerText: "All rights reserved. Clean cars, happy customers.",
        fullNamePlaceholder: "Enter your full name",
        emailPlaceholder: "Enter your email",
        phonePlaceholder: "Enter your phone number",
        carModelPlaceholder: "Example: Toyota Corolla",
        notesPlaceholder: "Any extra requests or vehicle notes",
        loginIdentityPlaceholder: "Enter username or email",
        passwordPlaceholder: "Enter your password",
        confirmPasswordPlaceholder: "Re-enter your password"
    },
    ar: {
        brandTag: "غسيل وتلميع السيارات",
        navHome: "الرئيسية",
        navAbout: "من نحن",
        navServices: "الخدمات",
        navBooking: "الحجز",
        navLogin: "الدخول",
        navContact: "التواصل",
        bookNow: "احجز الآن",
        heroEyebrow: "سريع ونظيف واحترافي",
        heroTitle: "امنح سيارتك اللمعان الذي تستحقه",
        heroText: "يساعد ShineHub العملاء على استعراض الخدمات وإنشاء الحسابات وحجز مواعيد غسيل السيارات خلال دقائق.",
        exploreServices: "استكشف الخدمات",
        createAccount: "إنشاء حساب",
        statYears: "سنوات خبرة",
        statCustomers: "عميل سعيد",
        statServices: "خدمة متاحة",
        heroCardBadge: "نفتح اليوم من 8:00 صباحًا إلى 11:00 مساءً",
        heroCardTitle: "حجز إلكتروني سهل",
        heroFeature1: "اختر باقة الخدمة المناسبة",
        heroFeature2: "حدد التاريخ والوقت المناسبين",
        heroFeature3: "احصل على تأكيدات فورية",
        miniCard1Label: "الأكثر طلبًا",
        miniCard1Value: "الغسيل المميز",
        miniCard2Label: "تبدأ من",
        aboutEyebrow: "من نحن",
        aboutTitle: "مغسلة سيارات موثوقة مبنية على الجودة والاهتمام",
        aboutText: "ShineHub مغسلة سيارات تركز على العميل وتهدف إلى إبقاء السيارات نظيفة ومحمية وجاهزة للطريق من خلال خدمة موثوقة وتجهيزات حديثة.",
        visionTitle: "رؤيتنا",
        visionText: "أن نصبح الوجهة المحلية الأكثر ثقة للعناية الذكية والمستدامة والمفصلة بالسيارات.",
        missionTitle: "مهمتنا",
        missionText: "تقديم خدمة سريعة ونتائج ثابتة وتجربة حجز رقمية سلسة لكل عميل.",
        goalsTitle: "أهدافنا المستقبلية",
        goalsText: "التوسع في خدمات التلميع المتنقل وبرامج الولاء وطبقات الحماية المتقدمة عبر فروع أكثر.",
        teamTitle: "فريقنا",
        teamText: "يعمل خبراء الغسيل والتفصيل والدعم معًا لمنح كل سيارة عناية ممتازة.",
        teamMember1: "متخصصو التلميع",
        teamMember2: "فريق التنظيف الداخلي",
        teamMember3: "خدمة العملاء",
        testimonialTitle: "آراء العملاء",
        review1: "“نتيجة نظافة ممتازة وطاقم ودود وعملية الحجز كانت سهلة جدًا.”",
        review2: "“حجزت أونلاين في أقل من دقيقتين وكانت نتيجة التلميع رائعة.”",
        contactTitle: "معلومات التواصل",
        contactAddressLabel: "العنوان:",
        contactAddressValue: "12 شارع النيل، القاهرة، مصر",
        contactPhoneLabel: "الهاتف:",
        contactEmailLabel: "البريد الإلكتروني:",
        contactHoursLabel: "ساعات العمل:",
        contactHoursValue: "يوميًا من 8:00 صباحًا إلى 11:00 مساءً",
        servicesEyebrow: "الخدمات",
        servicesTitle: "باقات واضحة وأسعار عادلة ونتائج احترافية",
        servicesText: "اختر من بين أكثر خدماتنا طلبًا، مع عرض السعر ووصف مختصر لكل خدمة.",
        serviceTagPopular: "الأكثر طلبًا",
        serviceTagBest: "أفضل قيمة",
        serviceTagShine: "مميز",
        serviceTagProtect: "حماية",
        serviceTagDeep: "عناية عميقة",
        bookThisService: "احجز هذه الخدمة",
        bookingEyebrow: "الحجز",
        bookingTitle: "احجز موعد غسيلك القادم في خطوات بسيطة",
        bookingText: "املأ النموذج التالي لطلب موعد. سيتمكن المستخدمون المسجلون من الاستفادة من حجز أسرع وسجل حجوزات في التحديثات القادمة.",
        bookingPoint1Title: "بسيط",
        bookingPoint1Text: "اختر الخدمة والتاريخ والوقت من مكان واحد.",
        bookingPoint2Title: "مرن",
        bookingPoint2Text: "مناسب للغسيل العادي والتفصيل والباقات المميزة.",
        bookingPoint3Title: "موثوق",
        bookingPoint3Text: "احصل على رسائل نجاح أو أخطاء واضحة بعد كل عملية.",
        bookingFormTitle: "احجز موعدًا",
        fullNameLabel: "الاسم الكامل",
        emailLabel: "البريد الإلكتروني",
        phoneLabel: "رقم الهاتف",
        carModelLabel: "موديل السيارة",
        serviceLabel: "الخدمة",
        chooseService: "اختر الخدمة",
        dateLabel: "التاريخ المفضل",
        timeLabel: "الوقت المفضل",
        notesLabel: "ملاحظات",
        submitBooking: "إرسال الحجز",
        authEyebrow: "الدخول للحساب",
        authTitle: "سجّل الدخول أو أنشئ حسابك",
        authText: "استخدم حسابك لإدارة الحجوزات القادمة والاستفادة من تجربة أسرع.",
        socialLabel: "تسجيل اختياري عبر التواصل الاجتماعي",
        loginTab: "تسجيل الدخول",
        registerTab: "إنشاء حساب",
        loginIdentityLabel: "اسم المستخدم أو البريد الإلكتروني",
        passwordLabel: "كلمة المرور",
        forgotPassword: "هل نسيت كلمة المرور؟",
        loginBtn: "دخول",
        confirmPasswordLabel: "تأكيد كلمة المرور",
        registerBtn: "إنشاء الحساب",
        footerText: "جميع الحقوق محفوظة. سيارات نظيفة وعملاء سعداء.",
        fullNamePlaceholder: "أدخل اسمك الكامل",
        emailPlaceholder: "أدخل بريدك الإلكتروني",
        phonePlaceholder: "أدخل رقم هاتفك",
        carModelPlaceholder: "مثال: تويوتا كورولا",
        notesPlaceholder: "أي طلبات إضافية أو ملاحظات عن السيارة",
        loginIdentityPlaceholder: "أدخل اسم المستخدم أو البريد الإلكتروني",
        passwordPlaceholder: "أدخل كلمة المرور",
        confirmPasswordPlaceholder: "أعد إدخال كلمة المرور"
    }
};
*/

/**
 * Service Labels: Mapping service IDs to bilingual names.
 * Used for populating dropdowns and service cards.
 */
/*
const serviceLabels = {
    1: { en: "Exterior Wash", ar: "غسيل خارجي" },
    2: { en: "Interior Cleaning", ar: "تنظيف داخلي" },
    3: { en: "Polishing", ar: "تلميع" }
};
*/

/* --- DOM Element Selection --- */

// const languageToggle = document.getElementById("languageToggle");
const toast = document.getElementById("toast");
const menuToggle = document.getElementById("menuToggle");
const mainNav = document.getElementById("mainNav");
const currentYear = document.getElementById("currentYear");
const bookingServiceSelect = document.getElementById("bookingServiceSelect");

/* --- Initialization --- */

if (currentYear) {
    currentYear.textContent = new Date().getFullYear();
}

/* --- UI Helpers --- */

function showToast(message, type = "success") {
    if (!toast) return;
    toast.textContent = message;
    toast.className = `toast ${type} show`;
    
    if (showToast.timeout) {
        clearTimeout(showToast.timeout);
    }
    
    showToast.timeout = setTimeout(() => {
        toast.className = "toast";
    }, 3200);
}

function activateTab(tabName) {
    document.querySelectorAll(".tab-btn").forEach((button) => {
        button.classList.toggle("active", button.dataset.tab === tabName);
    });

    document.querySelectorAll(".auth-form").forEach((form) => {
        form.classList.toggle("active", form.id === `${tabName}Form`);
    });
}

/* --- Language & Localization --- */

/*
function setLanguage(lang) {
    const dictionary = translations[lang];
    if (!dictionary) return;
    
    document.documentElement.lang = lang;
    document.documentElement.dir = lang === "ar" ? "rtl" : "ltr";
    document.body.classList.toggle("rtl", lang === "ar");
    if (languageToggle) languageToggle.textContent = lang === "ar" ? "EN" : "AR";

    document.querySelectorAll("[data-i18n]").forEach((element) => {
        const key = element.dataset.i18n;
        if (dictionary[key]) {
            element.textContent = dictionary[key];
        }
    });

    document.querySelectorAll("[data-i18n-placeholder]").forEach((element) => {
        const key = element.dataset.i18nPlaceholder;
        if (dictionary[key]) {
            element.placeholder = dictionary[key];
        }
    });

    if (bookingServiceSelect) {
        Array.from(bookingServiceSelect.options).forEach((option) => {
            const label = serviceLabels[option.value];
            if (label) {
                option.textContent = label[lang];
            }
        });
    }

    localStorage.setItem("shinehub-language", lang);
}
*/

/* --- Data Fetching & Form Submission --- */

/**
 * Submits form data to the backend via fetch API.
 * Handles response and provides user feedback via toast.
 * Corrected Paths and Redirection logic.
 */
async function submitForm(form) {
    const formData = new FormData(form);

    try {
        let targetUrl = "";
        // Paths are relative to the HTML files inside the /pages/ folder
        if (form.id === "loginForm") {
            targetUrl = "../backend/process_login.php";
        } else if (form.id === "registerForm") {
            targetUrl = "../backend/process_register.php";
        } else if (form.id === "bookingForm") {
            targetUrl = "../backend/process_booking.php";
        } else {
            showToast("Unknown form submission target.", "error");
            return;
        }

        const response = await fetch(targetUrl, {
            method: "POST",
            body: formData
        });

        // Ensure the response is valid JSON
        const result = await response.json();
        
        showToast(result.message, result.status === "success" ? "success" : "error");

        if (result.status === "success") {
            form.reset();
            
            // Check for redirect from backend (e.g., home.html)
            if (result.redirect) {
                setTimeout(() => {
                    window.location.href = result.redirect;
                }, 1000);
            } else if (form.id === "registerForm") {
                // If registration success, switch to login tab
                activateTab("login");
            }
        }
    } catch (error) {
        console.error("Submission Error:", error);
        showToast("Unable to connect to the server. Please check your PHP setup.", "error");
    }
}

async function loadServices() {
    try {
        // Fetch services relative path
        const response = await fetch("../backend/process_booking.php?action=get_services");
        const result = await response.json();

        if (result.status !== "success" || !Array.isArray(result.data)) {
            return;
        }

        // const currentLanguage = localStorage.getItem("shinehub-language") || "en";
        const grid = document.getElementById("serviceGrid");
        
        if (grid) {
            grid.innerHTML = "";
            // const defaultTags = ["serviceTagPopular", "serviceTagBest", "serviceTagShine", "serviceTagProtect", "serviceTagDeep"];

            result.data.forEach((service, index) => {
                const article = document.createElement("article");
                article.className = "service-card";
                // const serviceName = currentLanguage === "ar" ? service.service_name_ar : service.service_name_en;
                // const serviceDescription = currentLanguage === "ar" ? service.description_ar : service.description_en;
                // const tagKey = defaultTags[index] || "serviceTagPopular";
                // const tagText = translations[currentLanguage][tagKey] || translations.en[tagKey];

                // Modified for disabled translation: using English as default
                const serviceName = service.service_name_en;
                const serviceDescription = service.description_en;

                article.innerHTML = `
                    <div class="service-card-top">
                        <span class="service-tag">Service</span>
                        <h3>${serviceName}</h3>
                    </div>
                    <p>${serviceDescription || ''}</p>
                    <strong class="price">$${Number(service.price).toFixed(0)}</strong>
                    <button class="btn btn-secondary service-book-btn" data-service-id="${service.service_id}" type="button">Book Now</button>
                `;
                grid.appendChild(article);
            });
        }

        if (bookingServiceSelect) {
            // bookingServiceSelect.innerHTML = `<option value="">${translations[currentLanguage].chooseService}</option>`;
            bookingServiceSelect.innerHTML = `<option value="">Choose a service</option>`;
            result.data.forEach((service) => {
                const option = document.createElement("option");
                option.value = service.service_id;
                // option.textContent = currentLanguage === "ar" ? service.service_name_ar : service.service_name_en;
                option.textContent = service.service_name_en;
                bookingServiceSelect.appendChild(option);
            });
        }
    } catch (error) {
        console.warn("Services could not be loaded from PHP. Using static fallback.");
    }
}

/* --- Event Listeners --- */

/*
languageToggle?.addEventListener("click", () => {
    const current = localStorage.getItem("shinehub-language") || "en";
    const next = current === "en" ? "ar" : "en";
    setLanguage(next);
    loadServices();
});
*/

menuToggle?.addEventListener("click", () => {
    if (mainNav) mainNav.classList.toggle("open");
});

document.querySelectorAll(".tab-btn").forEach((button) => {
    button.addEventListener("click", () => activateTab(button.dataset.tab));
});

document.querySelectorAll("#bookingForm, #loginForm, #registerForm").forEach((form) => {
    form.addEventListener("submit", (event) => {
        event.preventDefault();
        submitForm(form);
    });
});

document.addEventListener("click", (event) => {
    if (event.target.classList.contains("service-book-btn")) {
        const serviceId = event.target.dataset.serviceId;
        if (serviceId && bookingServiceSelect) {
            bookingServiceSelect.value = serviceId;
        }

        const bookingSection = document.getElementById("booking");
        if (bookingSection) {
            bookingSection.scrollIntoView({ behavior: "smooth" });
        } else {
            window.location.href = `auth.html?id=${serviceId}`;
        }
    }

    if (event.target.closest(".nav a")) {
        if (mainNav) mainNav.classList.remove("open");
    }
});

/* --- App Startup --- */

document.addEventListener("DOMContentLoaded", () => {
    // const preferredLanguage = localStorage.getItem("shinehub-language") || "en";
    // setLanguage(preferredLanguage);
    loadServices();
    
    // Default tab for auth page
    const loginForm = document.getElementById("loginForm");
    if (loginForm) {
        activateTab("login");
    }

    // Toast handling from URL params
    const urlParams = new URLSearchParams(window.location.search);
    const statusParam = urlParams.get("status");
    const messageParam = urlParams.get("message");

    if (statusParam && messageParam) {
        showToast(decodeURIComponent(messageParam), statusParam);
        history.replaceState(null, document.title, window.location.pathname);
    }
});
