<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Manpower Services – Verified & Trusted Professionals</title>
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css" rel="stylesheet">
<style>
  /* ============ RESET & ROOT ============ */
  * { margin:0; padding:0; box-sizing:border-box; }

  :root {
    --teal: #1BA9A2;
    --teal-dark: #117873;
    --teal-deep: #0d5f5a;
    --dark-bg: #0f172a;
    --dark-bg-2: #1a2332;
    --dark-bg-3: #0b1220;
    --primary-gradient: linear-gradient(135deg, #1BA9A2 0%, #117873 100%);
    --primary-light: rgba(27, 169, 162, 0.1);
    --text-dark: #0f172a;
    --text-gray: #475569;
    --text-light: #94a3b8;
    --border-color: rgba(226, 232, 240, 0.8);
    --bg-light: #f8fafc;
    --bg-teal-tint: #f0fdfa;
    --glass-bg: rgba(255, 255, 255, 0.85);
    --shadow-sm: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
    --shadow-md: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
    --shadow-hover: 0 20px 25px -5px rgba(27, 169, 162, 0.25), 0 10px 10px -5px rgba(27, 169, 162, 0.1);
  }

  html { scroll-behavior: smooth; }

  body {
    font-family: 'Outfit', sans-serif;
    color: var(--text-dark);
    -webkit-font-smoothing: antialiased;
    background: #ffffff;
    overflow-x: hidden;
  }

  .container {
    max-width: 1240px;
    margin: 0 auto;
    padding: 0 2rem;
    width: 100%;
  }

  /* ============ HERO SECTION ============ */
  .hero-sec {
    padding: 5rem 0 4rem 0;
    background: #ffffff;
    position: relative;
    overflow: hidden;
  }

  .hero-sec::before {
    content: '';
    position: absolute;
    width: 500px; height: 400px;
    background: radial-gradient(circle, rgba(167,139,250,0.15) 0%, rgba(255,255,255,0) 70%);
    top: -100px;
    left: -150px;
    border-radius: 50%;
    z-index: 0;
  }

  .hero-container {
    max-width: 1340px;
    margin: 0 auto;
    padding: 0 2.5rem;
    display: grid;
    grid-template-columns: 1.05fr 1fr;
    gap: 3rem;
    align-items: center;
    position: relative;
    z-index: 1;
  }

  /* ----- LEFT ----- */
  .hero-left { padding-right: 1rem; }

  /* ⬇️ TEXT STYLE UPDATED — clean, modern, smaller & lighter */
  .hero-title {
    font-size: 2.6rem;
    font-weight: 600;
    line-height: 1.25;
    color: #111827;
    letter-spacing: -0.015em;
    margin-bottom: 1.2rem;
  }

  .hero-title .highlight {
    color: var(--teal);
    font-weight: 700;
  }

  .hero-subtitle {
    font-size: 1rem;
    color: #4b5563;
    line-height: 1.7;
    max-width: 560px;
    margin-bottom: 2rem;
    font-weight: 400;
    letter-spacing: 0.005em;
  }

  /* ----- Trust stats row ----- */
  .trust-stats {
    display: flex;
    gap: 2.5rem;
    margin-bottom: 2.2rem;
    flex-wrap: wrap;
  }

  .trust-item {
    display: flex;
    align-items: center;
    gap: 0.6rem;
  }

  .trust-icon {
    width: 30px;
    height: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
  }

  .trust-icon svg {
    width: 24px;
    height: 24px;
    stroke: #1f2937;
    stroke-width: 1.5;
    fill: none;
  }

  .trust-text { line-height: 1.3; }

  .trust-number {
    font-size: 0.95rem;
    font-weight: 700;
    color: #111827;
    letter-spacing: -0.01em;
  }

  .trust-label {
    font-size: 0.85rem;
    color: #6b7280;
    font-weight: 400;
  }

  /* ----- App Store Buttons ----- */
  .app-buttons {
    display: flex;
    gap: 0.9rem;
    flex-wrap: wrap;
  }

  .store-btn {
    background: #000;
    color: #fff;
    display: flex;
    align-items: center;
    gap: 0.65rem;
    padding: 0.5rem 1.1rem;
    border-radius: 10px;
    text-decoration: none;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    border: 1px solid #000;
    min-width: 165px;
  }

  .store-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 22px -8px rgba(0,0,0,0.4);
  }

  .store-btn i { font-size: 1.75rem; line-height: 1; }

  .store-text {
    display: flex;
    flex-direction: column;
    line-height: 1.15;
  }

  .store-small {
    font-size: 0.65rem;
    font-weight: 400;
    letter-spacing: 0.3px;
    opacity: 0.9;
  }

  .store-large {
    font-size: 1.15rem;
    font-weight: 600;
    letter-spacing: -0.01em;
  }

  /* ----- RIGHT ----- */
  .hero-right {
    position: relative;
    display: flex;
    justify-content: center;
    align-items: flex-end;
    min-height: 480px;
  }

  .hero-right img {
    width: 100%;
    max-width: 640px;
    height: auto;
    object-fit: contain;
    display: block;
    filter: drop-shadow(0 15px 35px rgba(0,0,0,0.08));
  }

  .rating-card {
    position: absolute;
    top: 38%;
    left: 32%;
    background: #ffffff;
    border-radius: 14px;
    padding: 0.85rem 1.25rem 0.85rem 1rem;
    display: flex;
    align-items: center;
    gap: 0.85rem;
    box-shadow: 0 12px 30px -8px rgba(0,0,0,0.2), 0 4px 12px -4px rgba(0,0,0,0.1);
    z-index: 2;
    animation: floatCard 4s ease-in-out infinite;
  }

  @keyframes floatCard {
    0%,100% { transform: translateY(0); }
    50% { transform: translateY(-8px); }
  }

  .rating-star {
    width: 42px;
    height: 42px;
    border-radius: 50%;
    background: #F97316;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
  }

  .rating-star i { color: #fff; font-size: 1.3rem; }

  .rating-info { line-height: 1.25; }

  .rating-score {
    font-size: 1.3rem;
    font-weight: 800;
    color: #111827;
    letter-spacing: -0.01em;
  }

  .rating-count {
    font-size: 0.85rem;
    color: #6b7280;
    font-weight: 400;
  }

  /* ============ SECTION TITLES ============ */
  .section-title {
    font-size: 2.2rem;
    font-weight: 700;
    color: var(--text-dark);
    margin-bottom: 1rem;
    position: relative;
    display: inline-block;
    letter-spacing: -0.02em;
  }

  .section-title::after {
    content: '';
    position: absolute;
    bottom: -10px;
    left: 50%;
    transform: translateX(-50%);
    width: 60px;
    height: 4px;
    background: var(--primary-gradient);
    border-radius: 2px;
  }

  .text-center { text-align: center; }
  .text-muted { color: var(--text-gray) !important; }
  .text-primary { color: var(--teal) !important; }
  .mt-5 { margin-top: 3rem; }
  .mb-5 { margin-bottom: 3rem; }

  /* ============ CATEGORIES ============ */
  .categories-sec {
    padding: 7rem 0;
    background: var(--bg-teal-tint);
  }

  .cat-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 2rem;
    margin-top: 3rem;
  }

  .cat-card {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    background: white;
    border: 1px solid var(--border-color);
    border-radius: 24px;
    padding: 3rem 2rem;
    text-align: center;
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    text-decoration: none !important;
    box-shadow: var(--shadow-sm);
    position: relative;
    overflow: hidden;
    z-index: 1;
    cursor: pointer;
  }

  .cat-card::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0; bottom: 0;
    background: var(--primary-gradient);
    opacity: 0;
    z-index: -1;
    transition: opacity 0.4s ease;
  }

  .cat-card:hover {
    transform: translateY(-10px);
    border-color: transparent;
    box-shadow: var(--shadow-hover);
  }

  .cat-card:hover::before { opacity: 1; }

  .cat-icon-wrapper {
    width: 80px;
    height: 80px;
    background: var(--primary-light);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2.5rem;
    margin-bottom: 1.5rem;
    transition: all 0.4s ease;
  }

  .cat-card:hover .cat-icon-wrapper {
    background: white;
    transform: scale(1.1);
  }

  .cat-name {
    font-size: 1.15rem;
    font-weight: 600;
    color: var(--text-dark);
    margin-bottom: 0;
    transition: color 0.4s ease;
    letter-spacing: -0.01em;
  }

  .cat-card:hover .cat-name {
    color: white;
    -webkit-text-fill-color: white;
  }

  .cat-card.explore-card {
    background: var(--primary-light);
    border-color: transparent;
  }
  .cat-card.explore-card .cat-icon-wrapper { background: white; }
  .cat-card.explore-card:hover { background: var(--primary-gradient); }
  .cat-card.explore-card:hover .cat-name {
    color: white;
    -webkit-text-fill-color: white;
  }

  /* ============ BUNDLES ============ */
  .bundles-sec {
    padding: 7rem 0;
    background: var(--dark-bg);
    background: radial-gradient(circle at 20% 80%, #1a3a3a 0%, var(--dark-bg) 60%, var(--dark-bg-3) 100%);
    position: relative;
    overflow: hidden;
  }

  .bundles-sec::before {
    content: '';
    position: absolute;
    width: 500px; height: 500px;
    background: radial-gradient(circle, rgba(27,169,162,0.15) 0%, rgba(255,255,255,0) 70%);
    top: -200px; left: -100px;
    border-radius: 50%;
  }

  .bundles-sec .section-title { color: #ffffff; }
  .bundles-sec .section-title::after { background: var(--primary-gradient); }

  .bundles-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 2.5rem;
    margin-top: 3rem;
    position: relative;
    z-index: 1;
  }

  .bundle-card {
    background: rgba(255,255,255,0.05);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(27,169,162,0.25);
    border-radius: 24px;
    padding: 3rem;
    display: flex;
    gap: 2rem;
    align-items: flex-start;
    transition: all 0.4s ease;
    box-shadow: var(--shadow-md);
    position: relative;
    overflow: hidden;
  }

  .bundle-card::before {
    content: '';
    position: absolute;
    top: -50%; right: -50%;
    width: 100%; height: 100%;
    background: radial-gradient(circle, rgba(27,169,162,0.2) 0%, transparent 70%);
    transition: all 0.6s ease;
    z-index: 0;
  }

  .bundle-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 40px -10px rgba(27,169,162,0.4);
    border-color: var(--teal);
  }

  .bundle-card:hover::before { transform: scale(1.5); }

  .bundle-icon-box {
    width: 70px;
    height: 70px;
    background: rgba(27,169,162,0.15);
    border: 1px solid rgba(27,169,162,0.3);
    border-radius: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2.25rem;
    flex-shrink: 0;
    position: relative;
    z-index: 1;
  }

  .bundle-info { flex-grow: 1; position: relative; z-index: 1; }

  .bundle-tag {
    display: inline-block;
    background: linear-gradient(135deg, #2dd4bf 0%, #1BA9A2 100%);
    color: #ffffff;
    font-size: 0.75rem;
    font-weight: 700;
    padding: 0.35rem 0.9rem;
    border-radius: 9999px;
    margin-bottom: 1rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
  }

  .bundle-title {
    font-size: 1.35rem;
    font-weight: 700;
    color: #ffffff;
    margin-bottom: 0.65rem;
    line-height: 1.35;
    letter-spacing: -0.01em;
  }

  .bundle-desc {
    font-size: 0.98rem;
    color: var(--text-light);
    line-height: 1.6;
    font-weight: 400;
  }

  /* ============ HOW IT WORKS ============ */
  .how-sec { padding: 7rem 0; background: white; }

  .how-subtitle {
    font-size: 1.05rem;
    color: var(--text-gray);
    margin-top: 1rem;
    margin-bottom: 4rem;
    font-weight: 400;
  }

  .steps-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 3rem;
    position: relative;
  }

  .steps-grid::before {
    content: '';
    position: absolute;
    top: 35px;
    left: 10%;
    right: 10%;
    height: 2px;
    background: repeating-linear-gradient(90deg, rgba(27,169,162,0.3) 0px, rgba(27,169,162,0.3) 8px, transparent 8px, transparent 16px);
    z-index: 0;
  }

  .step-card {
    text-align: center;
    position: relative;
    z-index: 1;
    background: white;
    padding: 1rem;
  }

  .step-number {
    width: 70px;
    height: 70px;
    background: var(--primary-gradient);
    color: white;
    font-size: 1.4rem;
    font-weight: 700;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 2rem auto;
    box-shadow: 0 10px 20px rgba(27, 169, 162, 0.3);
    border: 6px solid white;
    transition: transform 0.3s ease;
  }

  .step-card:hover .step-number { transform: scale(1.1) rotate(5deg); }

  .step-title {
    font-size: 1.15rem;
    font-weight: 700;
    color: var(--text-dark);
    margin-bottom: 0.65rem;
    letter-spacing: -0.01em;
  }

  .step-desc {
    font-size: 0.95rem;
    color: var(--text-gray);
    line-height: 1.6;
  }

  /* ============ FEATURES ============ */
  .features-sec { padding: 7rem 0; background: var(--bg-teal-tint); }

  .features-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 2.5rem;
    margin-top: 3rem;
  }

  .feature-card {
    background: white;
    border: 1px solid var(--border-color);
    border-radius: 24px;
    padding: 3.5rem 2.5rem;
    text-align: center;
    transition: all 0.4s ease;
    box-shadow: var(--shadow-sm);
    position: relative;
    overflow: hidden;
  }

  .feature-card::after {
    content: '';
    position: absolute;
    bottom: 0; left: 0;
    width: 100%; height: 4px;
    background: var(--primary-gradient);
    transform: scaleX(0);
    transition: transform 0.4s ease;
  }

  .feature-card:hover { transform: translateY(-8px); box-shadow: var(--shadow-md); }
  .feature-card:hover::after { transform: scaleX(1); }

  .feature-icon-wrapper {
    width: 80px;
    height: 80px;
    background: var(--primary-light);
    color: var(--teal);
    border-radius: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2.5rem;
    margin: 0 auto 2rem auto;
    transform: rotate(-10deg);
    transition: all 0.4s ease;
  }

  .feature-card:hover .feature-icon-wrapper {
    transform: rotate(0deg) scale(1.1);
    background: var(--teal);
    color: white;
  }

  .feature-title {
    font-size: 1.35rem;
    font-weight: 700;
    color: var(--text-dark);
    margin-bottom: 0.85rem;
    letter-spacing: -0.01em;
  }

  .feature-desc {
    font-size: 1rem;
    color: var(--text-gray);
    line-height: 1.6;
  }

  /* ============ TESTIMONIALS ============ */
  .testimonials-sec { padding: 7rem 0; background: white; }

  .section-subtitle {
    color: var(--text-gray);
    font-size: 1.05rem;
    max-width: 600px;
    margin: 1.5rem auto 0;
    font-weight: 400;
  }

  .hourly-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    background: var(--primary-gradient);
    color: white;
    padding: 0.45rem 1.15rem;
    border-radius: 9999px;
    font-weight: 600;
    font-size: 0.9rem;
    margin-bottom: 2rem;
    box-shadow: 0 4px 10px rgba(27, 169, 162, 0.3);
  }

  .testimonials-slider {
    display: flex;
    overflow-x: auto;
    scroll-snap-type: x mandatory;
    scroll-behavior: smooth;
    padding: 1rem 0 2rem 0;
    gap: 1.5rem;
  }

  .testimonials-slider::-webkit-scrollbar { height: 8px; }
  .testimonials-slider::-webkit-scrollbar-track { background: #f1f5f9; border-radius: 4px; }
  .testimonials-slider::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
  .testimonials-slider::-webkit-scrollbar-thumb:hover { background: var(--teal); }

  .testimonial-slide {
    scroll-snap-align: center;
    flex: 0 0 100%;
    max-width: 100%;
  }

  .testimonial-card {
    background: var(--bg-teal-tint);
    border: 1px solid rgba(27,169,162,0.15);
    border-radius: 24px;
    padding: 3rem 2.5rem;
    box-shadow: var(--shadow-sm);
    transition: all 0.4s ease;
    position: relative;
    height: 100%;
  }

  .testimonial-card:hover {
    transform: translateY(-5px);
    box-shadow: var(--shadow-md);
    border-color: var(--teal);
  }

  .quote-icon {
    position: absolute;
    top: 1.5rem;
    right: 2rem;
    font-size: 4rem;
    color: var(--primary-light);
    opacity: 0.7;
    line-height: 1;
    font-family: serif;
  }

  .client-info {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 1.5rem;
  }

  .avatar {
    width: 55px;
    height: 55px;
    border-radius: 50%;
    background: var(--primary-gradient);
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 1.1rem;
    color: #ffffff;
    border: 3px solid rgba(27,169,162,0.2);
  }

  .client-name {
    font-size: 1.05rem;
    font-weight: 700;
    color: var(--text-dark);
    margin-bottom: 0.25rem;
    letter-spacing: -0.01em;
  }

  .client-role {
    font-size: 0.85rem;
    color: var(--teal);
    font-weight: 500;
  }

  .testimonial-text {
    font-size: 1rem;
    color: var(--text-gray);
    line-height: 1.65;
    font-style: italic;
  }

  /* ============ PARTNERS ============ */
  .partners-sec {
    padding: 5rem 0 6rem 0;
    background: var(--dark-bg-3);
    border-top: 1px solid rgba(27,169,162,0.15);
  }

  .partners-label {
    font-size: 0.85rem;
    font-weight: 600;
    color: var(--teal);
    text-transform: uppercase;
    letter-spacing: 2px;
    margin-bottom: 2rem;
    opacity: 0.8;
  }

  .partner-logo {
    opacity: 0.5;
    transition: all 0.3s ease;
    filter: grayscale(100%);
    cursor: pointer;
    font-weight: 800;
    color: #e2e8f0;
    font-size: 1.6rem;
    margin: 0;
    letter-spacing: -0.01em;
  }

  .partner-logo:hover {
    opacity: 1;
    filter: grayscale(0%);
    transform: scale(1.05);
    color: var(--teal) !important;
  }

  .partners-track {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 2rem;
    padding-top: 2rem;
  }

  /* ============ RESPONSIVE ============ */
  @media (min-width: 768px) {
    .testimonial-slide {
      flex: 0 0 calc(50% - 0.75rem);
      max-width: calc(50% - 0.75rem);
    }
  }
  @media (min-width: 1024px) {
    .testimonial-slide {
      flex: 0 0 calc(33.333% - 1rem);
      max-width: calc(33.333% - 1rem);
    }
  }
  @media (max-width: 1200px) {
    .hero-title { font-size: 2.3rem; }
    .trust-stats { gap: 2rem; }
  }
  @media (max-width: 991px) {
    .hero-container {
      grid-template-columns: 1fr;
      gap: 2.5rem;
      padding: 0 1.5rem;
    }
    .hero-title { font-size: 2.1rem; }
    .hero-right { min-height: auto; }
    .hero-right img { max-width: 100%; }
    .rating-card { top: 20%; left: 8%; }
    .cat-grid, .features-grid { grid-template-columns: repeat(2, 1fr); }
    .steps-grid::before { display: none; }
    .steps-grid { gap: 2rem; }
  }
  @media (max-width: 768px) {
    .bundles-grid, .steps-grid, .features-grid, .cat-grid { grid-template-columns: 1fr; }
    .partners-track { justify-content: center; }
    .partner-logo { margin: 1rem !important; }
    .section-title { font-size: 1.8rem; }
    .bundle-card { flex-direction: column; align-items: center; text-align: center; padding: 2rem; }
    .testimonial-card { padding: 2rem 1.5rem; }
    .quote-icon { font-size: 3rem; right: 1rem; top: 1rem; }
  }
  @media (max-width: 600px) {
    .hero-sec { padding: 3rem 0 2rem 0; }
    .hero-title { font-size: 1.7rem; }
    .hero-subtitle { font-size: 0.95rem; }
    .trust-stats { gap: 1.2rem; flex-direction: column; }
    .app-buttons { flex-direction: column; align-items: flex-start; }
    .store-btn { width: 100%; max-width: 220px; }
    .rating-card {
      padding: 0.6rem 0.9rem;
      gap: 0.6rem;
      top: 12%;
      left: 4%;
    }
    .rating-star { width: 34px; height: 34px; }
    .rating-star i { font-size: 1rem; }
    .rating-score { font-size: 1.05rem; }
    .rating-count { font-size: 0.75rem; }
    .container { padding: 0 1.2rem; }
  }
  /* ============ FAQ ============ */
  .faq-sec { padding: 7rem 0; background: var(--bg-light); }

  .faq-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1.5rem;
    margin-top: 3.5rem;
  }

  .faq-item {
    background: white;
    border: 1px solid var(--border-color);
    border-radius: 20px;
    padding: 2rem 2.5rem;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: var(--shadow-sm);
  }

  .faq-item:hover, .faq-item.active {
    border-color: var(--teal);
    box-shadow: var(--shadow-hover);
  }

  .faq-question {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 1rem;
  }

  .faq-q-text {
    font-size: 1rem;
    font-weight: 600;
    color: var(--text-dark);
    line-height: 1.5;
  }

  .faq-toggle {
    width: 30px;
    height: 30px;
    border-radius: 50%;
    background: var(--primary-light);
    color: var(--teal);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
    flex-shrink: 0;
    transition: all 0.3s ease;
  }

  .faq-item.active .faq-toggle {
    background: var(--teal);
    color: white;
    transform: rotate(45deg);
  }

  .faq-answer {
    display: none;
    margin-top: 1rem;
    font-size: 0.95rem;
    color: var(--text-gray);
    line-height: 1.65;
  }

  .faq-item.active .faq-answer { display: block; }

  @media (max-width: 768px) {
    .faq-grid { grid-template-columns: 1fr; }
  }

  /* ============ PARTNER IMAGE ============ */
  .partner-img-wrap {
    display: flex;
    align-items: center;
    justify-content: center;
  }

  .partner-img-wrap img {
    max-height: 50px;
    max-width: 140px;
    object-fit: contain;
    opacity: 0.5;
    filter: grayscale(100%);
    transition: all 0.3s ease;
  }

  .partner-img-wrap img:hover {
    opacity: 1;
    filter: grayscale(0%);
    transform: scale(1.05);
  }

  /* ============ TESTIMONIAL AVATAR IMAGE ============ */
  .avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 50%;
  }
</style>
</head>
<body>

<!-- ============ HERO SECTION ============ -->
<section class="hero-sec">
  <div class="hero-container">

    <!-- LEFT -->
    <div class="hero-left">
      <h1 class="hero-title">
        Manpower Services –<br>
        <span class="highlight">Verified &amp; Trusted</span> Professionals
      </h1>

      <p class="hero-subtitle">
        Book trained nurses, ayas, drivers, sanitation workers, and more on an hourly basis. Fast, reliable, and affordable manpower solutions — anytime, anywhere.
      </p>

      <!-- trust stats -->
      <div class="trust-stats">
        <div class="trust-item">
          <div class="trust-icon">
            <svg viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
              <path d="M9 12l2 2 4-4"/>
              <path d="M12 2l2.4 1.8L17 3.5l.6 2.7 2.6.6-.6 2.6L21.8 12l-2.2 1.6.6 2.6-2.6.6-.6 2.7-2.6-.6L12 22l-2.4-1.8L7 20.5l-.6-2.7-2.6-.6.6-2.6L2.2 12l2.2-1.6-.6-2.6 2.6-.6.6-2.7 2.6.6L12 2z"/>
            </svg>
          </div>
          <div class="trust-text">
            <div class="trust-number">41,510 +</div>
            <div class="trust-label">Verified Providers</div>
          </div>
        </div>

        <div class="trust-item">
          <div class="trust-icon">
            <svg viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
              <polyline points="3 17 9 11 13 15 21 7"/>
              <polyline points="15 7 21 7 21 13"/>
            </svg>
          </div>
          <div class="trust-text">
            <div class="trust-number">15,000 +</div>
            <div class="trust-label">Services Completed</div>
          </div>
        </div>

        <div class="trust-item">
          <div class="trust-icon">
            <svg viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
              <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
            </svg>
          </div>
          <div class="trust-text">
            <div class="trust-number">12,521</div>
            <div class="trust-label">Reviews Globally</div>
          </div>
        </div>
      </div>

      <!-- app store buttons -->
      <div class="app-buttons">
        <a href="javascript:void(0)" class="store-btn">
          <i class="ri-apple-fill"></i>
          <div class="store-text">
            <span class="store-small">Available on the</span>
            <span class="store-large">App Store</span>
          </div>
        </a>
        <a href="javascript:void(0)" class="store-btn">
          <i class="ri-google-play-fill"></i>
          <div class="store-text">
            <span class="store-small">Get it on</span>
            <span class="store-large">Google play</span>
          </div>
        </a>
      </div>
    </div>

    <!-- RIGHT -->
    <div class="hero-right">
      <img
        src="https://images.unsplash.com/photo-1552664730-d307ca884978?w=900&h=700&fit=crop&crop=faces"
        alt="Verified manpower professionals team">

      <div class="rating-card">
        <div class="rating-star">
          <i class="ri-star-fill"></i>
        </div>
        <div class="rating-info">
          <div class="rating-score">4.9 / 5</div>
          <div class="rating-count">(230 reviews)</div>
        </div>
      </div>
    </div>

  </div>
</section>

<!-- ============ CATEGORIES ============ -->
<section class="categories-sec">
  <div class="container">
    <h2 class="section-title text-center">What do you need?</h2>
    <p class="text-center text-muted mb-5" style="margin-top: 1rem;">Book trained professionals for your home &amp; family needs.</p>
    <div class="cat-grid">
      <div class="cat-card"><div class="cat-icon-wrapper">👩‍⚕️</div><h3 class="cat-name">Nurses</h3></div>
      <div class="cat-card"><div class="cat-icon-wrapper">👶</div><h3 class="cat-name">Aya / Nanny</h3></div>
      <div class="cat-card"><div class="cat-icon-wrapper">🚗</div><h3 class="cat-name">Drivers</h3></div>
      <div class="cat-card"><div class="cat-icon-wrapper">🧹</div><h3 class="cat-name">Sanitation</h3></div>
      <div class="cat-card"><div class="cat-icon-wrapper">👨‍🍳</div><h3 class="cat-name">Cooks</h3></div>
      <div class="cat-card"><div class="cat-icon-wrapper">🔧</div><h3 class="cat-name">Handyman</h3></div>
      <div class="cat-card explore-card"><div class="cat-icon-wrapper">📱</div><h3 class="cat-name text-primary">Explore App</h3></div>
    </div>
  </div>
</section>

<!-- ============ BUNDLES ============ -->
<section class="bundles-sec">
  <div class="container">
    <h2 class="section-title text-center">Exclusive In-App Bundles</h2>
    <div class="bundles-grid">
      <div class="bundle-card">
        <div class="bundle-icon-box">🧹</div>
        <div class="bundle-info">
          <span class="bundle-tag">Best Seller</span>
          <h3 class="bundle-title">Daily essential cleaning bundle</h3>
          <p class="bundle-desc">Sweeping, mopping and utensils with a single booking!</p>
        </div>
      </div>
      <div class="bundle-card">
        <div class="bundle-icon-box">🚿</div>
        <div class="bundle-info">
          <span class="bundle-tag">Super Value</span>
          <h3 class="bundle-title">Bathroom deep clean pack</h3>
          <p class="bundle-desc">Complete bathroom cleaning + disinfection at ₹150 only!</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ============ HOW IT WORKS ============ -->
<section class="how-sec">
  <div class="container text-center">
    <h2 class="section-title">How It Works</h2>
    <p class="how-subtitle">Book a service in seconds straight from your phone</p>
    <div class="steps-grid">
      <div class="step-card"><div class="step-number">1</div><h3 class="step-title">Download App</h3><p class="step-desc">Available on iOS &amp; Android</p></div>
      <div class="step-card"><div class="step-number">2</div><h3 class="step-title">Choose Service</h3><p class="step-desc">Pick what you need done</p></div>
      <div class="step-card"><div class="step-number">3</div><h3 class="step-title">Provider Accepts</h3><p class="step-desc">Verified pro arrives shortly</p></div>
      <div class="step-card"><div class="step-number">4</div><h3 class="step-title">Secure Payment</h3><p class="step-desc">Track and pay directly in-app</p></div>
    </div>
  </div>
</section>

<!-- ============ FEATURES ============ -->
<section class="features-sec">
  <div class="container">
    <div class="features-grid">
      <div class="feature-card">
        <div class="feature-icon-wrapper"><i class="ri-time-line"></i></div>
        <h3 class="feature-title">Quick Booking</h3>
        <p class="feature-desc">Professional at your door within minutes</p>
      </div>
      <div class="feature-card">
        <div class="feature-icon-wrapper"><i class="ri-shield-check-line"></i></div>
        <h3 class="feature-title">Verified Pros</h3>
        <p class="feature-desc">Background checked &amp; certified</p>
      </div>
      <div class="feature-card">
        <div class="feature-icon-wrapper"><i class="ri-star-line"></i></div>
        <h3 class="feature-title">Top Rated</h3>
        <p class="feature-desc">4.8+ average rating from customers</p>
      </div>
    </div>
  </div>
</section>

<!-- ============ TESTIMONIALS ============ -->
<!-- ============ TESTIMONIALS ============ -->
<?php
// Fallback testimonials if none found in database
$testimonials_list = !empty($testimonial) ? $testimonial : array(
  (object) array('name' => 'Amit Sharma', 'company_name' => 'Homeowner', 'description' => 'I absolutely love the hourly rate model! The cleaner arrived on time, finished all the work in 2 hours, and I only paid for those exact 2 hours.', 'logo' => ''),
  (object) array('name' => 'Priya Kapoor', 'company_name' => 'Working Professional', 'description' => 'Hired a plumber for some quick fixes. Since they charge by the hour, I bundled all my small repairs together. Saved me so much money.', 'logo' => ''),
  (object) array('name' => 'Rahul Desai', 'company_name' => 'Verified Customer', 'description' => 'The app is seamless. Booking a professional electrician took just a few taps. The hourly billing is the best feature.', 'logo' => ''),
  (object) array('name' => 'Sneha Mishra', 'company_name' => 'Regular User', 'description' => 'I book the deep cleaning bundle every month. The pros are polite, verify their hours, and the hourly tracking gives me complete peace of mind.', 'logo' => ''),
);
?>
<section class="testimonials-sec">
  <div class="container">
    <div class="text-center">
      <div class="hourly-badge"><i class="ri-time-line"></i> Hourly Basis Services</div>
      <h2 class="section-title">What Our Customers Say</h2>
      <p class="section-subtitle">Pay only for the hours you need. See why thousands love our transparent and flexible service.</p>
    </div>

    <div class="testimonials-slider mt-5">
      <?php foreach($testimonials_list as $v):
        $v_name = is_object($v) ? $v->name : $v['name'];
        $v_company = is_object($v) ? $v->company_name : $v['company_name'];
        $v_desc = is_object($v) ? $v->description : $v['description'];
        $v_logo = is_object($v) ? ($v->logo ?? '') : ($v['logo'] ?? '');
        $logo_url = !empty($v_logo) ? UPLOAD_HTTP_PATH.'testimonial-icon/'.$v_logo : '';
        
        // Generate initials
        $words = explode(' ', trim($v_name));
        $initials = '';
        foreach(array_slice($words, 0, 2) as $w){ $initials .= strtoupper(substr($w,0,1)); }
      ?>
      <div class="testimonial-slide">
        <div class="testimonial-card">
          <div class="quote-icon">"</div>
          <div class="client-info">
            <div class="avatar">
              <?php if(!empty($logo_url)): ?>
                <img src="<?php echo $logo_url; ?>" alt="<?php echo htmlspecialchars($v_name); ?>" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                <span style="display:none;"><?php echo $initials; ?></span>
              <?php else: ?>
                <span><?php echo $initials; ?></span>
              <?php endif; ?>
            </div>
            <div>
              <h4 class="client-name"><?php echo htmlspecialchars($v_name); ?></h4>
              <span class="client-role"><?php echo htmlspecialchars($v_company); ?></span>
            </div>
          </div>
          <p class="testimonial-text">"<?php echo htmlspecialchars($v_desc); ?>"</p>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ============ PARTNERS ============ -->
<?php
// Fallback partners if none found in database
$partners_list = !empty($partner) ? $partner : array(
  (object) array('name' => 'UrbanNest', 'box_image' => ''),
  (object) array('name' => 'CleanPro', 'box_image' => ''),
  (object) array('name' => 'FixItRight', 'box_image' => ''),
  (object) array('name' => 'HomeServe+', 'box_image' => ''),
  (object) array('name' => 'BuildMart', 'box_image' => ''),
);
?>
<section class="partners-sec">
  <div class="container text-center">
    <p class="partners-label">Trusted by Industry Leaders &amp; Brands</p>
    <div class="partners-track">
      <?php foreach($partners_list as $p):
        $p_name = is_object($p) ? $p->name : $p['name'];
        $p_image = is_object($p) ? ($p->box_image ?? '') : ($p['box_image'] ?? '');
        $img_url = !empty($p_image) ? UPLOAD_HTTP_PATH.'box/'.$p_image : '';
      ?>
      <div class="partner-img-wrap">
        <?php if(!empty($img_url)): ?>
          <img src="<?php echo $img_url; ?>" alt="<?php echo htmlspecialchars($p_name); ?>" title="<?php echo htmlspecialchars($p_name); ?>" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
          <h3 class="partner-logo" style="display:none;"><?php echo htmlspecialchars($p_name); ?></h3>
        <?php else: ?>
          <h3 class="partner-logo"><?php echo htmlspecialchars($p_name); ?></h3>
        <?php endif; ?>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ============ FAQ ============ -->
<?php
// Guarantee 4 FAQs by merging DB articles with fallback defaults
$default_faqs = array(
  array(
    'title' => 'What is SnapHive and how does it work?',
    'description' => 'SnapHive connects you with trusted, verified independent service providers for hourly manpower services including ayas, nurses, drivers, cleaners, and more. Choose your service, book an hourly slot, and pay only for the exact hours worked.'
  ),
  array(
    'title' => 'What types of services can I book on SnapHive?',
    'description' => 'You can book trained nurses, ayas/nannies, personal drivers, sanitation & cleaning workers, cooks, electricians, plumbers, and home maintenance professionals on a flexible hourly basis.'
  ),
  array(
    'title' => 'How does hourly rate billing work?',
    'description' => 'Our billing is strictly based on the actual hours of service rendered. Providers track their time through the app, and you are charged only for the verified hours without any hidden fees.'
  ),
  array(
    'title' => 'Are all service providers verified and safe?',
    'description' => 'Yes, every professional on SnapHive undergoes background verification, identity check, and skill screening before being listed to ensure your family’s safety and peace of mind.'
  )
);

// Collect up to 4 items: start with DB articles, then fill up to 4 with defaults
$faq_display_list = array();
if (!empty($help) && is_array($help)) {
  foreach ($help as $h) {
    $t = is_array($h) ? ($h['title'] ?? '') : ($h->title ?? '');
    $d = is_array($h) ? ($h['description'] ?? '') : ($h->description ?? '');
    if (!empty($t) && !empty($d)) {
      $faq_display_list[] = array('title' => $t, 'description' => $d);
    }
    if (count($faq_display_list) >= 4) break;
  }
}

// Fill remaining slots up to 4 from defaults
$default_idx = 0;
while (count($faq_display_list) < 4 && isset($default_faqs[$default_idx])) {
  $faq_display_list[] = $default_faqs[$default_idx];
  $default_idx++;
}
?>
<section class="faq-sec">
  <div class="container">
    <div class="text-center">
      <h2 class="section-title">Frequently Asked Questions</h2>
      <p class="section-subtitle">Find answers to common questions about our manpower services, bookings, and payments.</p>
    </div>
    <div class="faq-grid">
      <?php foreach($faq_display_list as $faq): ?>
      <div class="faq-item" onclick="toggleFaq(this)">
        <div class="faq-question">
          <span class="faq-q-text"><?php echo htmlspecialchars($faq['title']); ?></span>
          <span class="faq-toggle">+</span>
        </div>
        <div class="faq-answer">
          <?php echo strip_tags($faq['description'], '<p><br><b><strong><ul><li><a>'); ?>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<script>
function toggleFaq(el) {
  var isActive = el.classList.contains('active');
  // Close all
  document.querySelectorAll('.faq-item.active').forEach(function(item){
    item.classList.remove('active');
  });
  // Open clicked if it was not active
  if (!isActive) {
    el.classList.add('active');
  }
}
</script>


</body>
</html>