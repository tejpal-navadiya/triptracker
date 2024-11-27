<?php
// Enqueue parent theme styles
function elementor_child_enqueue_styles() {
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
    // Enqueue child theme style
    wp_enqueue_style( 'child-style', get_stylesheet_directory_uri() . '/style.css', array('parent-style'), wp_get_theme()->get('Version') );
}
add_action( 'wp_enqueue_scripts', 'elementor_child_enqueue_styles' );

function subscription_plan_shortcode() {
    // Step 1: Fetch Data from the API
    $response = wp_remote_get('https://tatriptracker.com/api/subscription_plans_list?page=1'); // Replace with actual API URL
    //echo $response;
    $plans = json_decode(wp_remote_retrieve_body($response), true);
    $plan = $plans['data'];
   
    // Check if API returned data
    if (empty($plans)) {
        return '<p>Unable to fetch subscription plans. Please try again later.</p>';
    }

    // Step 2: Generate HTML structure
    ob_start(); ?>
    <div id="pricing-plans">
        <!-- Toggle Button Styled as a Switch in Center -->
        <div class="toggle-switch-wrapper">
            <span>Monthly</span>
            <div class="toggle-switch">
                <input type="checkbox" id="price-toggle" onchange="togglePricing(this)">
                <label for="price-toggle" class="switch-label"></label>
            </div>
            <span>Yearly</span>
        </div>

        <!-- Plans Grid -->
        <div class="plans-grid">
            <?php foreach ($plan['data'] as $plan): ?>
                <div class="plan-item">
                    <div class="plan-header"><img src="https://triptracker.yuglogix.com/wp-content/uploads/2024/10/Group-36.png"><h5><?php echo esc_html($plan['sp_name']); ?></h5></div>
                    <div class="plan-price">
                        <p class="monthly-price" style="display: block;">
                            <?php echo esc_html($plan['sp_month_amount']); ?>/month
                        </p>
                        <p class="annual-price" style="display: none;">
                            <?php echo esc_html($plan['sp_year_amount']); ?>/year
                        </p>
                    </div>
                    <p class="plan-desc"><?php echo esc_html($plan['sp_desc']); ?></p>
                    <!-- Get Started Button with dynamic link -->
                    <a target="_blank" href="https://tatriptracker.com/company/register?plan_id=<?php echo esc_attr($plan['sp_id']); ?>&period=monthly" 
                       class="get-started-btn" 
                       data-plan-id="<?php echo esc_attr($plan['sp_id']); ?>"
                       data-period="monthly">
                        Get Started
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Step 3: Toggle Script -->
    <script>
        function togglePricing(toggle) {
            let period = toggle.checked ? 'yearly' : 'monthly';
            
            document.querySelectorAll('.monthly-price').forEach(el => el.style.display = period === 'monthly' ? 'block' : 'none');
            document.querySelectorAll('.annual-price').forEach(el => el.style.display = period === 'yearly' ? 'block' : 'none');
            
            document.querySelectorAll('.get-started-btn').forEach(button => {
                button.href = `https://tatriptracker.com/company/register?plan_id=${button.dataset.planId}&period=${period}`;
                button.dataset.period = period;
            });
        }
    </script>

    
    <?php return ob_get_clean();
}
add_shortcode('subscription_plans', 'subscription_plan_shortcode');
