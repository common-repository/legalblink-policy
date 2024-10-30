<?php

include_once('LegalblinkPolicy_LifeCycle.php');
include_once('library/apf/admin-page-framework.php');
require_once('classes/LBPFrontPage.php');
require_once('classes/LBPSettingsPage.php');
include_once('classes/LBPSettingsPageUtility.php');
include_once('classes/LBPPolicyUtility.php');
include_once('classes/LegalblinkPolicy_CookiePolicyShortCode.php');
include_once('classes/LegalblinkPolicy_PrivacyPolicyShortCode.php');
include_once('classes/LegalblinkPolicy_GCSPolicyShortCode.php');

class LegalblinkPolicy_Plugin extends LegalblinkPolicy_LifeCycle
{

    /**
     * See: http://plugin.michael-simpson.com/?page_id=31
     * @return array of option meta data.
     */
    public function getOptionMetaData()
    {
        return array();
    }

    protected function getOptionValueI18nString($optionValue)
    {
        $i18nValue = parent::getOptionValueI18nString($optionValue);

        return $i18nValue;
    }

    protected function initOptions()
    {
        $options = $this->getOptionMetaData();
        if ( ! empty($options)) {
            foreach ($options as $key => $arr) {
                if (is_array($arr) && count($arr > 1)) {
                    $this->addOption($key, $arr[1]);
                }
            }
        }
    }

    public function getPluginDisplayName()
    {
        return 'LegalBlink Policy';
    }

    protected function getMainPluginFileName()
    {
        return 'legalblink-policy.php';
    }

    /**
     * See: http://plugin.michael-simpson.com/?page_id=101
     * Called by install() to create any database tables if needed.
     * Best Practice:
     * (1) Prefix all table names with $wpdb->prefix
     * (2) make table names lower case only
     * @return void
     */
    protected function installDatabaseTables()
    {
        //
    }

    /**
     * See: http://plugin.michael-simpson.com/?page_id=101
     * Drop plugin-created tables on uninstall.
     * @return void
     */
    protected function unInstallDatabaseTables()
    {
        //
    }


    /**
     * Perform actions when upgrading from version X to version Y
     * See: http://plugin.michael-simpson.com/?page_id=35
     * @return void
     */
    public function upgrade()
    {
    }

    public function addActionsAndFilters()
    {
        $_oRequirementCheck = new AdminPageFramework_Requirement(
            array(
                'php'       => array(
                    'version' => '5.2.4',
                    'error'   => 'The plugin requires the PHP version %1$s or higher.',
                ),
                'wordpress' => array(
                    'version' => '4.9.0',
                    'error'   => 'The plugin requires the WordPress version %1$s or higher.',
                ),
                'mysql'     => array(
                    'version' => '5.0',
                    'error'   => 'The plugin requires the MySQL version %1$s or higher.',
                ),
            ),
            'LegalBlink Policy'
        );

        if ($_oRequirementCheck->check()) {
            include_once(ABSPATH.'wp-admin/includes/plugin.php');
            if (is_plugin_active('legalblink-policy/legalblink-policy.php')) {
                $_oRequirementCheck->deactivatePlugin(
                    'legalblink-policy/legalblink-policy.php',   // the plugin main file path
                    __('Deactivating the plugin', 'legalblink-policy'),  // additional message
                    true    // is in the activation hook. This will exit the script.
                );
            }
        } else {
            add_action('wp_enqueue_scripts', array(&$this, 'enqueueStylesAndScripts'));
            add_action('admin_enqueue_scripts', array(&$this, 'enqueueAdminPageStylesAndScripts'));

            // Register short codes
            $cookie_policy_sc = new LegalblinkPolicy_CookiePolicyShortCode();
            $cookie_policy_sc->register(LBPSettingsPage::SHORTCODE_LBP_COOKIE_POLICY);

            $privacy_policy_sc = new LegalblinkPolicy_PrivacyPolicyShortCode();
            $privacy_policy_sc->register(LBPSettingsPage::SHORTCODE_LBP_PRIVACY_POLICY);

            $gcs_policy_sc = new LegalblinkPolicy_GCSPolicyShortCode();
            $gcs_policy_sc->register(LBPSettingsPage::SHORTCODE_LBP_GCS_POLICY);

            if(is_admin()){
                new LBPSettingsPage();
            }else{
                new LBPFrontPage();
            }
        }
    }

    public function enqueueAdminPageStylesAndScripts()
    {
        wp_enqueue_script('jquery');
        wp_enqueue_script('legalblink-policy-custom-admin-script',
            plugins_url('/js/legalblink-policy-custom-admin-script.js', __FILE__));

        wp_enqueue_style('legalblink-policy-custom-admin-style',
            plugins_url('/css/legalblink-custom-admin-style.css', __FILE__));
    }

    public function enqueueStylesAndScripts()
    {
        $page_id                                        = get_queried_object_id();
        $lbp_tab_banner_cookie_section_general_settings = AdminPageFramework::getOption('LBPSettingsPage',
            'lbp_tab_banner_cookie_section_general_settings');
        $lbp_tab_banner_cookie_section_style_settings   = AdminPageFramework::getOption('LBPSettingsPage',
            'lbp_tab_banner_cookie_section_style_settings');
        $lbp_cookie_policy_cms_page                     = (int)LBPSettingsPageUtility::getOption('LBPSettingsPage',
            'lbp_tab_cookie_policy_section_primary', null, 'lbp_cookie_policy_cms_page');

        wp_enqueue_script('jquery');
        wp_enqueue_script('jquery-effects-core');
        wp_enqueue_script('legalblink-policy-cookie-banner-script',
            plugins_url('/js/legalblink-policy-cookie-banner.js', __FILE__), array('jquery'));

        $alert_message      = $lbp_tab_banner_cookie_section_general_settings['lbp_banner_cookie_alert_message'];
        $cookie_policy_link = '<a href="'.get_permalink($lbp_cookie_policy_cms_page).'" target="_blank">'.get_the_title($lbp_cookie_policy_cms_page).'</a>';

        $lbp_tab_banner_cookie_section_general_settings['lbp_banner_cookie_alert_message'] = str_replace('{cookie_policy}',
            $cookie_policy_link, $alert_message);

        // Additional texts
        $lbp_tab_banner_cookie_section_general_settings['lbp_banner_cookie_alert_message_case_1'] =
            __('Accessing another area of ​​the site or clicking on any element of the page (image or link) outside the banner',
                'legalblink-policy');

        $lbp_tab_banner_cookie_section_general_settings['lbp_banner_cookie_alert_message_case_2'] =
            __('Performing a scrolling action of the page (c.d. scroll down)', 'legalblink-policy');

        $lbp_tab_banner_cookie_section_general_settings['lbp_banner_cookie_alert_message_case_3'] =
            sprintf( __( 'By clicking on the "%s" button in the banner itself', 'legalblink-policy' ), $lbp_tab_banner_cookie_section_general_settings['lbp_banner_cookie_alert_accept_button_caption'] );

        $lbp_tab_banner_cookie_section_general_settings['lbp_banner_cookie_alert_message_case_4'] =
            sprintf( __( 'By clicking on the "%s" button in the banner itself', 'legalblink-policy' ), $lbp_tab_banner_cookie_section_general_settings['lbp_banner_cookie_alert_close_button_caption'] );

        $lbp_tab_banner_cookie_section_general_settings['lbp_banner_cookie_alert_message_case_5'] =
            sprintf( __( 'By clicking on the "%s" button or "%s" button in the banner itself', 'legalblink-policy' ),
                $lbp_tab_banner_cookie_section_general_settings['lbp_banner_cookie_alert_accept_button_caption'],
                $lbp_tab_banner_cookie_section_general_settings['lbp_banner_cookie_alert_close_button_caption']
            );

        $lbp_tab_banner_cookie_section_general_settings['lbp_banner_cookie_alert_message_case_end'] =
            __('will consent to the use of cookies.', 'legalblink-policy');

        $consent_cookie_1 = (int)$lbp_tab_banner_cookie_section_general_settings['lbp_banner_cookie_accept_cookie_methods']['consent_cookie_1'];
        $consent_cookie_2 = (int)$lbp_tab_banner_cookie_section_general_settings['lbp_banner_cookie_accept_cookie_methods']['consent_cookie_2'];
        $consent_cookie_3 = (int)$lbp_tab_banner_cookie_section_general_settings['lbp_banner_cookie_accept_cookie_methods']['consent_cookie_3'];
        $consent_cookie_4 = (int)$lbp_tab_banner_cookie_section_general_settings['lbp_banner_cookie_accept_cookie_methods']['consent_cookie_4'];

        $lbp_banner_cookie_alert_message_extra = array();
        if ($consent_cookie_1 === 1 && $consent_cookie_4 === 0){
            $lbp_banner_cookie_alert_message_extra[] = strtolower($lbp_tab_banner_cookie_section_general_settings['lbp_banner_cookie_alert_message_case_3']);
        }
        if ($consent_cookie_1 === 0 && $consent_cookie_4 === 1){
            $lbp_banner_cookie_alert_message_extra[] = strtolower($lbp_tab_banner_cookie_section_general_settings['lbp_banner_cookie_alert_message_case_4']);
        }
        if ($consent_cookie_1 === 1 && $consent_cookie_4 === 1){
            $lbp_banner_cookie_alert_message_extra[] = strtolower($lbp_tab_banner_cookie_section_general_settings['lbp_banner_cookie_alert_message_case_5']);
        }
        if ($consent_cookie_2 === 1){
            $lbp_banner_cookie_alert_message_extra[] = strtolower($lbp_tab_banner_cookie_section_general_settings['lbp_banner_cookie_alert_message_case_2']);
        }
        if ($consent_cookie_3 === 1){
            $lbp_banner_cookie_alert_message_extra[] = strtolower($lbp_tab_banner_cookie_section_general_settings['lbp_banner_cookie_alert_message_case_1']);
        }

        if(isset($lbp_banner_cookie_alert_message_extra[0])){
            $lbp_banner_cookie_alert_message_extra[0] = ucfirst($lbp_banner_cookie_alert_message_extra[0]);
        }

        $lbp_tab_banner_cookie_section_general_settings['lbp_banner_cookie_alert_message_extra'] = implode(__(', or ', 'legalblink-policy'), $lbp_banner_cookie_alert_message_extra);
        $lbp_tab_banner_cookie_section_general_settings['lbp_banner_cookie_alert_message_extra'] .= ' '.$lbp_tab_banner_cookie_section_general_settings['lbp_banner_cookie_alert_message_case_end'];

        wp_localize_script('legalblink-policy-cookie-banner-script', 'lbp_cookie_banner_conf', array(
            'general'   => $lbp_tab_banner_cookie_section_general_settings,
            'style'     => $lbp_tab_banner_cookie_section_style_settings,
            'is_secure' => LBPPolicyUtility::isSecure(),
        ));

        wp_enqueue_style('legalblink-policy-cookie-banner-style',
            plugins_url('/css/legalblink-policy-cookie-banner.css', __FILE__));

        if (isset($lbp_tab_banner_cookie_section_general_settings['lbp_banner_cookie_position']) && (int)$lbp_tab_banner_cookie_section_general_settings['lbp_banner_cookie_position'] === 1) {
            wp_enqueue_style('legalblink-policy-cookie-banner-bottom-style',
                plugins_url('/css/legalblink-policy-cookie-banner-bottom.css', __FILE__));
        }

        // Cookie Policy table management
        if ($page_id === $lbp_cookie_policy_cms_page) {
            wp_enqueue_script('legalblink-policy-cookie-script',
                plugins_url('/js/legalblink-policy-cookie.js', __FILE__), array('jquery'));

            wp_localize_script('legalblink-policy-cookie-script', 'lbp_cookie_policy_conf', array(
                'ajax_url'              => admin_url('admin-ajax.php'),
                'lbp_is_user_logged_in' => is_user_logged_in(),
                'texts'                 => array(
                    'alert1'  => __('After making your choice on the consent form for first-party profiling cookies, to save your preferences click on "Save".',
                        'legalblink-policy'),
                    'save'    => __('Save', 'legalblink-policy'),
                    'enable'  => __('Enabled', 'legalblink-policy'),
                    'disable' => __('Disabled', 'legalblink-policy'),
                ),
            ));
        }

        // Banner Cookie
        wp_enqueue_script('overhang-script',
            plugins_url('/js/libs/overhang/overhang.min.js', __FILE__), array('jquery'));

        wp_enqueue_style('overhang-style',
            plugins_url('/js/libs/overhang/overhang.min.css', __FILE__));

        // Check Cookie Acceptance
        /*
        $lbp_tab_advanced_settings_section_advanced_settings = AdminPageFramework::getOption('LBPSettingsPage',
            'lbp_tab_advanced_settings_section_advanced_settings');
        // var_dump($lbp_tab_advanced_settings_section_advanced_settings);

        if(isset($_COOKIE['lbp_cookie_accepted']) && $_COOKIE['lbp_cookie_accepted'] === 'yes') {
           //
        }else{
            // Check auto block scripts
           if (isset($lbp_tab_advanced_settings_section_advanced_settings['lbp_advanced_settings_auto_block_scripts']) &&
               (int)$lbp_tab_advanced_settings_section_advanced_settings['lbp_advanced_settings_auto_block_scripts'] === 1){

           }
        }
        */
    }

}
