</main>
<a class="whatsapp-float" href="https://wa.me/201000000000" target="_blank" rel="noopener" aria-label="WhatsApp">
    <i class="fa-brands fa-whatsapp"></i>
</a>
<footer class="site-footer">
    <section class="footer-grid">
        <article>
            <h3>Golden Hive</h3>
            <p>Luxury natural honey with heritage craftsmanship.</p>
        </article>
        <article>
            <h4>Contact</h4>
            <form id="contactForm" class="contact-form">
                <input name="name" placeholder="Name" required>
                <input name="email" type="email" placeholder="Email" required>
                <textarea name="message" placeholder="Message" required></textarea>
                <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
                <button class="honey-btn" type="submit">Send</button>
            </form>
        </article>
    </section>
    <p>© <?= date('Y') ?> Golden Hive</p>
</footer>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
<script src="/assets/js/app.js" defer></script>
</body>
</html>
