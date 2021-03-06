<?php
use Com\Hunchfree\Wp\Themes\Hffoundation as Hffoundation;

/*
	Template Name: Markets - Solutions - Products
*/

get_header();

# Take care of drawing out everything between the header and the footer.
# - In case of an exception, draw it out.
# - If there are warnings and the user is a logged in admin, draw them
try {

    $renderer = Hffoundation\Theme_Front_End::get_instance();
    if ( !is_object( $renderer ) ) {
        $this->_notices[] = "Failed to get renderer instance";
        throw new \Exception("Failed to get renderer instance", 10001);
    }

    if ( !have_posts() ) {
        $this->_notices[] = "Attempting to draw singular without any posts!";
        throw new \Exception("Non 404, singular, without any posts!", 10001);

    } else {
        while ( have_posts() ) {
            # load the current post into the global space for easy access
            the_post();

            # grab access to the currently loaded post
            global $post;

            # get the post id, css classes for it, and post type
            $post_id = get_the_ID();
            $css_classes = implode(' ', get_post_class());
            $post_type = get_post_type( $post );
            $title = get_the_title();

            # We are drawing the entire post.

            ###
            ### Header Block
            ###
            if( have_rows('header_block') ){

                while( have_rows('header_block') ){
                    the_row();

                    $header_title = get_sub_field('header_title');
                    $header_body_text = get_sub_field('header_body_text');
                    $background_image = get_sub_field('background_image');

                    if( !empty($background_image) ) {

                        $header_img_url = $background_image['url'];
                        $header_img_alt = $background_image['alt'];

                    }

                }

            }

            $header_block = <<<HTML
			
			 <div class="grid-container">
      
                  <div class="grid-x grid-margin-x header careers-bg align-middle align-center text-center" style="background: url(' {$header_img_url} '); background-size: cover;">
                  
                    <div class="large-8 medium-10 small-12 cell content white-text">
            
                      <h1>{$header_title}</h1>
            
                      <p>{$header_body_text}</p>
            
                    </div>
                  
                  </div>
                
            </div>

HTML;

            ###
            ### Content Sections Block
            ###
            if( have_rows('content_sections') ) {

                while (have_rows('content_sections')) {
                    the_row();

                    if (have_rows('content')) {
                        $content = '';

                        while (have_rows('content')) {
                            the_row();

                            $section_title = get_sub_field('section_title');
                            $button_text = get_sub_field('button_text');
                            $button_link = get_sub_field('button_link');
                            $background_image = get_sub_field('background_image');

                            if (!empty($background_image)) {

                                $section_background_img = $background_image['url'];
                                $section_background_alt = $background_image['alt'];

                            }

                            $content .= <<<HTML
                            
                            <div class="cell">

                              <a href="{$button_link}">
                    
                                <div class="card text-center" style="background: url('{$section_background_img}'); background-size: cover;">
                    
                                  <div class="card-content">
                    
                                    <h2 class="card-title">{$section_title}</h2>
                    
                                    <p class="card-description button">{$button_text}</p>
                    
                                  </div>
                    
                                </div>
                    
                              </a>
                    
                            </div>
                    
HTML;
                        }

                    }

                }

            }

            $sections_block = <<<HTML

            <section class="white-bg normal-pad">

                <div class="grid-container">

                    <div class="grid-x grid-margin-x text-center">

                        <div class="cell small-12">

                            <h2>Featured {$title}</h2>

                        </div>

                    </div>

                </div>
            
                <div class="grid-container box-pad">

                    <div class="grid-x grid-margin-x small-up-1 medium-up-2 large-up-3 align-center">
                
                        {$content}
      
                    </div>
      
                </div>

            </section>

HTML;


            ###
            ### Footer Block
            ###
            if( have_rows('footer_block') ){

                while( have_rows('footer_block') ){
                    the_row();

                    $footer_title = get_sub_field('footer_title');
                    $footer_content = get_sub_field('footer_content');
                    $background_image = get_sub_field('background_image');

                    if( !empty($background_image) ) {

                        $footer_image_url = $background_image['url'];
                        $footer_image_alt = $background_image['alt'];

                    }

                }

            }

            $footer_block = <<<HTML
            
            <section class="">

              <div class="grid-container">
        
                <div class="grid-x grid-margin-x align-center pipes-bg text-center align-middle foot-callout" style="background: url(' {$footer_image_url} '); background-size: cover;">
        
                  <div class="cell large-8 medium-10 small-12 white-text content">
        
                    <h2>{$footer_title}</h2>
        
                    <p>{$footer_content}</p>

                    <a href="/contact/" class="button white">Contact Us</a>
        
                  </div>
        
                </div>
            
              </div>
        
            </section>
                    
HTML;



            /**
             * The following does all of the actual drawing
             */
            echo <<<HTML
				{$header_block}
				{$sections_block}
                {$footer_block}

HTML;


        }
    }
} catch ( \Exception $e ) {
    if ( WP_DEBUG || ( is_user_logged_in() && current_user_can('activate-plugins') ) ) {
        echo "<p>Exception Encountered:</p><pre>" . print_r($e, true) . "</pre>";
        if ( isset( $o_renderer ) && is_object( $o_renderer ) ) {
            $notices = $o_renderer->get_warnings();
            if ( 0 < count($notices) ) {
                echo '<div><h4>Notices:</h4><pre>' . print_r($notices, true) . '</pre></div>';
            }
        }
    }
}

get_footer();
