<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//dd($filter);
/* echo '<pre>';
print_r($cms_temp);
echo '</pre>'; */
?>
<?php if (isset($cms->content_slug) && $cms->content_slug == 'about-us'): ?>

<style>
.about-hero {
    background: linear-gradient(135deg, #f0fdfa 0%, #e0f2fe 100%);
    padding: 6rem 0;
    text-align: center;
    position: relative;
    overflow: hidden;
}
.about-hero::before {
    content: '';
    position: absolute;
    width: 400px;
    height: 400px;
    background: radial-gradient(circle, rgba(43,170,177,0.1) 0%, rgba(255,255,255,0) 70%);
    top: -100px;
    right: -50px;
    border-radius: 50%;
}
.about-hero::after {
    content: '';
    position: absolute;
    width: 300px;
    height: 300px;
    background: radial-gradient(circle, rgba(43,170,177,0.08) 0%, rgba(255,255,255,0) 70%);
    bottom: -100px;
    left: -50px;
    border-radius: 50%;
}
.about-title {
    font-size: 3.5rem;
    font-weight: 800;
    color: #1e293b;
    margin-bottom: 1.5rem;
    position: relative;
    z-index: 1;
}
.about-title span {
    background: linear-gradient(135deg, #1e293b 0%, #2BAAB1 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}
.about-subtitle {
    font-size: 1.25rem;
    color: #64748b;
    max-width: 750px;
    margin: 0 auto 2.5rem auto;
    line-height: 1.7;
    position: relative;
    z-index: 1;
}
.about-stats {
    display: flex;
    justify-content: center;
    gap: 3rem;
    margin-top: 3rem;
    position: relative;
    z-index: 1;
}
.stat-item {
    background: white;
    padding: 2rem 3rem;
    border-radius: 24px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.04);
    border: 1px solid rgba(43,170,177,0.08);
    transition: transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275), box-shadow 0.4s ease;
}
.stat-item:hover {
    transform: translateY(-8px);
    box-shadow: 0 20px 40px rgba(43,170,177,0.12);
    border-color: rgba(43,170,177,0.3);
}
.stat-num {
    font-size: 2.75rem;
    font-weight: 800;
    color: #2BAAB1;
    margin-bottom: 0.25rem;
    background: linear-gradient(135deg, #2BAAB1 0%, #1e293b 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}
.stat-label {
    color: #64748b;
    font-weight: 600;
    font-size: 1.1rem;
}
.about-content-section {
    padding: 6rem 0;
    background: #ffffff;
}
.about-card {
    background: #f8fafc;
    border-radius: 24px;
    padding: 3rem;
    border: 1px solid rgba(43,170,177,0.05);
    height: 100%;
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    position: relative;
    overflow: hidden;
}
.about-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, rgba(43, 170, 177, 0.03) 0%, rgba(43, 170, 177, 0) 100%);
    z-index: 0;
    opacity: 0;
    transition: opacity 0.4s ease;
}
.about-card:hover {
    background: #ffffff;
    box-shadow: 0 24px 48px rgba(43,170,177,0.1);
    border-color: rgba(43,170,177,0.3);
    transform: translateY(-8px);
}
.about-card:hover::before {
    opacity: 1;
}
.about-icon {
    width: 72px;
    height: 72px;
    background: rgba(43,170,177,0.1);
    color: #2BAAB1;
    border-radius: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    margin-bottom: 2rem;
    position: relative;
    z-index: 1;
    transition: all 0.4s ease;
    transform: rotate(-3deg);
}
.about-card:hover .about-icon {
    transform: scale(1.1) rotate(0deg);
    background: #2BAAB1;
    color: white;
    box-shadow: 0 10px 20px rgba(43, 170, 177, 0.2);
}
.about-card-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 1rem;
    position: relative;
    z-index: 1;
}
.about-card-text {
    color: #64748b;
    line-height: 1.7;
    position: relative;
    z-index: 1;
    font-size: 1.05rem;
}

@media (max-width: 768px) {
    .about-stats {
        flex-direction: column;
        gap: 1.5rem;
    }
}
</style>

<section class="about-hero">
    <div class="container">
        <h1 class="about-title">About <span>Us</span></h1>
        <p class="about-subtitle">We are on a mission to transform how you take care of your home. By connecting you with highly vetted, top-tier professionals, we make home maintenance effortless, reliable, and entirely secure.</p>
        
        <div class="about-stats flex-wrap">
            <div class="stat-item">
                <div class="stat-num">50K+</div>
                <div class="stat-label">Happy Homes</div>
            </div>
            <div class="stat-item">
                <div class="stat-num">5K+</div>
                <div class="stat-label">Verified Pros</div>
            </div>
            <div class="stat-item">
                <div class="stat-num">4.8</div>
                <div class="stat-label">Average Rating</div>
            </div>
        </div>
    </div>
</section>

<section class="about-content-section">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-4 col-md-6">
                <div class="about-card">
                    <div class="about-icon"><i class="ri-shield-check-line"></i></div>
                    <h3 class="about-card-title">Our Promise</h3>
                    <p class="about-card-text">We promise to deliver exceptional quality and total peace of mind. Every single professional on our platform undergoes rigorous background checks and continuous quality assessments.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="about-card">
                    <div class="about-icon"><i class="ri-rocket-line"></i></div>
                    <h3 class="about-card-title">Our Vision</h3>
                    <p class="about-card-text">To become the world's most trusted partner for home services, enabling families to spend less time on tedious chores and more time doing what they truly love.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="about-card">
                    <div class="about-icon"><i class="ri-heart-3-line"></i></div>
                    <h3 class="about-card-title">Core Values</h3>
                    <p class="about-card-text">Trust, reliability, and excellence. We treat your home with the exact same care, dedication, and respect that we treat our own, ensuring a spotless experience every time.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<?php elseif (isset($cms->content_slug) && $cms->content_slug == 'how-it-works'): ?>

<style>
.hiw-hero {
    background: linear-gradient(135deg, #f0fdfa 0%, #e0f2fe 100%);
    padding: 6rem 0;
    text-align: center;
    position: relative;
    overflow: hidden;
    margin-bottom: 4rem;
    border-radius: 0 0 40px 40px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.02);
}
.hiw-hero::before {
    content: '';
    position: absolute;
    width: 300px;
    height: 300px;
    background: radial-gradient(circle, rgba(43,170,177,0.15) 0%, rgba(255,255,255,0) 70%);
    top: -100px;
    left: -50px;
    border-radius: 50%;
}
.hiw-title {
    font-size: 3rem;
    font-weight: 800;
    color: #1e293b;
    margin-bottom: 1rem;
    position: relative;
    z-index: 1;
}
.hiw-title span {
    background: linear-gradient(135deg, #1e293b 0%, #2BAAB1 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}
.hiw-subtitle {
    color: #64748b;
    font-size: 1.15rem;
    max-width: 600px;
    margin: 0 auto;
    position: relative;
    z-index: 1;
    line-height: 1.6;
}

.step-card {
    background: #ffffff;
    border-radius: 24px;
    padding: 3rem 2.5rem;
    box-shadow: 0 10px 30px rgba(0,0,0,0.03);
    border: 1px solid rgba(43,170,177,0.08);
    position: relative;
    transition: all 0.4s ease;
    height: 100%;
    z-index: 2;
    overflow: hidden;
}
.step-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 20px 40px rgba(43,170,177,0.12);
    border-color: rgba(43,170,177,0.2);
}
.step-number {
    position: absolute;
    top: -20px;
    right: -20px;
    font-size: 8rem;
    font-weight: 900;
    color: rgba(43, 170, 177, 0.04);
    line-height: 1;
    z-index: 0;
    transition: color 0.4s ease;
}
.step-card:hover .step-number {
    color: rgba(43, 170, 177, 0.08);
}
.step-icon {
    width: 80px;
    height: 80px;
    background: rgba(43,170,177,0.1);
    color: #2BAAB1;
    border-radius: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2.5rem;
    margin-bottom: 2rem;
    position: relative;
    z-index: 1;
    transition: all 0.4s ease;
}
.step-card:hover .step-icon {
    transform: scale(1.1) rotate(5deg);
    background: #2BAAB1;
    color: white;
    box-shadow: 0 10px 20px rgba(43, 170, 177, 0.2);
}
.step-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 1rem;
    position: relative;
    z-index: 1;
}
.step-text {
    color: #64748b;
    line-height: 1.7;
    position: relative;
    z-index: 1;
    font-size: 1.05rem;
}

@media (min-width: 992px) {
    .step-wrapper {
        position: relative;
    }
}
</style>

<div class="hiw-hero">
    <div class="container">
        <h1 class="hiw-title">How It <span>Works</span></h1>
        <p class="hiw-subtitle">Getting your home chores done has never been easier. Follow these simple steps to book a trusted professional in minutes.</p>
    </div>
</div>

<section class="section mb-5">
    <div class="container">
        <div class="row g-4">
            
            <div class="col-lg-3 col-md-6 step-wrapper">
                <div class="step-card">
                    <div class="step-number">1</div>
                    <div class="step-icon"><i class="ri-search-eye-line"></i></div>
                    <h3 class="step-title">Choose Service</h3>
                    <p class="step-text">Select from our wide range of home services. Tell us exactly what you need and pick a convenient time.</p>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 step-wrapper">
                <div class="step-card">
                    <div class="step-number">2</div>
                    <div class="step-icon"><i class="ri-user-star-line"></i></div>
                    <h3 class="step-title">We Match You</h3>
                    <p class="step-text">We instantly connect you with a highly-rated, background-checked professional in your local area.</p>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 step-wrapper">
                <div class="step-card">
                    <div class="step-number">3</div>
                    <div class="step-icon"><i class="ri-tools-line"></i></div>
                    <h3 class="step-title">Job Gets Done</h3>
                    <p class="step-text">Your pro arrives fully equipped and ready to work. They'll complete the job to the highest standards.</p>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 step-wrapper">
                <div class="step-card">
                    <div class="step-number">4</div>
                    <div class="step-icon"><i class="ri-cup-line"></i></div>
                    <h3 class="step-title">Relax & Enjoy</h3>
                    <p class="step-text">Pay securely online only when the job is done. Sit back, relax, and enjoy your perfectly sorted home.</p>
                </div>
            </div>

        </div>
    </div>
</section>

<?php else: ?>

<style>
.generic-hero {
    background: linear-gradient(135deg, #f0fdfa 0%, #e0f2fe 100%);
    padding: 6rem 0 8rem 0;
    text-align: center;
    position: relative;
    overflow: hidden;
    margin-bottom: -5rem;
    border-radius: 0 0 40px 40px;
}
.generic-hero::before {
    content: '';
    position: absolute;
    width: 300px;
    height: 300px;
    background: radial-gradient(circle, rgba(43,170,177,0.15) 0%, rgba(255,255,255,0) 70%);
    top: -100px;
    left: -50px;
    border-radius: 50%;
}
.generic-title {
    font-size: 3rem;
    font-weight: 800;
    color: #1e293b;
    position: relative;
    z-index: 1;
}
.generic-title span {
    background: linear-gradient(135deg, #1e293b 0%, #2BAAB1 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}
.generic-content-wrapper {
    background: #ffffff;
    border-radius: 24px;
    padding: 4rem;
    box-shadow: 0 20px 50px rgba(0,0,0,0.06);
    border: 1px solid rgba(43,170,177,0.08);
    position: relative;
    z-index: 2;
    margin-bottom: 4rem;
}
.cms-page h1, .cms-page h2, .cms-page h3, .cms-page h4 {
    color: #1e293b;
    font-weight: 700;
    margin-top: 2rem;
    margin-bottom: 1rem;
}
.cms-page p, .cms-page li {
    color: #475569;
    line-height: 1.8;
    font-size: 1.05rem;
    margin-bottom: 1rem;
}
.cms-page a {
    color: #2BAAB1;
    text-decoration: none;
    font-weight: 600;
}
.cms-page a:hover {
    text-decoration: underline;
}
@media (max-width: 768px) {
    .generic-content-wrapper {
        padding: 2rem;
    }
}
</style>

<div class="generic-hero">
    <div class="container">
        <h1 class="generic-title"><span><?php D($cms->title); ?></span></h1>
    </div>
</div>

<section class="section mb-5">
    <div class="container">
        <div class="generic-content-wrapper">
            <div class="cms-page">
                <?php 
                if($cms_temp){
                    foreach($cms_temp as $k=>$block){
                        if($block->cms_class){
                            echo '<div class="'.$block->cms_class.'">';
                        }
                        $child_block=array();
                        if($block->child_class){
                            $child_block=explode(',',$block->child_class);
                        }
                        if($child_block){
                            foreach($child_block as $c=>$child){
                                echo '<div class="'.$child.'">';
                            }
                        }
                        if($block->part){
                            foreach($block->part as $p=>$part){
                                echo '<div class="'.$part->part_class.'">';
                                echo html_entity_decode($part->part_content);
                                echo '</div>';
                            }

                        }
                        if($child_block){
                            foreach($child_block as $c=>$child){
                                echo '</div>';	
                            }
                        }
                        if($block->cms_class){
                            echo '</div>';		
                        }
                    }
                }
                ?>
            </div>
        </div>
    </div>
</section>

<?php endif; ?>

<?php /*?>
<section class="section">
	<div class="container">
    	<?php D(html_entity_decode($cms->content)); ?>
    </div>
</section>
<?php */?>