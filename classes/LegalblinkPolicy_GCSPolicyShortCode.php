<?php

include_once(WP_PLUGIN_DIR.'/legalblink-policy/LegalblinkPolicy_ShortCodeLoader.php');

class LegalblinkPolicy_GCSPolicyShortCode extends LegalblinkPolicy_ShortCodeLoader
{
    /**
     * @param  $atts shortcode inputs
     *
     * @return string shortcode content
     */
    public function handleShortcode($atts)
    {
        $lbp_is_gcs_policy_enabled = (int)LBPSettingsPageUtility::getOption('LBPSettingsPage',
            'lbp_tab_gcs_policy_section_primary', null, 'lbp_is_gcs_policy_enabled');

        if (empty($lbp_is_gcs_policy_enabled)) {
            return __('Feature not enabled', 'legalblink-policy');
        } else {
            $lbp_gcs_policy_url = LBPSettingsPageUtility::getOption('LBPSettingsPage',
                'lbp_tab_gcs_policy_section_primary', null, 'lbp_gcs_policy_url');

            if (empty($lbp_gcs_policy_url)) {
                return __('Feature not configured', 'legalblink-policy');
            } else {
                return LBPPolicyUtility::getContentCached($lbp_gcs_policy_url);
            }
        }
    }
}
