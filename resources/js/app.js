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

        const opened = document.querySelector(".modal.show");
        if (opened && event.target === opened) {
            opened.classList.remove("show");
        }
    });
}

// -----------------------------------------------------------------------------
// Verify email page modal
// -----------------------------------------------------------------------------

function initVerifyEmailModal() {
    if (!document.body.classList.contains("verify-email-page")) {
        return;
    }

    const modal = document.getElementById("modal-login-block");
    modal?.classList.add("show");
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

    const initial = document.body.dataset.activeTab || "login";

    const show = (target) => {
        const isLogin = target === "login";
        tabLogin.classList.toggle("active", isLogin);
        tabRegister.classList.toggle("active", !isLogin);
        panelLogin.classList.toggle("hidden", !isLogin);
        panelRegister.classList.toggle("hidden", isLogin);
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

    if (!emailInput || !enterpriseBlock) {
        return;
    }

    const toggle = () => {
        const value = (emailInput.value || "").toLowerCase();
        const isVlute =
            value.endsWith("@st.vlute.edu.vn") ||
            value.endsWith("@vlute.edu.vn");
        enterpriseBlock.classList.toggle(
            "hidden",
            isVlute || !value.includes("@")
        );
    };

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

    const shouldShow = document.body.dataset.unapproved === "1";
    if (!shouldShow) {
        return;
    }

    const modal = document.getElementById("modal-login-block");
    const modalBody = document.getElementById("modal-login-block-body");
    const email = document.body.dataset.unapprovedEmail || "";

    if (modalBody) {
        modalBody.innerHTML = `
            <p>Tài khoản <strong>${email}</strong> đang chờ phê duyệt.</p>
            <p>Vui lòng đợi quản trị viên xác nhận. Bạn có thể quay lại sau.</p>
        `;
    }

    modal?.classList.add("show");
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
