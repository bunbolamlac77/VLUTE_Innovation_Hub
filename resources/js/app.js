import "./bootstrap";

import Alpine from "alpinejs";

window.Alpine = Alpine;

Alpine.start();

document.addEventListener("DOMContentLoaded", () => {
    setupGlobalClickHandlers();
    initVerifyEmailModal();
    initAuthTabs();
    initEnterpriseFieldsToggle();
    initPasswordStrengthMeter();
    initPasswordMatchCheck();
    initUnapprovedModal();
});

// -----------------------------------------------------------------------------
// Global click handlers for modal close & password toggles
// -----------------------------------------------------------------------------

function setupGlobalClickHandlers() {
    document.addEventListener("click", (event) => {
        const closeTrigger = event.target.closest("[data-close]");
        if (closeTrigger) {
            const id = closeTrigger.getAttribute("data-close");
            const target = id ? document.getElementById(id) : null;
            if (target) {
                target.classList.add("hidden");
                target.classList.remove("show");
            }
        }

        const eyeToggle = event.target.closest(".eye-toggle");
        if (eyeToggle) {
            const selector = eyeToggle.getAttribute("data-toggle");
            const input = selector ? document.querySelector(selector) : null;
            if (input) {
                input.type = input.type === "password" ? "text" : "password";
            }
        }

        const googleButton = event.target.closest("[data-google-login]");
        if (googleButton) {
            alert("Đăng nhập Google đang được phát triển");
            return;
        }

        // close when clicking on modal backdrop
        const backdrop = event.target.closest("#modal-login-block");
        if (backdrop && event.target.id === "modal-login-block") {
            backdrop.classList.add("hidden");
        }
    });
}

// -----------------------------------------------------------------------------
// Verify email page modal
// -----------------------------------------------------------------------------

function initVerifyEmailModal() {
    const modal = document.getElementById("modal-login-block");
    if (!modal) return;
    modal.classList.remove("hidden");
    modal.classList.add("show");
}

// -----------------------------------------------------------------------------
// Login/Register tabs & helpers
// -----------------------------------------------------------------------------

function initAuthTabs() {
    if (!document.body.classList.contains("auth-page")) {
        return;
    }

    const tabLogin = document.getElementById("tab-login");
    const tabRegister = document.getElementById("tab-register");
    const panelLogin = document.getElementById("panel-login");
    const panelRegister = document.getElementById("panel-register");

    if (!tabLogin || !tabRegister || !panelLogin || !panelRegister) {
        return;
    }

    // Kiểm tra nếu có lỗi từ form đăng ký, tự động chuyển sang tab register
    const hasRegisterErrors = document.querySelector(
        "#panel-register .auth-alert--error"
    );
    const initial = hasRegisterErrors
        ? "register"
        : document.body.dataset.activeTab || "login";

    const show = (target) => {
        const isLogin = target === "login";
        tabLogin.classList.toggle("active", isLogin);
        tabRegister.classList.toggle("active", !isLogin);
        panelLogin.classList.toggle("hidden", !isLogin);
        panelRegister.classList.toggle("hidden", isLogin);

        // Tailwind styling for active tab (no custom CSS)
        const on = ["bg-white", "shadow", "text-blue-600"]; // active styles
        const off = ["bg-transparent", "text-slate-500"]; // inactive styles
        tabLogin.classList.remove(...on, ...off);
        tabRegister.classList.remove(...on, ...off);
        if (isLogin) {
            tabLogin.classList.add(...on);
            tabRegister.classList.add(...off);
        } else {
            tabRegister.classList.add(...on);
            tabLogin.classList.add(...off);
        }
    };

    show(initial);

    tabLogin.addEventListener("click", () => show("login"));
    tabRegister.addEventListener("click", () => show("register"));
}

function initEnterpriseFieldsToggle() {
    if (!document.body.classList.contains("auth-page")) {
        return;
    }

    const emailInput = document.getElementById("reg-email");
    const enterpriseBlock = document.getElementById("enterprise-fields");
    const regForm = document.getElementById("reg-form");
    const enterpriseFields =
        enterpriseBlock?.querySelectorAll(".enterprise-field");

    if (!emailInput || !enterpriseBlock || !regForm) {
        return;
    }

    const toggle = () => {
        const value = (emailInput.value || "").toLowerCase();
        const isVlute =
            value.endsWith("@st.vlute.edu.vn") ||
            value.endsWith("@vlute.edu.vn");
        const shouldShow = !isVlute && value.includes("@");

        enterpriseBlock.classList.toggle("hidden", !shouldShow);

        // Cập nhật required attribute
        if (enterpriseFields) {
            enterpriseFields.forEach((field) => {
                if (shouldShow) {
                    field.setAttribute("required", "required");
                } else {
                    field.removeAttribute("required");
                }
            });
        }
    };

    // Validate form submission
    regForm.addEventListener("submit", (e) => {
        const value = (emailInput.value || "").toLowerCase();
        const isVlute =
            value.endsWith("@st.vlute.edu.vn") ||
            value.endsWith("@vlute.edu.vn");
        const shouldRequire = !isVlute && value.includes("@");

        if (shouldRequire && enterpriseFields) {
            let isValid = true;
            let firstError = null;

            enterpriseFields.forEach((field) => {
                if (!field.value || field.value.trim() === "") {
                    isValid = false;
                    field.classList.add("error-field");
                    if (!firstError) {
                        firstError = field;
                    }
                } else {
                    field.classList.remove("error-field");
                }
            });

            if (!isValid) {
                e.preventDefault();
                if (firstError) {
                    firstError.scrollIntoView({
                        behavior: "smooth",
                        block: "center",
                    });
                    firstError.focus();
                }
                alert(
                    "Vui lòng điền đầy đủ thông tin doanh nghiệp để tiếp tục đăng ký."
                );
                return false;
            }
        }
    });

    // Remove error class on input
    if (enterpriseFields) {
        enterpriseFields.forEach((field) => {
            field.addEventListener("input", () => {
                field.classList.remove("error-field");
            });
        });
    }

    emailInput.addEventListener("input", toggle);
    toggle();
}

function initPasswordStrengthMeter() {
    if (!document.body.classList.contains("auth-page")) {
        return;
    }

    const passwordInput = document.getElementById("reg-password");
    const emailInput = document.getElementById("reg-email");
    const bar = document.getElementById("pw-bar-register");
    const requirements = {
        r1: document.getElementById("pw-r1"),
        r2: document.getElementById("pw-r2"),
        r3: document.getElementById("pw-r3"),
        r4: document.getElementById("pw-r4"),
        r5: document.getElementById("pw-r5"),
    };

    if (!passwordInput || !bar) {
        return;
    }

    const setRequirementState = (element, ok) => {
        if (!element) {
            return;
        }
        element.classList.toggle("pw-ok", ok);
        element.classList.toggle("pw-bad", !ok);
    };

    const evaluate = () => {
        const value = passwordInput.value || "";
        const email = emailInput ? emailInput.value : "";

        const checks = {
            r1: value.length >= 8,
            r2: /[A-Z]/.test(value) && /[a-z]/.test(value),
            r3: /\d/.test(value),
            r4: /[^\w\s]/.test(value),
            r5: true,
        };

        if (email) {
            const local = (email.split("@")[0] || "").toLowerCase();
            if (local.length >= 4) {
                checks.r5 = !value.toLowerCase().includes(local);
            }
        }

        const score = Object.values(checks).filter(Boolean).length;

        Object.entries(requirements).forEach(([key, element]) => {
            setRequirementState(element, checks[key]);
        });

        bar.style.width = `${(score / 5) * 100}%`;
    };

    passwordInput.addEventListener("input", evaluate);
    emailInput?.addEventListener("input", evaluate);
    evaluate();
}

function initPasswordMatchCheck() {
    if (!document.body.classList.contains("auth-page")) {
        return;
    }

    const password = document.getElementById("reg-password");
    const confirmation = document.getElementById("reg-password-confirm");
    const note = document.getElementById("match-note");
    const button = document.getElementById("btn-register");

    if (!password || !confirmation || !button) {
        return;
    }

    const sync = () => {
        const ok =
            password.value.length > 0 && password.value === confirmation.value;
        button.disabled = !ok;
        if (note) {
            note.textContent = ok
                ? "Mật khẩu khớp."
                : "Mật khẩu nhập lại chưa khớp.";
            note.style.color = ok ? "#059669" : "#b91c1c";
        }
    };

    password.addEventListener("input", sync);
    confirmation.addEventListener("input", sync);
    sync();
}

function initUnapprovedModal() {
    if (!document.body.classList.contains("auth-page")) {
        return;
    }

    const modal = document.getElementById("modal-login-block");
    const modalBody = document.getElementById("modal-login-block-body");

    if (!modal || !modalBody) {
        return;
    }

    // Kiểm tra unapproved
    const shouldShowUnapproved = document.body.dataset.unapproved === "1";
    if (shouldShowUnapproved) {
        const email = document.body.dataset.unapprovedEmail || "";
        modalBody.innerHTML = `
            <p>Tài khoản <strong>${email}</strong> đang chờ phê duyệt.</p>
            <p>Vui lòng đợi quản trị viên xác nhận. Bạn có thể quay lại sau.</p>
        `;
        modal.classList.add("show");
        return;
    }

    // Kiểm tra unverified
    const shouldShowUnverified = document.body.dataset.unverified === "1";
    if (shouldShowUnverified) {
        const email = document.body.dataset.unverifiedEmail || "";
        modalBody.innerHTML = `
            <p>Tài khoản <strong>${email}</strong> chưa xác thực email.</p>
            <p>Vui lòng kiểm tra hộp thư và nhấn vào liên kết xác thực để kích hoạt tài khoản.</p>
        `;
        modal.classList.add("show");
        return;
    }
}

/* =====================================================
   ADMIN UI SCRIPTS — gom ở đây để bảo trì
   ===================================================== */

// Xác nhận xoá / từ chối
window.confirmDelete = function (e, message = "Bạn chắc chắn?") {
    if (!confirm(message)) {
        e.preventDefault();
        return false;
    }
    return true;
};

// Đánh dấu tab active theo URL (phòng trường hợp render SSR khác)
document.addEventListener("DOMContentLoaded", () => {
    const url = new URL(window.location.href);
    const tab = url.searchParams.get("tab") || "approvals";
    document.querySelectorAll(".admin-tab").forEach((a) => {
        if (a.href.includes(`tab=${tab}`)) a.classList.add("is-active");
    });
});
