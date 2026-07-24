(() => {
  const reduceMotion = window.matchMedia("(prefers-reduced-motion: reduce)").matches;
  const body = document.body;

  // Barra de progresso de scroll
  const progress = document.querySelector("[data-scroll-progress]");
  const updateProgress = () => {
    if (!progress) return;
    const max = document.documentElement.scrollHeight - window.innerHeight;
    const value = max > 0 ? (window.scrollY / max) * 100 : 0;
    progress.style.transform = `scaleX(${Math.min(100, Math.max(0, value)) / 100})`;
  };

  // Header com sombra
  const header = document.querySelector("[data-header]");
  const onScrollChrome = () => {
    if (header) header.classList.toggle("is-scrolled", window.scrollY > 12);
    updateProgress();
    const backTop = document.querySelector("[data-back-top]");
    if (backTop) backTop.hidden = window.scrollY < 480;
  };
  onScrollChrome();
  window.addEventListener("scroll", onScrollChrome, { passive: true });

  // Menu mobile
  const nav = document.querySelector("[data-nav]");
  const toggle = document.querySelector("[data-nav-toggle]");
  const closeNav = () => {
    if (!nav || !toggle) return;
    nav.classList.remove("is-open");
    toggle.setAttribute("aria-expanded", "false");
    body.classList.remove("nav-open");
  };
  if (toggle && nav) {
    toggle.addEventListener("click", () => {
      const open = !nav.classList.contains("is-open");
      nav.classList.toggle("is-open", open);
      toggle.setAttribute("aria-expanded", open ? "true" : "false");
      body.classList.toggle("nav-open", open);
    });
    nav.querySelectorAll("a").forEach((link) => {
      link.addEventListener("click", closeNav);
    });
    document.addEventListener("keydown", (e) => {
      if (e.key === "Escape") closeNav();
    });
  }

  // Scrollspy
  const navLinks = Array.from(document.querySelectorAll("[data-nav-link]"));
  const sections = navLinks
    .map((link) => {
      const id = link.getAttribute("href");
      return id ? document.querySelector(id) : null;
    })
    .filter(Boolean);

  const setActiveLink = () => {
    let current = sections[0];
    const offset = 120;
    sections.forEach((section) => {
      if (section.getBoundingClientRect().top - offset <= 0) current = section;
    });
    navLinks.forEach((link) => {
      const match = current && link.getAttribute("href") === `#${current.id}`;
      link.classList.toggle("is-active", Boolean(match));
    });
  };
  window.addEventListener("scroll", setActiveLink, { passive: true });
  setActiveLink();

  // Reveal
  const nodes = document.querySelectorAll("[data-reveal]");
  if (nodes.length) {
    if (reduceMotion || !("IntersectionObserver" in window)) {
      nodes.forEach((el) => el.classList.add("is-visible"));
    } else {
      const observer = new IntersectionObserver(
        (entries, obs) => {
          entries.forEach((entry) => {
            if (!entry.isIntersecting) return;
            entry.target.classList.add("is-visible");
            obs.unobserve(entry.target);
          });
        },
        { rootMargin: "0px 0px -10% 0px", threshold: 0.12 }
      );
      nodes.forEach((el) => observer.observe(el));
    }
  }

  // Pré-selecionar serviço + scroll ao formulário
  const serviceSelect = document.querySelector("[data-service-select]");
  document.querySelectorAll("[data-select-service]").forEach((btn) => {
    btn.addEventListener("click", (e) => {
      const value = btn.getAttribute("data-select-service");
      if (!value || !serviceSelect) return;
      // deixa o hash navegar; seleciona após um tick
      window.setTimeout(() => {
        serviceSelect.value = value;
        serviceSelect.dispatchEvent(new Event("change", { bubbles: true }));
        serviceSelect.focus({ preventScroll: true });
        const formWrap = document.querySelector(".contact-form-wrap");
        if (formWrap) formWrap.classList.add("is-highlight");
        window.setTimeout(() => formWrap && formWrap.classList.remove("is-highlight"), 1200);
      }, 50);
    });
  });

  // Máscara de telefone BR
  const phoneInput = document.querySelector("[data-phone-mask]");
  if (phoneInput) {
    const formatPhone = (raw) => {
      const digits = raw.replace(/\D+/g, "").slice(0, 11);
      if (digits.length === 0) return "";
      if (digits.length <= 2) return `(${digits}`;
      if (digits.length <= 6) return `(${digits.slice(0, 2)}) ${digits.slice(2)}`;
      if (digits.length <= 10) {
        return `(${digits.slice(0, 2)}) ${digits.slice(2, 6)}-${digits.slice(6)}`;
      }
      return `(${digits.slice(0, 2)}) ${digits.slice(2, 7)}-${digits.slice(7)}`;
    };
    phoneInput.addEventListener("input", () => {
      const start = phoneInput.selectionStart;
      const before = phoneInput.value.length;
      phoneInput.value = formatPhone(phoneInput.value);
      const after = phoneInput.value.length;
      if (typeof start === "number") {
        phoneInput.setSelectionRange(start + (after - before), start + (after - before));
      }
    });
  }

  // Submit loading
  const form = document.querySelector("[data-lead-form]");
  if (form) {
    form.addEventListener("submit", () => {
      const btn = form.querySelector("[data-submit-btn]");
      const label = form.querySelector("[data-submit-label]");
      const loading = form.querySelector("[data-submit-loading]");
      if (btn) {
        btn.disabled = true;
        btn.classList.add("is-loading");
      }
      if (label) label.hidden = true;
      if (loading) loading.hidden = false;
    });
  }

  // Foco no alerta de flash
  const flash = document.querySelector("[data-flash-alert]");
  if (flash) {
    flash.focus({ preventScroll: true });
  }

  // Voltar ao topo
  const backTop = document.querySelector("[data-back-top]");
  if (backTop) {
    backTop.addEventListener("click", () => {
      window.scrollTo({ top: 0, behavior: reduceMotion ? "auto" : "smooth" });
    });
  }

  // Esconder FAB perto do bloco de contato (reduz ruído)
  const waFab = document.querySelector("[data-wa-fab]");
  const contact = document.querySelector("#contato");
  if (waFab && contact && "IntersectionObserver" in window) {
    const fabObs = new IntersectionObserver(
      ([entry]) => {
        waFab.classList.toggle("is-hidden", entry.isIntersecting && entry.intersectionRatio > 0.2);
      },
      { threshold: [0.2] }
    );
    fabObs.observe(contact);
  }
})();
