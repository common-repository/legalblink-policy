<?php

class LBPSettingsPageUtility
{
    public static function getOption($sOptionKey, $asKey = null, $vDefault = null, $sOptionSingleKey = null)
    {
        $data = AdminPageFramework::getOption($sOptionKey, $asKey, $vDefault);
        if ( ! empty($data)) {
            if (isset($data[$sOptionSingleKey])) {
                return $data[$sOptionSingleKey];
            }
        }

        return false;
    }

    /**
     * Determines if a post, identified by the specified ID, exist
     * within the WordPress database.
     *
     * @param int $id The ID of the post to check
     *
     * @return   bool          True if the post exists; otherwise, false.
     * @since    1.0.0
     */
    public static function post_exists($id)
    {
        return is_string(get_post_status($id));
    }

    public static function get_pages_array()
    {
        $pages = array();
        $page_ids = get_all_page_ids();
        foreach($page_ids as $id)
        {
            $pages[$id] = get_the_title($id);
        }
        return $pages;
    }
}