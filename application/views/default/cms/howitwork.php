<style>
.hiw-hero {
    background: linear-gradient(135deg, #f0fdfa 0%, #e0f2fe 100%);
    padding: 6rem 0 8rem 0;
    text-align: center;
    position: relative;
    overflow: hidden;
    margin-bottom: -5rem;
    border-radius: 0 0 40px 40px;
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
    position: relative;
    z-index: 1;
    margin-bottom: 1rem;
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

.hiw-content-wrapper {
    background: #ffffff;
    border-radius: 24px;
    padding: 3.5rem 4rem;
    box-shadow: 0 20px 50px rgba(0,0,0,0.06);
    border: 1px solid rgba(43,170,177,0.08);
    position: relative;
    z-index: 2;
    margin-bottom: 4rem;
}

/* Premium Tabs */
.premium-tabs {
    border-bottom: none;
    gap: 1rem;
    position: relative;
    z-index: 3;
    margin-bottom: -25px; /* Overlap the wrapper slightly */
    justify-content: center;
}
.premium-tabs .nav-link {
    background: #f8fafc;
    border: 1px solid rgba(43,170,177,0.1) !important;
    color: #64748b;
    font-weight: 600;
    font-size: 1.1rem;
    padding: 1rem 2.5rem;
    border-radius: 50px;
    transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    box-shadow: 0 4px 10px rgba(0,0,0,0.02);
}
.premium-tabs .nav-link:hover {
    background: #e0f2fe;
    color: #2BAAB1;
}
.premium-tabs .nav-link.active {
    background: #2BAAB1 !important;
    color: #ffffff !important;
    border-color: #2BAAB1 !important;
    box-shadow: 0 10px 20px rgba(43, 170, 177, 0.2);
    transform: translateY(-4px);
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

@media (max-width: 768px) {
    .hiw-content-wrapper {
        padding: 2rem;
    }
    .premium-tabs .nav-link {
        padding: 0.75rem 1.5rem;
        font-size: 1rem;
    }
}
</style>

<div class="hiw-hero">
    <div class="container">
        <h1 class="hiw-title">How It <span>Works</span></h1>
        <p class="hiw-subtitle">Whether you're looking to book a service or offer your professional skills, getting started is incredibly simple.</p>
    </div>
</div>

<div class="container">
    <ul class="nav nav-tabs premium-tabs" id="myTab" role="tablist">  
      <?php if($how_it_works_freelancer){?>
      <li class="nav-item">
        <a class="nav-link active" id="profile-tab" data-bs-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false"><?php D($cms_freelancer->title); ?></a>
      </li> 
      <?php }?>
      <?php if($how_it_works_employer){?>
      <li class="nav-item">
        <a class="nav-link" id="home-tab" data-bs-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true"><?php D($cms_employer->title); ?></a>
      </li> 
      <?php }?>
    </ul>
</div>

<section class="section mb-5 mt-4">
    <div class="container">
        <div class="hiw-content-wrapper">
            <div class="tab-content cms-page" id="myTabContent">
                
                <?php if($how_it_works_freelancer){?>
                <!-- FREELANCER -->
                <div class="tab-pane fade show active" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                <?php
                    foreach($how_it_works_freelancer as $k=>$block){
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
                ?>
                </div>
                <?php }?>
                
                <?php if($how_it_works_employer){?>
                <!-- EMPLOYER -->
                <div class="tab-pane fade" id="home" role="tabpanel" aria-labelledby="home-tab">
                <?php
                    foreach($how_it_works_employer as $k=>$block){
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
                ?>
                </div>
                <?php }?>

            </div>
        </div>
    </div>
</section>