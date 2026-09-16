<style>
.help-hero {
    background: linear-gradient(135deg, #f0fdfa 0%, #e0f2fe 100%);
    padding: 6rem 0 8rem 0;
    text-align: center;
    position: relative;
    overflow: hidden;
    margin-bottom: -5rem;
    border-radius: 0 0 40px 40px;
}
.help-hero::before {
    content: '';
    position: absolute;
    width: 300px;
    height: 300px;
    background: radial-gradient(circle, rgba(43,170,177,0.15) 0%, rgba(255,255,255,0) 70%);
    top: -100px;
    left: -50px;
    border-radius: 50%;
}
.help-title {
    font-size: 3rem;
    font-weight: 800;
    color: #1e293b;
    position: relative;
    z-index: 1;
    margin-bottom: 1rem;
}
.help-title span {
    background: linear-gradient(135deg, #1e293b 0%, #2BAAB1 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}
.help-subtitle {
    color: #64748b;
    font-size: 1.15rem;
    max-width: 600px;
    margin: 0 auto;
    position: relative;
    z-index: 1;
    line-height: 1.6;
}

.help-content-wrapper {
    background: #ffffff;
    border-radius: 24px;
    padding: 4rem;
    box-shadow: 0 20px 50px rgba(0,0,0,0.06);
    border: 1px solid rgba(43,170,177,0.08);
    position: relative;
    z-index: 2;
    margin-bottom: 4rem;
}

/* Premium Accordion Styling */
.premium-accordion .accordion-item {
    border: 1px solid rgba(43,170,177,0.1);
    border-radius: 16px !important;
    margin-bottom: 1.25rem;
    overflow: hidden;
    background: #f8fafc;
    transition: all 0.3s ease;
}
.premium-accordion .accordion-item:hover {
    border-color: rgba(43,170,177,0.3);
    box-shadow: 0 5px 15px rgba(43,170,177,0.05);
    transform: translateY(-2px);
}
.premium-accordion .accordion-button {
    background: transparent;
    font-weight: 600;
    font-size: 1.15rem;
    color: #1e293b;
    padding: 1.5rem;
    box-shadow: none !important;
}
.premium-accordion .accordion-button:not(.collapsed) {
    color: #2BAAB1;
    background: #ffffff;
    border-bottom: 1px solid rgba(43,170,177,0.1);
}
.premium-accordion .accordion-body {
    padding: 1.5rem;
    color: #64748b;
    line-height: 1.8;
    background: #ffffff;
    font-size: 1.05rem;
}
@media (max-width: 768px) {
    .help-content-wrapper {
        padding: 2rem;
    }
}
</style>

<div class="help-hero">
  <div class="container">
    <h1 class="help-title">Frequently Asked <span>Questions</span></h1>
    <p class="help-subtitle">Need help? We've got you covered. Find answers to our most common questions below.</p>
  </div>
</div>

<section class="section mb-5">
  <div class="container">
    <div class="help-content-wrapper">
        <div class="accordion accordion-flush premium-accordion" id="accordionFlushExample">

          <?php foreach ($help as $k => $v) {
            $collapseId = 'flush-collapse-' . $k;
          ?>
            <div class="accordion-item">
              <h5 class="accordion-header" id="heading-<?php echo $k; ?>">
                <button
                  class="accordion-button collapsed"
                  type="button"
                  data-bs-toggle="collapse"
                  data-bs-target="#<?php echo $collapseId; ?>"
                  aria-expanded="false"
                  aria-controls="<?php echo $collapseId; ?>">
                  <?php echo $v['title']; ?>
                </button>
              </h5>

              <div
                id="<?php echo $collapseId; ?>"
                class="accordion-collapse collapse"
                data-bs-parent="#accordionFlushExample">
                <div class="accordion-body">
                  <?php echo nl2br($v['description']); ?>
                </div>
              </div>

            </div>
          <?php } ?>

        </div>
    </div>
  </div>
</section>