<?php

// Extend the class
class LBPSettingsPage extends AdminPageFramework {
	/**
	 * The set-up method which is triggered automatically with the 'wp_loaded' hook.
	 *
	 * Here we define the setup() method to set how many pages, page titles and icons etc.
	 */

	public $lbp_plugin_purpose = array();
	public $cms_pages = array();

	const SHORTCODE_LBP_COOKIE_POLICY = 'LBP_COOKIE_POLICY';
	const SHORTCODE_LBP_PRIVACY_POLICY = 'LBP_PRIVACY_POLICY';
	const SHORTCODE_LBP_GCS_POLICY = 'LBP_GCS_POLICY';

	public function setUp() {
		$this->lbp_plugin_purpose = array(
			0 => __( 'Not set', 'legalblink-policy' ),
			1 => __( 'Strictly necessary', 'legalblink-policy' ),
			2 => __( 'Basic interactions & features', 'legalblink-policy' ),
			3 => __( 'Experience anhancement', 'legalblink-policy' ),
			4 => __( 'Targeting & Advertising', 'legalblink-policy' ),
		);

		$this->cms_pages = LBPSettingsPageUtility::get_pages_array();

		// Create a top-level menu.
		$this->setRootMenuPage(
			__( 'LegalBlink', 'legalblink-policy' ),
			LBP_ASSETS_URL . '/img/logo_c_menu_16.png'
		);

		// Add the sub menus and the pages.
		// The third parameter accepts screen icon url that appears at the top of the page.
		$this->addSubMenuItems(
			array(
				'title'     => __( 'Banner Cookie', 'legalblink-policy' ),
				'page_slug' => 'lbp_tab_banner_cookie',
			),
			array(
				'title'     => __( 'Cookie Policy', 'legalblink-policy' ),
				'page_slug' => 'lbp_tab_cookie_policy',
			),
			array(
				'title'     => __( 'Privacy Policy', 'legalblink-policy' ),
				'page_slug' => 'lbp_tab_privacy_policy',
			),
			array(
				'title'     => __( 'GCS Policy', 'legalblink-policy' ),
				'page_slug' => 'lbp_tab_gcs_policy',
			),
			array(
				'title'     => __( 'Advanced Settings', 'legalblink-policy' ),
				'page_slug' => 'lbp_tab_advanced_settings',
			),
			array(
				'title'     => __( 'Cache', 'legalblink-policy' ),
				'page_slug' => 'lbp_tab_cache_settings',
			),
			array(
				'title'                 => __( 'FAQ', 'legalblink-policy' ),
				'href'                  => 'https://legalblink.it/faq/',
				'show_page_heading_tab' => false,
			)
		);
		/*
		// Add sub menu items.
		$this->addSubMenuItems(
			array(
				'title'     => __( 'Settings' ),
				'page_slug' => 'lbp_settings_page',
			)
		);
		*/
	}

	// BANNER COOKIE TAB
	public function load_lbp_tab_banner_cookie() {
		$this->addSettingSections(
			array(
				'section_id' => 'lbp_tab_banner_cookie_section_general_settings',
				'title'      => __( 'General Settings', 'legalblink-policy' ),
			)
		);

		$this->addSettingFields(
			'lbp_tab_banner_cookie_section_general_settings',
			/*
			array(
				'field_id'      => 'lbp_tab_banner_cookie_section_1',
				'type'          => 'lbp_section_title',
				'title'         => '<strong><u>'
								   . __( 'General Settings', 'legalblink-policy' )
								   . '</u></strong>',
			),
			*/
			array(
				'field_id' => 'lbp_is_banner_cookie_enabled',
				'title'    => __( 'Show the banner', 'legalblink-policy' ),
				'type'     => 'checkbox',
				'label'    => __( 'It shows the cookie banner in all site pages.', 'legalblink-policy' ),
				'default'  => false,
			),
			array(
				'field_id'    => 'lbp_banner_cookie_position',
				'title'       => __( 'Position', 'legalblink-policy' ),
				'type'        => 'select',
				'description' => __( 'Choose where to display the banner.', 'legalblink-policy' ),
				'default'     => 0,
				'label'       => array(
					0 => __( 'Top', 'legalblink-policy' ),
					1 => __( 'Bottom', 'legalblink-policy' ),
				),
			),
			array(
				'field_id'    => 'lbp_banner_cookie_alert_message',
				'title'       => __( 'Alert message', 'legalblink-policy' ),
				'description' => __( 'Enter here the message to be shown to your customers. Use the reserved string {cookie_policy} to create an automatic link to the cookie page.',
					'legalblink-policy' ),
				'default'     => __( 'This site uses third-party cookies to profile users, these cookies allow the correct use of our services. To learn more or opt out of all or some cookies go to {cookie_policy} page.',
					'legalblink-policy' ),
				'type'        => 'textarea',
				// pass the setting array to customize the editor. For the setting argument, see http://codex.wordpress.org/Function_Reference/wp_editor.
				'rich'        => array(
					'media_buttons' => false,
					'tinymce'       => false,
				),
				'attributes'  => array(
					'field' => array(
						'style' => 'width: 100%;'
						// since the rich editor does not accept the cols attribute, set the width by inline-style.
					),
				),
			),
			array(
				'field_id'    => 'lbp_banner_cookie_alert_accept_button_caption',
				'title'       => __( 'Custom text for the accept button', 'legalblink-policy' ),
				'description' => __( 'Write here a custom text for the cookie acceptance button. (e.g. \'Ok, I understand\')',
					'legalblink-policy' ),
				'type'        => 'text',
				'default'     => __( 'Ok, I understand', 'legalblink-policy' ),
				'attributes'  => array(
					'size'        => 128,
					'placeholder' => __( 'Type something here.', 'legalblink-policy' ),
				),
			),
			array(
				'field_id'    => 'lbp_banner_cookie_alert_close_button_caption',
				'title'       => __( 'Custom text for the close button', 'legalblink-policy' ),
				'description' => __( 'Write here a custom text for the cookie banner close button. (e.g. \'Close\')',
					'legalblink-policy' ),
				'type'        => 'text',
				'default'     => __( 'Close', 'legalblink-policy' ),
				'attributes'  => array(
					'size'        => 128,
					'placeholder' => __( 'Type something here.', 'legalblink-policy' ),
				),
			),
			array(
				'field_id'    => 'lbp_banner_cookie_accept_cookie_methods',
				'title'       => __( 'Method of accepting cookies', 'legalblink-policy' ),
				'type'        => 'checkbox',
				'label'       => array(
					'consent_cookie_1' => __( 'Accept the cookie information by clicking on the ACCEPT button in the banner',
						'legalblink-policy' ),
					'consent_cookie_2' => __( 'Accept the cookie information on the mouse scroll event',
						'legalblink-policy' ),
					'consent_cookie_3' => __( 'Accept the cookie information by continuing to browse, accessing another area of the site',
						'legalblink-policy' ),
					'consent_cookie_4' => __( 'Accept the cookie information by clicking on the CLOSE button on the banner',
						'legalblink-policy' ),
				),
				'default'     => array(
					'consent_cookie_1' => true,
					'consent_cookie_2' => false,
					'consent_cookie_3' => false,
					'consent_cookie_4' => false,
				),
				'after_label' => '<br />',
			),
			array(
				'field_id' => 'lbp_is_banner_cookie_accept_cookie_reload_page',
				'title'    => __( 'Force reload page', 'legalblink-policy' ),
				'type'     => 'checkbox',
				'label'    => __( 'Force reload the current page when the user accepts the site cookies.',
					'legalblink-policy' ),
				'default'  => false,
			)
		);

		$this->addSettingSections(
			array(
				'section_id' => 'lbp_tab_banner_cookie_section_style_settings',
				'title'      => __( 'Style Settings', 'legalblink-policy' ),
			)
		);

		$this->addSettingFields(
			'lbp_tab_banner_cookie_section_style_settings',
			array(
				'field_id'    => 'lbp_banner_cookie_background_color',
				'title'       => __( 'Banner background color', 'legalblink-policy' ),
				'description' => __( 'Choose the background color of the banner.', 'legalblink-policy' ),
				'default'     => '#3b3b3b',
				'type'        => 'color',
			),
			array(
				'field_id'    => 'lbp_banner_cookie_text_color',
				'title'       => __( 'Banner text color', 'legalblink-policy' ),
				'description' => __( 'Choose the color of the banner text.', 'legalblink-policy' ),
				'default'     => '#ffffff',
				'type'        => 'color',
			),
			array(
				'field_id'    => 'lbp_banner_cookie_button_background_color',
				'title'       => __( 'Button background color', 'legalblink-policy' ),
				'description' => __( 'Choose the background color of the button.', 'legalblink-policy' ),
				'default'     => '#000000',
				'type'        => 'color',
			),
			array(
				'field_id'    => 'lbp_banner_cookie_button_text_color',
				'title'       => __( 'Button text color', 'legalblink-policy' ),
				'description' => __( 'Choose the text color of the button.', 'legalblink-policy' ),
				'default'     => '#ffffff',
				'type'        => 'color',
			),
			array(
				'field_id'    => 'lbp_banner_cookie_text_size',
				'title'       => __( 'Banner text size', 'legalblink-policy' ),
				'default'     => 12,
				'description' => __( 'Choose the size in pixels of the text.', 'legalblink-policy' ),
				'type'        => 'number',
			),
			array(
				'field_id' => 'lbp_is_banner_cookie_overlay_enabled',
				'title'    => __( 'Enable the overlay', 'legalblink-policy' ),
				'type'     => 'checkbox',
				'label'    => __( 'When the banner appears, a background color is applied to the page.',
					'legalblink-policy' ),
				'default'  => false,
			),
			array(
				'field_id'    => 'lbp_banner_cookie_overlay_color',
				'title'       => __( 'Overlay color', 'legalblink-policy' ),
				'description' => __( 'Choose the overlay color of the page.', 'legalblink-policy' ),
				'default'     => '#232525',
				'type'        => 'color',
			),
			array(
				'field_id'    => 'lbp_banner_cookie_animation_style',
				'title'       => __( 'Animation style', 'legalblink-policy' ),
				'type'        => 'select',
				'description' => __( 'Choose the animation to apply when the banner appears.', 'legalblink-policy' ),
				'default'     => 'none',
				'label'       => array(
					'none'          => __( 'No animation', 'legalblink-policy' ),
					'linear'        => __( 'Style 1', 'legalblink-policy' ),
					'easeInElastic' => __( 'Style 2', 'legalblink-policy' ),
					'easeInQuad'    => __( 'Style 3', 'legalblink-policy' ),
					'easeInCubic'   => __( 'Style 4', 'legalblink-policy' ),
					'easeInCirc'    => __( 'Style 5', 'legalblink-policy' ),
					'easeInBounce'  => __( 'Style 6', 'legalblink-policy' ),
				),
			),
			array(
				'field_id'    => 'lbp_banner_cookie_custom_css_class',
				'title'       => __( 'Custom CSS class', 'legalblink-policy' ),
				'description' => __( 'Enter a custom CSS class',
					'legalblink-policy' ),
				'type'        => 'text',
				'default'     => '',
				'attributes'  => array(
					'size' => 128,
				),
			)
		);

		$this->addSettingFields(
			array(
				'field_id'   => 'lbp_tab_banner_cookie_submit',
				'label'      => __( 'Save', 'legalblink-policy' ),
				'type'       => 'submit',
				'save'       => false,
				'attributes' => array(
					'class' => 'button button-secondary',
					'style' => 'background-color: #C1DCFA;',
					'field' => array(
						'style' => 'display: inline; clear: none;',
					),
				),
			)
		);
	}

	// COOKIE POLICY TAB
	public function load_lbp_tab_cookie_policy() {
		$this->addSettingSections(
			array(
				'section_id' => 'lbp_tab_cookie_policy_section_primary',
				'title'      => __( 'Cookie Policy Settings', 'legalblink-policy' ),
			)
		);

		$this->addSettingFields(
			'lbp_tab_cookie_policy_section_primary',
			array(
				'field_id' => 'lbp_is_cookie_policy_enabled',
				'title'    => __( 'Activate cookie policy page', 'legalblink-policy' ),
				'type'     => 'checkbox',
				'label'    => __( 'Shows the cookie policy content in a specific page of the site.',
					'legalblink-policy' ),
				'default'  => false,
			),
			array(
				'field_id'    => 'lbp_cookie_policy_cms_page',
				'title'       => __( 'Cookie policy page', 'legalblink-policy' ),
				'type'        => 'select',
				'description' => __( 'Choose the page used to show the cookie policy.', 'legalblink-policy' ) .
				                 '<p><a href="' . admin_url( 'edit.php?post_type=page',
						$_SERVER['HTTP_HOST'] ) . '" target="_self">' . __( 'Click here to go check the CMS pages of your site.',
						'legalblink-policy' ) . '</a></p>' .
				                 '<p style="color:red;">' . __( 'Warning! The selected CMS page will be overwritten.',
						'legalblink-policy' ) . '</p>',
				'default'     => 0,
				'label'       => $this->cms_pages,
			),
			array(
				'field_id'    => 'lbp_cookie_policy_url',
				'title'       => __( 'Cookie policy URL provided by LegalBlink', 'legalblink-policy' ),
				'type'        => 'text',
				'description' => '<p>' . __( 'Insert DIRECT LINK of the "Cookie Policy" document generated with LegalBlink.',
						'legalblink-policy' ) . '</p>' .
				                 '<p>' . __( 'The content of the document you generated using LegalBlink service will be gathered and displayed on the page you have selected in this section.',
						'legalblink-policy' ) . '</p>' .
				                 '<p><a href="https://legalblink.it/signup/" target="_blank">' . __( 'If you don\'t have an account yet, click here to register one freely and start generating your legal documents.',
						'legalblink-policy' ) . '</a></p>',
				'attributes'  => array(
					'size'        => 128,
					'placeholder' => 'https://legalblink.it/cmp/123456789',

				),
			),
			array(
				'field_id'    => 'lbp_cookie_policy_shortcode',
				'type'        => 'lbp_section_title',
				'title'       => __( 'Shortcode', 'legalblink-policy' ),
				'description' => __( 'Click or select to copy and paste this shortcode in every page that could used to show the policy.',
					'legalblink-policy' ),
				'content'     => '<div class="tooltip lbp-text-to-copy" title="' . __( 'Click to copy in clipboard.',
						'legalblink-policy' ) . '"><div class="lbp_policy_shortcode"><code class="lbp-text-to-copy-clipboard">[' . self::SHORTCODE_LBP_COOKIE_POLICY . ']</code></div></div>',
			)
		);

		$this->addSettingFields(
			array(
				'field_id'   => 'lbp_tab_cookie_policy_submit',
				'label'      => __( 'Save', 'legalblink-policy' ),
				'type'       => 'submit',
				'save'       => false,
				'attributes' => array(
					'class' => 'button button-secondary',
					'style' => 'background-color: #C1DCFA;',
					'field' => array(
						'style' => 'display: inline; clear: none;',
					),
				),
			)
		);
	}

	// PRIVACY POLICY TAB
	public function load_lbp_tab_privacy_policy() {
		$this->addSettingSections(
			array(
				'section_id' => 'lbp_tab_privacy_policy_section_primary',
				'title'      => __( 'Privacy Policy Settings', 'legalblink-policy' ),
			)
		);

		$this->addSettingFields(
			'lbp_tab_privacy_policy_section_primary',
			array(
				'field_id' => 'lbp_is_privacy_policy_enabled',
				'title'    => __( 'Activate privacy policy page', 'legalblink-policy' ),
				'type'     => 'checkbox',
				'label'    => __( 'Shows the privacy policy content in a specific page of the site.',
					'legalblink-policy' ),
				'default'  => false,
			),
			array(
				'field_id'    => 'lbp_privacy_policy_cms_page',
				'title'       => __( 'Privacy policy page', 'legalblink-policy' ),
				'type'        => 'select',
				'description' => __( 'Choose the page used to show the privacy policy.', 'legalblink-policy' ) .
				                 '<p><a href="' . admin_url( 'edit.php?post_type=page',
						$_SERVER['HTTP_HOST'] ) . '" target="_self">' . __( 'Click here to go check the CMS pages of your site.',
						'legalblink-policy' ) . '</a></p>' .
				                 '<p style="color:red;">' . __( 'Warning! The selected CMS page will be overwritten.',
						'legalblink-policy' ) . '</p>',
				'default'     => 0,
				'label'       => $this->cms_pages,
			),
			array(
				'field_id'    => 'lbp_set_privacy_policy_wp_page',
				'title'       => __( 'Set as default in Wordpress', 'legalblink-policy' ),
				'type'        => 'checkbox',
				'label'       => __( 'Set this page as the default privacy policy page in Wordpress.',
					'legalblink-policy' ),
				'description' => '<p><a href="' . admin_url( 'options-privacy.php',
						$_SERVER['HTTP_HOST'] ) . '" target="_self">' . __( 'Click here to go in privacy policy settings of your site.',
						'legalblink-policy' ) . '</a></p>',
				'default'     => false,
			),
			array(
				'field_id'    => 'lbp_privacy_policy_url',
				'title'       => __( 'Privacy policy URL provided by LegalBlink', 'legalblink-policy' ),
				'type'        => 'text',
				'description' => '<p>' . __( 'Insert DIRECT LINK of the "Privacy Policy" document generated with LegalBlink.',
						'legalblink-policy' ) . '</p>' .
				                 '<p>' . __( 'The content of the document you generated using LegalBlink service will be gathered and displayed on the page you have selected in this section.',
						'legalblink-policy' ) . '</p>' .
				                 '<p><a href="https://legalblink.it/signup/" target="_blank">' . __( 'If you don\'t have an account yet, click here to register one freely and start generating your legal documents.',
						'legalblink-policy' ) . '</a></p>',
				'attributes'  => array(
					'size'        => 128,
					'placeholder' => 'https://legalblink.it/pry/123456789',

				),
			),
			array(
				'field_id'    => 'lbp_privacy_policy_shortcode',
				'type'        => 'lbp_section_title',
				'title'       => __( 'Shortcode', 'legalblink-policy' ),
				'description' => __( 'Click or select to copy and paste this shortcode in every page that could used to show the policy.',
					'legalblink-policy' ),
				'content'     => '<div class="tooltip lbp-text-to-copy" title="' . __( 'Click to copy in clipboard.',
						'legalblink-policy' ) . '"><div class="lbp_policy_shortcode"><code class="lbp-text-to-copy-clipboard">[' . self::SHORTCODE_LBP_PRIVACY_POLICY . ']</code></div></div>',
			)
		);

		$this->addSettingFields(
			array(
				'field_id'   => 'lbp_tab_privacy_policy_submit',
				'label'      => __( 'Save', 'legalblink-policy' ),
				'type'       => 'submit',
				'save'       => false,
				'attributes' => array(
					'class' => 'button button-secondary',
					'style' => 'background-color: #C1DCFA;',
					'field' => array(
						'style' => 'display: inline; clear: none;',
					),
				),
			)
		);
	}

	// GCS POLICY TAB
	public function load_lbp_tab_gcs_policy() {
		$this->addSettingSections(
			array(
				'section_id' => 'lbp_tab_gcs_policy_section_primary',
				'title'      => __( 'General Conditions of Sale Policy Settings', 'legalblink-policy' ),
			)
		);

		$this->addSettingFields(
			'lbp_tab_gcs_policy_section_primary',
			array(
				'field_id' => 'lbp_is_gcs_policy_enabled',
				'title'    => __( 'Activate GCS policy page', 'legalblink-policy' ),
				'type'     => 'checkbox',
				'label'    => __( 'Shows the GCS policy content in a specific page of the site.',
					'legalblink-policy' ),
				'default'  => false,
			),
			array(
				'field_id'    => 'lbp_gcs_policy_cms_page',
				'title'       => __( 'GCS policy page', 'legalblink-policy' ),
				'type'        => 'select',
				'description' => __( 'Choose the page used to show the GCS policy.', 'legalblink-policy' ) .
				                 '<p><a href="' . admin_url( 'edit.php?post_type=page',
						$_SERVER['HTTP_HOST'] ) . '" target="_self">' . __( 'Click here to go check the CMS pages of your site.',
						'legalblink-policy' ) . '</a></p>' .
				                 '<p style="color:red;">' . __( 'Warning! The selected CMS page will be overwritten.',
						'legalblink-policy' ) . '</p>',
				'default'     => 0,
				'label'       => $this->cms_pages,
			),
			array(
				'field_id'    => 'lbp_gcs_policy_url',
				'title'       => __( 'GCS policy URL provided by LegalBlink', 'legalblink-policy' ),
				'type'        => 'text',
				'description' => '<p>' . __( 'Insert DIRECT LINK of the "GCS Policy" document generated with LegalBlink.',
						'legalblink-policy' ) . '</p>' .
				                 '<p>' . __( 'The content of the document you generated using LegalBlink service will be gathered and displayed on the page you have selected in this section.',
						'legalblink-policy' ) . '</p>' .
				                 '<p><a href="https://legalblink.it/signup/" target="_blank">' . __( 'If you don\'t have an account yet, click here to register one freely and start generating your legal documents.',
						'legalblink-policy' ) . '</a></p>',
				'attributes'  => array(
					'size'        => 128,
					'placeholder' => 'https://legalblink.it/gcs/123456789',

				),
			),
			array(
				'field_id'    => 'lbp_gcs_policy_shortcode',
				'type'        => 'lbp_section_title',
				'title'       => __( 'Shortcode', 'legalblink-policy' ),
				'description' => __( 'Click or select to copy and paste this shortcode in every page that could used to show the policy.',
					'legalblink-policy' ),
				'content'     => '<div class="tooltip lbp-text-to-copy" title="' . __( 'Click to copy in clipboard.',
						'legalblink-policy' ) . '"><div class="lbp_policy_shortcode"><code class="lbp-text-to-copy-clipboard">[' . self::SHORTCODE_LBP_GCS_POLICY . ']</code></div></div>',
			)
		);

		$this->addSettingFields(
			array(
				'field_id'   => 'lbp_tab_gcs_policy_submit',
				'label'      => __( 'Save', 'legalblink-policy' ),
				'type'       => 'submit',
				'save'       => false,
				'attributes' => array(
					'class' => 'button button-secondary',
					'style' => 'background-color: #C1DCFA;',
					'field' => array(
						'style' => 'display: inline; clear: none;',
					),
				),
			)
		);
	}

	// ADVANCED SETTINGS TAB
	public function load_lbp_tab_advanced_settings() {
		$this->addSettingSections(
			array(
				'section_id' => 'lbp_tab_advanced_settings_section_advanced_settings',
				'title'      => __( 'Advanced Settings', 'legalblink-policy' ),
			)
		);

		$this->addSettingFields(
			'lbp_tab_advanced_settings_section_advanced_settings',
			array(
				'field_id' => 'lbp_advanced_settings_auto_block_iframe',
				'title'    => __( 'Iframe blocking', 'legalblink-policy' ),
				'type'     => 'checkbox',
				'label'    => __( 'Automatically block all iframe detected by the plugin.', 'legalblink-policy' ),
				'default'  => false,
			),
			array(
				'field_id' => 'lbp_advanced_settings_auto_block_scripts',
				'title'    => __( 'Script blocking', 'legalblink-policy' ),
				'type'     => 'checkbox',
				'label'    => __( 'Automatically block known scripts detected by the plugin.', 'legalblink-policy' ),
				'default'  => false,
			),
			array(
				'field_id' => 'lbp_advanced_settings_auto_block_embed',
				'title'    => __( 'Embed blocking', 'legalblink-policy' ),
				'type'     => 'checkbox',
				'label'    => __( 'Automatically block all embed components detected by the plugin.',
					'legalblink-policy' ),
				'default'  => false,
			)
		/*
		array(
			'field_id' => 'lbp_advanced_settings_auto_block_third_part_cookies',
			'title'    => __('Block third-party cookies', 'legalblink-policy'),
			'type'     => 'checkbox',
			'label'    => __('Automatically block third part cookies detected by the plugin.',
				'legalblink-policy'),
			'default'  => false,
		),
		*/

		/*
		array(
			'field_id' => 'lbp_advanced_settings_section_block_scripts',
			'type'     => 'lbp_section_title',
			'title'    => '<strong><u>'
						  .__('Custom components to block', 'legalblink-policy')
						  .'</u></strong>',
		),
		// Iframe
		array(
			'field_id'   => 'lbp_advanced_settings_repeater_block_iframes',
			'title'      => __('Iframes', 'legalblink-policy'),
			'repeatable' => true,
			'sortable'   => true,
			'content'    => array(
				array(
					'field_id'   => 'lbp_iframe_to_block',
					'title'      => __('Custom iframe to block', 'legalblink-policy'),
					'type'       => 'text',
					'attributes' => array(
						'size'        => 128,
						'placeholder' => __('Enter custom iframe.', 'legalblink-policy'),

					),
				),
				array(
					'field_id'    => 'lbp_iframe_to_block_purpose',
					'title'       => __('Purpose', 'legalblink-policy'),
					'type'        => 'select',
					'description' => __('Select the iframe purpose.', 'legalblink-policy'),
					'default'     => 0,
					'label'       => $this->lbp_plugin_purpose,
					'attributes'  => array(
						'size'        => 128,
						'placeholder' => __('Enter custom iframe.', 'legalblink-policy'),
					),
				),
			),
		),
		// Scripts
		array(
			'field_id'   => 'lbp_advanced_settings_repeater_block_scripts',
			'title'      => __('Scripts', 'legalblink-policy'),
			'repeatable' => true,
			'sortable'   => true,
			'content'    => array(
				array(
					'field_id'   => 'lbp_script_to_block',
					'title'      => __('Custom script to block', 'legalblink-policy'),
					'type'       => 'text',
					'attributes' => array(
						'size'        => 128,
						'placeholder' => __('Enter custom script.', 'legalblink-policy'),

					),
				),
				array(
					'field_id'    => 'lbp_script_to_block_purpose',
					'title'       => __('Purpose', 'legalblink-policy'),
					'type'        => 'select',
					'description' => __('Select the script purpose.', 'legalblink-policy'),
					'default'     => 0,
					'label'       => $this->lbp_plugin_purpose,
					'attributes'  => array(
						'size'        => 128,
						'placeholder' => __('Enter custom script.', 'legalblink-policy'),
					),
				),
			),
		),
		// Embed
		array(
			'field_id'   => 'lbp_advanced_settings_repeater_block_embeds',
			'title'      => __('Embedded', 'legalblink-policy'),
			'repeatable' => true,
			'sortable'   => true,
			'content'    => array(
				array(
					'field_id'   => 'lbp_embeds_to_block',
					'title'      => __('Custom embed to block', 'legalblink-policy'),
					'type'       => 'text',
					'attributes' => array(
						'size'        => 128,
						'placeholder' => __('Enter custom embed.', 'legalblink-policy'),

					),
				),
				array(
					'field_id'    => 'lbp_embed_to_block_purpose',
					'title'       => __('Purpose', 'legalblink-policy'),
					'type'        => 'select',
					'description' => __('Select the embed purpose.', 'legalblink-policy'),
					'default'     => 0,
					'label'       => $this->lbp_plugin_purpose,
					'attributes'  => array(
						'size'        => 128,
						'placeholder' => __('Enter custom embed.', 'legalblink-policy'),
					),
				),
			),
		)
		*/
		);

		$this->addSettingFields(
			array(
				'field_id'   => 'lbp_tab_advanced_settings_submit',
				'label'      => __( 'Save', 'legalblink-policy' ),
				'type'       => 'submit',
				'save'       => false,
				'attributes' => array(
					'class' => 'button button-secondary',
					'style' => 'background-color: #C1DCFA;',
					'field' => array(
						'style' => 'display: inline; clear: none;',
					),
				),
			)
		);
	}

	// CACHE SETTINGS TAB
	public function load_lbp_tab_cache_settings() {
		$this->addSettingSections(
			array(
				'section_id' => 'lbp_tab_cache_settings_section_cache_settings',
				'title'      => __( 'Cache settings', 'legalblink-policy' ),
			)
		);

		$this->addSettingFields(
			'lbp_tab_cache_settings_section_cache_settings',
			array(
				'field_id' => 'lbp_tab_cache_settings_section',
				'type'     => 'lbp_section_title',
				'content'  => '<div>'
				              . __( 'To refresh policy texts, it is possible to delete the cache, clicking on the button below.',
						'legalblink-policy' )
				              . '</div>',
			),
			array(
				'field_id'   => 'lbp_tab_cache_settings_submit',
				'label'      => __( 'Clear cache', 'legalblink-policy' ),
				'type'       => 'submit',
				'save'       => false,
				'attributes' => array(
					'class' => 'button button-secondary',
					'style' => 'background-color: #C1DCFA;',
					'field' => array(
						'style' => 'display: inline; clear: none;',
					),
				),
			)
		);
	}

	public function display_plugin_header_info() {
		$legalblink_url = 'https://legalblink.it/';
		$logo           = LBP_ASSETS_URL . '/img/logo_main_96.png';
		?>
        <div class="legalblink_display_plugin_header_info_wrapper">
            <a href="<?= $legalblink_url ?>" target="_blank"><img src="<?= $logo ?>"/></a>
            <h2><?= __( 'The first generator of general conditions of sale, privacy policy and cookie policy for e-commerce.',
					'legalblink-policy' ); ?></h2>
            <p><?= __( 'With LegalBlink you forget about legal issues because you have a team of professionals who are experts in digital law who support your business.',
					'legalblink-policy' ); ?></p>
            <p><?= __( 'LegalBlink generates the legal documents of your business in the fastest and cheapest way.',
					'legalblink-policy' ); ?></p>
        </div>
		<?php
	}

	public function do_before_lbp_tab_banner_cookie( $class_obj ) {
		$this->display_plugin_header_info();
	}

	public function do_before_lbp_tab_cookie_policy( $class_obj ) {
		$this->display_plugin_header_info();
	}

	public function do_before_lbp_tab_privacy_policy( $class_obj ) {
		$this->display_plugin_header_info();
	}

	public function do_before_lbp_tab_gcs_policy( $class_obj ) {
		$this->display_plugin_header_info();
	}

	public function do_before_lbp_tab_advanced_settings( $class_obj ) {
		$this->display_plugin_header_info();
	}

	public function do_before_lbp_tab_cache_settings( $class_obj ) {
		$this->display_plugin_header_info();
	}

	public function options_update_status_lbp_tab_cookie_policy( $options ) {
		//
	}

	// + VALIDATION

	// Cookie Policy Validation
	public function validation_LBPSettingsPage_lbp_tab_cookie_policy_section_primary_lbp_cookie_policy_cms_page(
		$aNewInput,
		$aOldOptions
	) {
		return $this->custom_validation_LBPSettingsPage_lbp_policy_cms_page( $aNewInput, $aOldOptions,
			self::SHORTCODE_LBP_COOKIE_POLICY );
	}

	public function validation_LBPSettingsPage_lbp_tab_cookie_policy_section_primary_lbp_cookie_policy_url(
		$aNewInput,
		$aOldOptions
	) {
		return $this->custom_validation_LBPSettingsPage_lbp_policy_url( $aNewInput, $aOldOptions );
	}

	// Privacy Policy Validation
	public function validation_LBPSettingsPage_lbp_tab_privacy_policy_section_primary_lbp_privacy_policy_cms_page(
		$aNewInput,
		$aOldOptions
	) {
		return $this->custom_validation_LBPSettingsPage_lbp_policy_cms_page( $aNewInput, $aOldOptions,
			self::SHORTCODE_LBP_PRIVACY_POLICY );
	}

	public function validation_LBPSettingsPage_lbp_tab_privacy_policy_section_primary_lbp_privacy_policy_url(
		$aNewInput,
		$aOldOptions
	) {
		return $this->custom_validation_LBPSettingsPage_lbp_policy_url( $aNewInput, $aOldOptions );
	}

	// GCS Policy Validation
	public function validation_LBPSettingsPage_lbp_tab_gcs_policy_section_primary_lbp_gcs_policy_cms_page(
		$aNewInput,
		$aOldOptions
	) {
		return $this->custom_validation_LBPSettingsPage_lbp_policy_cms_page( $aNewInput, $aOldOptions,
			self::SHORTCODE_LBP_GCS_POLICY );
	}

	public function validation_LBPSettingsPage_lbp_tab_gcs_policy_section_primary_lbp_gcs_policy_url(
		$aNewInput,
		$aOldOptions
	) {
		return $this->custom_validation_LBPSettingsPage_lbp_policy_url( $aNewInput, $aOldOptions );
	}

	public function custom_validation_LBPSettingsPage_lbp_policy_cms_page(
		&$aNewInput,
		&$aOldOptions,
		$shortcode
	) {
		$bVerified = true;
		$aErrors   = array();

		// if ($aNewInput !== $aOldOptions) {
		$post_id = (int) $aNewInput;
		if ( LBPSettingsPageUtility::post_exists( $post_id ) ) {
			kses_remove_filters();
			$post_content   = '[' . $shortcode . ']';
			$edited_post    = array(
				'ID'           => $post_id,
				// 'post_status' => 'publish',
				'post_content' => $post_content,
				'post_type'    => 'page',
			);
			$result_post_id = wp_update_post( $edited_post, true );
			kses_init_filters();

			if ( is_wp_error( $result_post_id ) ) {
				$aErrors[] = __( 'A problem occurred when saving the page', 'legalblink-policy' );
				$bVerified = false;
			}
		} else {
			$bVerified = false;
			$aErrors[] = __( 'Page does not exists', 'legalblink-policy' );
		}
		// }

		if ( ! $bVerified ) {
			$this->setSettingNotice( __( 'There was an error: ', 'legalblink-policy' ) . implode( ', ', $aErrors ) );

			return $aOldOptions;
		}

		return $aNewInput;
	}

	public function custom_validation_LBPSettingsPage_lbp_policy_url(
		&$aNewInput,
		&$aOldOptions
	) {
		$bVerified = true;
		$aErrors   = array();

		if ( empty( $aNewInput ) ) {
			$bVerified = false;
			$aErrors[] = __( 'URL should not be empty', 'legalblink-policy' );
		}

		if ( ! $bVerified ) {
			//$this->setFieldErrors( $aErrors );
			$this->setSettingNotice( __( 'There was an error: ', 'legalblink-policy' ) . implode( ', ', $aErrors ) );

			return $aOldOptions;
		}

		return $aNewInput;
	}

	// Privacy Policy extra settings
	public function validation_LBPSettingsPage_lbp_tab_privacy_policy_section_primary(
		$aNewInput,
		$aOldOptions
	) {
		if ( isset( $aNewInput['lbp_set_privacy_policy_wp_page'] ) && (int) $aNewInput['lbp_set_privacy_policy_wp_page'] === 1 ) {
			if ( isset( $aNewInput['lbp_set_privacy_policy_wp_page'] ) ) {
				$privacy_policy_page_id = (int) $aNewInput['lbp_privacy_policy_cms_page'];
				update_option( 'wp_page_for_privacy_policy', $privacy_policy_page_id );
			}
		}

		return $aNewInput;
	}

	// Cache Settings
	public function validation_LBPSettingsPage_lbp_tab_cache_settings_section_cache_settings(
		$aNewInput,
		$aOldOptions
	) {
		$lbp_cookie_policy_url = LBPSettingsPageUtility::getOption( 'LBPSettingsPage',
			'lbp_tab_cookie_policy_section_primary', null, 'lbp_cookie_policy_url' );

		$lbp_privacy_policy_url = LBPSettingsPageUtility::getOption( 'LBPSettingsPage',
			'lbp_tab_privacy_policy_section_primary', null, 'lbp_privacy_policy_url' );

		$lbp_gcs_policy_url = LBPSettingsPageUtility::getOption( 'LBPSettingsPage',
			'lbp_tab_gcs_policy_section_primary', null, 'lbp_gcs_policy_url' );

		if ( ! empty( $lbp_cookie_policy_url ) ) {
			LBPPolicyUtility::clearCache( $lbp_cookie_policy_url );
		}
		if ( ! empty( $lbp_privacy_policy_url ) ) {
			LBPPolicyUtility::clearCache( $lbp_privacy_policy_url );
		}
		if ( ! empty( $lbp_gcs_policy_url ) ) {
			LBPPolicyUtility::clearCache( $lbp_gcs_policy_url );
		}

		$this->setSettingNotice( __( 'The cache has been cleared.', 'legalblink-policy' ), 'updated' );
	}

	/*
	public function validation_LBPSettingsPage($aInputs, $aOldInputs, $oFactory, $aSubmitInfo)
	{
		return $aInputs;
	}
	*/

	// - VALIDATION
}
