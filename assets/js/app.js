(() => {
  // Mirror the PHP url() helper: prepend APP_BASE (set inline in <head>) to any path.
  const appUrl = (path) => APP_BASE + '/' + path.replace(/^\/+/, '');

  AOS.init({ duration: 900, once: true });

  const loader = document.getElementById('loader');
  window.addEventListener('load', () => setTimeout(() => loader?.classList.add('done'), 800));
  const showToast = (message, type = 'success') => {
    const wrap = document.querySelector('.toast-wrap') || (() => {
      const created = document.createElement('div');
      created.className = 'toast-wrap';
      document.body.appendChild(created);
      return created;
    })();
    const toast = document.createElement('div');
    toast.className = `toast toast-${type}`;
    toast.textContent = message;
    wrap.appendChild(toast);
    setTimeout(() => toast.remove(), 2400);
  };

  document.querySelectorAll('.counter').forEach((counter) => {
    const target = Number(counter.dataset.target || 0);
    gsap.to(counter, {
      innerText: target,
      duration: 2,
      snap: { innerText: 1 },
      scrollTrigger: { trigger: counter, start: 'top 85%' }
    });
  });

  new Swiper('.featured', {
    slidesPerView: 1.15,
    spaceBetween: 14,
    breakpoints: { 768: { slidesPerView: 2.2 }, 1100: { slidesPerView: 3 } },
    loop: true,
    autoplay: { delay: 2600 }
  });

  document.querySelectorAll('.add-cart').forEach((btn) => {
    btn.addEventListener('click', () => {
      const productId = btn.dataset.id;
      const formData = new FormData();
      formData.append('csrf_token', document.querySelector('input[name="csrf_token"]')?.value || '');
      formData.append('product_id', productId);
      formData.append('quantity', document.getElementById('qty')?.value || '1');
      fetch(appUrl('/api/cart.php?action=add'), { method: 'POST', body: formData })
        .then((res) => res.json())
        .then((data) => {
          if (data.redirect) {
            window.location.href = data.redirect;
            return;
          }
          if (data.ok) {
            btn.textContent = 'Added ✓';
            gsap.fromTo(btn, { scale: 1 }, { scale: 1.05, yoyo: true, repeat: 1, duration: .2 });
          } else {
            showToast(data.error || 'Could not add to cart', 'error');
          }
        })
        .catch(() => showToast('Network error', 'error'));
    });
  });

  const search = document.getElementById('productSearch');
  const sortSelect = document.getElementById('sortSelect');
  const grid = document.getElementById('productGrid');

  const applyGridControls = () => {
    if (!grid) return;
    const cards = Array.from(grid.querySelectorAll('.product-card'));
    const query = (search?.value || '').toLowerCase();
    cards.forEach((card) => { card.style.display = card.dataset.name.includes(query) ? '' : 'none'; });
    if (sortSelect?.value.startsWith('price')) {
      cards.sort((a, b) => Number(a.dataset.price) - Number(b.dataset.price));
      if (sortSelect.value.endsWith('desc')) cards.reverse();
      cards.forEach((card) => grid.append(card));
    }
  };
  search?.addEventListener('input', applyGridControls);
  sortSelect?.addEventListener('change', applyGridControls);

  const menuToggle = document.querySelector('.menu-toggle');
  const navLinks = document.querySelector('.nav-links');
  menuToggle?.addEventListener('click', () => navLinks?.classList.toggle('open'));

  const themeToggle = document.getElementById('themeToggle');
  const root = document.documentElement;
  root.dataset.theme = localStorage.getItem('theme') || 'light';
  themeToggle?.addEventListener('click', () => {
    root.dataset.theme = root.dataset.theme === 'light' ? 'dark' : 'light';
    localStorage.setItem('theme', root.dataset.theme);
  });

  const dictionary = {
    ar: { home: 'الرئيسية', shop: 'المتجر', cart: 'السلة', checkout: 'الدفع', dashboard: 'لوحة المستخدم', login: 'تسجيل الدخول', hero_title: 'غولدن هايف للعسل الفاخر', hero_text: 'عسل طبيعي فاخر بنكهة نقية' },
    en: { home: 'Home', shop: 'Shop', cart: 'Cart', checkout: 'Checkout', dashboard: 'Dashboard', login: 'Login', hero_title: 'Golden Hive Luxury Honey', hero_text: 'Pure artisanal honey crafted for premium taste and wellness.' }
  };

  let lang = localStorage.getItem('lang') || 'en';
  const applyLang = () => {
    document.documentElement.lang = lang;
    document.documentElement.dir = lang === 'ar' ? 'rtl' : 'ltr';
    document.querySelectorAll('[data-i18n]').forEach((el) => {
      const key = el.dataset.i18n;
      if (dictionary[lang]?.[key]) el.textContent = dictionary[lang][key];
    });
  };
  document.getElementById('langToggle')?.addEventListener('click', () => {
    lang = lang === 'en' ? 'ar' : 'en';
    localStorage.setItem('lang', lang);
    applyLang();
  });
  applyLang();

  const cursor = document.querySelector('.honey-cursor');
  document.addEventListener('mousemove', (e) => {
    cursor.style.left = `${e.clientX}px`;
    cursor.style.top = `${e.clientY}px`;
  });

  const passToggle = document.getElementById('togglePassword');
  passToggle?.addEventListener('click', () => {
    const input = document.getElementById('passwordInput');
    input.type = input.type === 'password' ? 'text' : 'password';
  });

  const contactForm = document.getElementById('contactForm');
  contactForm?.addEventListener('submit', async (e) => {
    e.preventDefault();
    const res = await fetch(appUrl('/api/contact.php'), { method: 'POST', body: new FormData(contactForm) });
    showToast(res.ok ? 'Message sent' : 'Unable to send message', res.ok ? 'success' : 'error');
    contactForm.reset();
  });

  if (document.getElementById('threeHero')) {
    const scene = new THREE.Scene();
    const camera = new THREE.PerspectiveCamera(75, window.innerWidth / window.innerHeight, 0.1, 1000);
    const renderer = new THREE.WebGLRenderer({ alpha: true, antialias: true });
    renderer.setSize(window.innerWidth, Math.min(window.innerHeight, 600));
    document.getElementById('threeHero').appendChild(renderer.domElement);
    renderer.domElement.style.position = 'absolute';
    renderer.domElement.style.inset = '0';

    const geometry = new THREE.IcosahedronGeometry(2.2, 3);
    const material = new THREE.MeshStandardMaterial({ color: 0xF4B400, metalness: 0.4, roughness: 0.22 });
    const honeyMesh = new THREE.Mesh(geometry, material);
    scene.add(honeyMesh);
    scene.add(new THREE.PointLight(0xffd451, 2.2, 70));
    const ambient = new THREE.AmbientLight(0xffffff, 0.7);
    scene.add(ambient);
    camera.position.z = 5;

    const animate = () => {
      requestAnimationFrame(animate);
      honeyMesh.rotation.x += 0.003;
      honeyMesh.rotation.y += 0.006;
      renderer.render(scene, camera);
    };
    animate();

    window.addEventListener('mousemove', (e) => {
      gsap.to(honeyMesh.rotation, { x: (e.clientY / innerHeight - 0.5) * 0.6, y: (e.clientX / innerWidth - 0.5) * 0.8, duration: 1 });
    });

    window.addEventListener('resize', () => {
      camera.aspect = window.innerWidth / window.innerHeight;
      camera.updateProjectionMatrix();
      renderer.setSize(window.innerWidth, Math.min(window.innerHeight, 600));
    });
  }
})();
