<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Elementor Widget: Eventos Anteriores.
 */
class Past_Events_Widget extends \Elementor\Widget_Base
{

    public function get_name()
    {
        return 'past_events';
    }

    public function get_title()
    {
        return esc_html__('Eventos Anteriores', 'plugin-eventos-anteriores');
    }

    public function get_icon()
    {
        return 'eicon-calendar';
    }

    public function get_categories()
    {
        return ['general'];
    }

    public function get_script_depends()
    {
        return [];
    }

    protected function register_controls()
    {
        $this->start_controls_section(
            'content_section',
            [
                'label' => esc_html__('Content', 'plugin-eventos-anteriores'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'posts_per_page',
            [
                'label' => esc_html__('Posts Per Page', 'plugin-eventos-anteriores'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 100,
                'step' => 1,
                'default' => 6,
            ]
        );

        $this->end_controls_section();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $posts_per_page = $settings['posts_per_page'];

        // Get current date
        $current_date = date('Y-m-d'); // Adjust format if your 'fecha_evento' is stored differently (e.g., 'Ymd')

        $args = array(
            'post_type' => 'evento',
            'posts_per_page' => $posts_per_page,
            'post_status' => 'publish',
            'meta_query' => array(
                array(
                    'key' => 'fecha_evento',
                    'value' => $current_date,
                    'compare' => '<',
                    'type' => 'DATE'
                ),
            ),
            'orderby' => 'meta_value',
            'meta_key' => 'fecha_evento',
            'order' => 'DESC', // Show most recent past events first
        );

        $query = new \WP_Query($args);

        // Fallback: If no meta key exists or distinct query logic is needed
        if (!$query->have_posts()) {
            // Note: If you want to show ALL events if no "past" logic works, uncomment below line
            // $query = new \WP_Query( array( 'post_type' => 'evento', 'posts_per_page' => $posts_per_page ) );
        }

        if ($query->have_posts()) {
            echo '<div class="pea-container">';
            echo '<style>
                .pea-container {
                    max-width: 1152px;
                    margin: 0 auto;
                    padding: 3rem 1rem;
                    font-family: "Montserrat", sans-serif;
                }
                .pea-grid {
                    display: grid;
                    grid-template-columns: repeat(1, minmax(0, 1fr));
                    gap: 2rem;
                    text-align: center;
                }
                @media (min-width: 768px) {
                    .pea-grid {
                        grid-template-columns: repeat(2, minmax(0, 1fr));
                    }
                }
                @media (min-width: 1024px) {
                    .pea-grid {
                        grid-template-columns: repeat(3, minmax(0, 1fr));
                    }
                }
                .pea-item {
                    display: flex;
                    flex-direction: column;
                    align-items: center;
                }
                .pea-image-wrapper {
                    width: 12rem;
                    height: 12rem;
                    border-radius: 9999px;
                    overflow: hidden;
                    margin-bottom: 1.5rem;
                    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
                }
                .pea-image {
                    width: 100%;
                    height: 100%;
                    object-fit: cover;
                }
                .pea-title {
                    font-size: 0.875rem;
                    letter-spacing: 0.1em;
                    text-transform: uppercase;
                    color: #1f2937;
                    font-weight: 500;
                    line-height: 1.625;
                    margin: 0;
                }
            </style>';

            echo '<div class="pea-grid">';

            while ($query->have_posts()) {
                $query->the_post();
                $image_url = get_the_post_thumbnail_url(get_the_ID(), 'full');
                if (!$image_url) {
                    $image_url = 'https://placehold.co/400x400/e2e8f0/1e293b?text=No+Image';
                }
                $title = get_the_title();

                echo '<div class="pea-item">';
                echo '<div class="pea-image-wrapper">';
                echo '<img src="' . esc_url($image_url) . '" alt="' . esc_attr($title) . '" class="pea-image">';
                echo '</div>';
                echo '<h3 class="pea-title">' . wp_kses_post($title) . '</h3>'; // Assuming title might have HTML like <br>
                echo '</div>';
            }

            echo '</div>';
            echo '</div>';
            wp_reset_postdata();
        } else {
            if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
                echo '<div class="pea-no-posts">Nc hay eventos anteriores para mostrar. (Post Type: "evento", Meta Key: "fecha_evento" < Today)</div>';
            }
        }
    }
}
