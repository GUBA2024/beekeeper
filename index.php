<?php
$pageTitle = 'Home';
require __DIR__ . '/includes/header.php';
?>
<section class="hero honey-bg" id="threeHero">
    <div class="honey-flow"></div>
    <div class="hero-content" data-aos="fade-up">
        <h1 data-i18n="hero_title">Golden Hive Luxury Honey</h1>
        <p data-i18n="hero_text">Pure artisanal honey crafted for premium taste and wellness.</p>
        <a class="honey-btn" href="/shop.php">Shop Collection</a>
    </div>
    <svg class="bee-svg" viewBox="0 0 120 40" aria-hidden="true"><circle cx="20" cy="20" r="6"/><circle cx="32" cy="20" r="6"/></svg>
</section>

<section class="stats" data-aos="fade-up">
    <div><h3 class="counter" data-target="12000">0</h3><p>Happy Customers</p></div>
    <div><h3 class="counter" data-target="98">0</h3><p>Purity Score</p></div>
    <div><h3 class="counter" data-target="24">0</h3><p>Countries</p></div>
</section>

<section class="swiper featured" data-aos="zoom-in">
    <div class="swiper-wrapper">
        <article class="swiper-slide product-card"><img loading="lazy" src="https://images.unsplash.com/photo-1471943038886-87c772c31367?w=800" alt="Royal honey"><h3>Royal Gold</h3><p>$59</p></article>
        <article class="swiper-slide product-card"><img loading="lazy" src="https://images.unsplash.com/photo-1587049352851-8d4e89133924?w=800" alt="Forest honey"><h3>Forest Amber</h3><p>$49</p></article>
        <article class="swiper-slide product-card"><img loading="lazy" src="https://images.unsplash.com/photo-1514996937319-344454492b37?w=800" alt="Clover honey"><h3>Clover Silk</h3><p>$45</p></article>
    </div>
</section>

<section class="grid two-col" data-aos="fade-up">
    <article>
        <h2>Why Choose Us</h2>
        <p>Single-origin sourcing, lab-tested purity, and sustainable beekeeping.</p>
    </article>
    <article>
        <h2>Testimonials</h2>
        <p>"The smoothest premium honey I have tasted." — Nour A.</p>
    </article>
</section>

<section class="newsletter" data-aos="fade-up">
    <h2>Join the Hive</h2>
    <form action="/api/contact.php" method="post">
        <input name="name" placeholder="Name" required>
        <input name="email" type="email" placeholder="Email" required>
        <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
        <button class="honey-btn" type="submit">Subscribe</button>
    </form>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
