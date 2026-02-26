<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class The_Mind_Testimonial_Form {

    public static function init() {
        add_shortcode( 'tm_testimonial_form', [ __CLASS__, 'render_form' ] );
        add_action( 'init', [ __CLASS__, 'handle_submission' ] );
    }

    public static function render_form( $atts ) {
        // Check if submitted
        if ( isset( $_GET['tm_submitted'] ) && $_GET['tm_submitted'] === 'true' ) {
            return '<div class="tm-form-success">' . esc_html__( '¡Gracias por tu testimonio! Ha sido enviado para aprobación.', 'the-mind-testimonials' ) . '</div>';
        }

        ob_start();
        ?>
        <div class="tm-testimonial-form-wrapper">
            <form action="" method="post" class="tm-testimonial-submission-form">
                <?php wp_nonce_field( 'tm_submit_testimonial', 'tm_testimonial_nonce' ); ?>
                
                <div class="tm-form-group">
                    <label for="tm_name"><?php esc_html_e( 'Nombre', 'the-mind-testimonials' ); ?></label>
                    <input type="text" name="tm_name" id="tm_name" required>
                </div>
                
                <div class="tm-form-group">
                    <label for="tm_email"><?php esc_html_e( 'Email', 'the-mind-testimonials' ); ?></label>
                    <input type="email" name="tm_email" id="tm_email" required>
                </div>

                <div class="tm-form-group">
                    <label><?php esc_html_e( 'Calificación', 'the-mind-testimonials' ); ?></label>
                    <div class="tm-rating-inputs">
                        <?php for ( $i = 5; $i >= 1; $i-- ) : ?>
                            <input type="radio" name="tm_rating" id="star<?php echo $i; ?>" value="<?php echo $i; ?>" required>
                            <label for="star<?php echo $i; ?>"><i class="fas fa-star"></i></label>
                        <?php endfor; ?>
                    </div>
                </div>
                
                <div class="tm-form-group">
                    <label for="tm_content"><?php esc_html_e( 'Tu Testimonio', 'the-mind-testimonials' ); ?></label>
                    <textarea name="tm_content" id="tm_content" rows="4" required></textarea>
                </div>
                
                <button type="submit" name="tm_submit_testimonial" class="tm-submit-btn">
                    <?php esc_html_e( 'Enviar Testimonio', 'the-mind-testimonials' ); ?>
                </button>
            </form>
        </div>
        
        <style>
            .tm-testimonial-form-wrapper {
                background: #000;
                color: #fff;
                padding: 30px;
                border-radius: 10px;
                max-width: 500px;
                margin: 0 auto;
            }
            .tm-form-group {
                margin-bottom: 20px;
                text-align: left;
            }
            .tm-form-group label {
                display: block;
                margin-bottom: 5px;
                font-weight: bold;
            }
            .tm-form-group input[type="text"],
            .tm-form-group input[type="email"],
            .tm-form-group textarea {
                width: 100%;
                padding: 10px;
                border: 1px solid #333;
                background: #222;
                color: #fff;
                border-radius: 4px;
            }
            .tm-rating-inputs {
                display: flex;
                flex-direction: row-reverse;
                justify-content: flex-end;
                gap: 5px;
            }
            .tm-rating-inputs input {
                display: none;
            }
            .tm-rating-inputs label {
                cursor: pointer;
                font-size: 24px;
                color: #444;
            }
            .tm-rating-inputs input:checked ~ label,
            .tm-rating-inputs label:hover,
            .tm-rating-inputs label:hover ~ label {
                color: #ffcc00; /* Yellow stars */
            }
            
            .tm-submit-btn {
                background: #fff;
                color: #000;
                padding: 10px 20px;
                border: none;
                border-radius: 4px;
                cursor: pointer;
                font-weight: bold;
                transition: background 0.3s;
            }
            .tm-submit-btn:hover {
                background: #ccc;
            }
            .tm-form-success {
                padding: 20px;
                background: #d4edda;
                color: #155724;
                border: 1px solid #c3e6cb;
                border-radius: 5px;
                text-align: center;
            }
        </style>
        <?php
        return ob_get_clean();
    }

    public static function handle_submission() {
        if ( isset( $_POST['tm_submit_testimonial'] ) && check_admin_referer( 'tm_submit_testimonial', 'tm_testimonial_nonce' ) ) {
            
            $name = sanitize_text_field( $_POST['tm_name'] );
            $email = sanitize_email( $_POST['tm_email'] );
            $content = sanitize_textarea_field( $_POST['tm_content'] );
            $rating = intval( $_POST['tm_rating'] );
            
            // Insert as a Comment to the current post (or global 0 if strict "testimonials" logic)
            // But comments need a post_id. 
            // We can create a dedicated 'testimonials' page or post type if needed.
            // For simplicity, attach to the post where the form is.
            $post_id = get_the_ID();
            
            // Or better, create a 'testimonial' custom post type? No, user wanted "admin approve". 
            // Standard comments flow is easiest for approval.
            
            $comment_data = [
                'comment_post_ID' => $post_id,
                'comment_author' => $name,
                'comment_author_email' => $email,
                'comment_content' => $content,
                'comment_type' => 'review', // Mark as review if possible
                'comment_meta' => [
                    'rating' => $rating,
                ],
                'comment_approved' => 0, // Pending approval
            ];

            $comment_id = wp_insert_comment( $comment_data );
            
            if ( $comment_id ) {
                add_comment_meta( $comment_id, 'rating', $rating );
                
                // Redirect to avoid resubmission
                $redirect_url = add_query_arg( 'tm_submitted', 'true', wp_get_referer() );
                wp_redirect( $redirect_url );
                exit;
            }
        }
    }
}

The_Mind_Testimonial_Form::init();
