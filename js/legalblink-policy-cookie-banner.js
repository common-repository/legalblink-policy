let $ = jQuery;
;(function($) {
    let is_lbp_cookie_accepted = false;
    let is_secure = false;
    let is_reload_page = false;

    function lbp_console_log(...params) {
        console.log('LBP FRONT', params);
    }

    function lbp_getCookieValue(a) {
        var b = document.cookie.match('(^|;)\\s*' + a + '\\s*=\\s*([^;]+)');
        return b ? b.pop() : '';
    }

    function lbp_setCookie(name, value) {
        let secure = "";
        if (is_secure) {
            secure = ";secure;";
        }

        var exdate = new Date();
        exdate.setDate(exdate.getDate() + 365);
        document.cookie = name + "=" + value + "; expires=" + exdate.toUTCString() + "; path=/; " + secure;
    }

    function lbp_removeCookieBanner() {
        if ($('body div.overhang').length) {
            $('body div.overhang').remove();
        }
        if ($('body div.overhang-overlay').length) {
            $('body div.overhang-overlay').remove();
        }

        lbp_console_log('is_reload_page', is_reload_page);

        if (is_reload_page) {
            setTimeout(function () {
                location.reload();
            }, 1000);
        }
    }

    jQuery(document).ready(function ($) {
        lbp_console_log('READY');

        if (typeof lbp_cookie_banner_conf === 'undefined' || lbp_cookie_banner_conf === null) {
            lbp_console_log('No cookie banner configuration found.');
        } else {
            const lbp_cookie_banner_conf_default = {
                "general": {
                    "lbp_is_banner_cookie_enabled": "0",
                    "lbp_banner_cookie_position": "0",
                    "lbp_banner_cookie_alert_message": "This site uses third-party cookies to profile users, these cookies allow the correct use of our services.",
                    "lbp_banner_cookie_alert_accept_button_caption": "Ok, I understand",
                    "lbp_banner_cookie_alert_close_button_caption": "Close",
                    "lbp_banner_cookie_alert_message_case_1": "",
                    "lbp_banner_cookie_alert_message_case_2": "",
                    "lbp_banner_cookie_alert_message_case_3": "",
                    "lbp_banner_cookie_alert_message_case_4": "",
                    "lbp_banner_cookie_alert_message_case_5": "",
                    "lbp_banner_cookie_alert_message_case_end": "consent to the use of cookies.",
                    "lbp_banner_cookie_alert_message_extra": "Any action will consent to the use of cookies.",
                    "lbp_is_banner_cookie_accept_cookie_reload_page": "0",
                    "lbp_banner_cookie_accept_cookie_methods": {
                        "consent_cookie_1": "1",
                        "consent_cookie_2": "0",
                        "consent_cookie_3": "0",
                        "consent_cookie_4": "0"
                    }
                },
                "style": {
                    "lbp_banner_cookie_background_color": "#3b3b3b",
                    "lbp_banner_cookie_text_color": "#ffffff",
                    "lbp_banner_cookie_button_background_color": "#000000",
                    "lbp_banner_cookie_button_text_color": "#ffffff",
                    "lbp_banner_cookie_text_size": "12",
                    "lbp_banner_cookie_custom_css_class": "",
                    "lbp_is_banner_cookie_overlay_enabled": "0",
                    "lbp_banner_cookie_overlay_color": "#e5e5e5",
                    "lbp_banner_cookie_animation_style": "linear",
                },
                "is_secure": false,
            };

            const lbp_cookie_banner_config = {...lbp_cookie_banner_conf_default, ...lbp_cookie_banner_conf};

            if (typeof lbp_cookie_banner_config.is_secure !== 'undefined' && lbp_cookie_banner_config.is_secure !== null && lbp_cookie_banner_config.is_secure === true) {
                is_secure = true;
            }

            if (typeof lbp_cookie_banner_config.general.lbp_is_banner_cookie_accept_cookie_reload_page !== 'undefined' &&
                lbp_cookie_banner_config.general.lbp_is_banner_cookie_accept_cookie_reload_page !== null &&
                parseInt(lbp_cookie_banner_config.general.lbp_is_banner_cookie_accept_cookie_reload_page, 10) === 1) {
                is_reload_page = true;
            }

            lbp_console_log('is_secure', is_secure);

            if (parseInt(lbp_cookie_banner_config.general.lbp_is_banner_cookie_enabled, 10) === 1) {
                const font_size = lbp_cookie_banner_config.style.lbp_banner_cookie_text_size + 'px';

                let lbp_cookie_banner_html = '<div style="font-size: ' +
                    font_size +
                    '" class="lbp_cookie_banner_message">' +
                    lbp_cookie_banner_config.general.lbp_banner_cookie_alert_message + '</div>';

                const button_accept = '<button style="font-size: ' + font_size + '; background-color: ' + lbp_cookie_banner_config.style.lbp_banner_cookie_button_background_color +
                    ';color: ' + lbp_cookie_banner_config.style.lbp_banner_cookie_button_text_color +
                    ';" class="lbp_cookie_banner_button lbp_cookie_banner_button_accept">' + lbp_cookie_banner_config.general.lbp_banner_cookie_alert_accept_button_caption + '</button>';
                const button_close = '<button style="font-size: ' + font_size + '; background-color: ' + lbp_cookie_banner_config.style.lbp_banner_cookie_button_background_color +
                    ';color: ' + lbp_cookie_banner_config.style.lbp_banner_cookie_button_text_color +
                    ';" class="lbp_cookie_banner_button lbp_cookie_banner_button_close">' + lbp_cookie_banner_config.general.lbp_banner_cookie_alert_close_button_caption + '</button>';
                const button_container_start = '<div class="lbp_cookie_banner_button_container">';
                const button_container_end = '</div>';

                // Accept the cookie information by clicking on the ACCEPT button in the banner
                const consent_cookie_1 = parseInt(lbp_cookie_banner_config.general.lbp_banner_cookie_accept_cookie_methods.consent_cookie_1, 10);
                // Accept the cookie information on the mouse scroll event
                const consent_cookie_2 = parseInt(lbp_cookie_banner_config.general.lbp_banner_cookie_accept_cookie_methods.consent_cookie_2, 10);
                // Accept the cookie information by continuing to browse, accessing another area of the site
                const consent_cookie_3 = parseInt(lbp_cookie_banner_config.general.lbp_banner_cookie_accept_cookie_methods.consent_cookie_3, 10);
                // Accept the cookie information by clicking on the CLOSE button on the banner
                const consent_cookie_4 = parseInt(lbp_cookie_banner_config.general.lbp_banner_cookie_accept_cookie_methods.consent_cookie_4, 10);

                const is_overlay = parseInt(lbp_cookie_banner_config.style.lbp_is_banner_cookie_overlay_enabled, 10) === 0 ? false : true;

                let lbp_cookie_banner_html_full = '';
                lbp_cookie_banner_html += '<div style="font-size: ' +
                    font_size +
                    '" class="lbp_cookie_banner_message">' +
                    lbp_cookie_banner_config.general.lbp_banner_cookie_alert_message_extra + '</div>';

                if (consent_cookie_1 === 1) {
                    lbp_cookie_banner_html_full = lbp_cookie_banner_html + button_container_start + button_accept + button_container_end;
                }

                if (consent_cookie_4 === 1) {
                    lbp_cookie_banner_html_full = lbp_cookie_banner_html + button_container_start + button_close + button_container_end;
                }

                if (consent_cookie_1 === 1 && consent_cookie_4 === 1) {
                    lbp_cookie_banner_html_full = lbp_cookie_banner_html + button_container_start + button_accept + button_close + button_container_end;
                }

                is_lbp_cookie_accepted = false;
                let lbp_cookie_accepted = lbp_getCookieValue('lbp_cookie_accepted');
                if (typeof lbp_cookie_accepted !== 'undefined' && lbp_cookie_accepted !== null && lbp_cookie_accepted !== "") {
                    is_lbp_cookie_accepted = true;
                }

                // If cookie policy is not accepted yet
                if ($.fn.overhang && !is_lbp_cookie_accepted) {

                    let overhangSpeed = 1000;
                    let overhandLbp_banner_cookie_animation_style = lbp_cookie_banner_config.style.lbp_banner_cookie_animation_style;
                    if (lbp_cookie_banner_config.style.lbp_banner_cookie_animation_style === 'none') {
                        overhandLbp_banner_cookie_animation_style = 'linear';
                        overhangSpeed = 0;
                    }

                    $("body").overhang({
                        type: "info",
                        primary: lbp_cookie_banner_config.style.lbp_banner_cookie_background_color,
                        accent: lbp_cookie_banner_config.style.lbp_banner_cookie_text_color,
                        message: lbp_cookie_banner_html_full,
                        custom: true,
                        html: true,
                        overlay: is_overlay,
                        overlayColor: lbp_cookie_banner_config.style.lbp_banner_cookie_overlay_color,
                        easing: overhandLbp_banner_cookie_animation_style,
                        customClasses: lbp_cookie_banner_config.style.lbp_banner_cookie_custom_css_class,
                        duration: 999999,
                        closeConfirm: false,
                        speed: overhangSpeed,
                        // callback: function (value) {}
                    });

                    if (consent_cookie_1 === 1) {
                        $('button.lbp_cookie_banner_button_accept').on('click touchstart', function (event) {
                            event.preventDefault();
                            event.stopPropagation();
                            lbp_console_log('button accept, cookies accepted');
                            is_lbp_cookie_accepted = true;
                            lbp_setCookie("lbp_cookie_accepted", "yes");
                            lbp_removeCookieBanner();
                        });
                    }

                    if (consent_cookie_2 === 1) {
                        let lbp_cookie_accepted = lbp_getCookieValue('lbp_cookie_accepted');
                        if (typeof lbp_cookie_accepted === 'undefined' || lbp_cookie_accepted === null || lbp_cookie_accepted === "") {
                            $(document).scroll(function () {
                                if (!is_lbp_cookie_accepted) {
                                    lbp_console_log('scrolling, cookies accepted');
                                    is_lbp_cookie_accepted = true;
                                    lbp_setCookie("lbp_cookie_accepted", "yes");
                                    lbp_removeCookieBanner();
                                }
                            });
                        }
                    }

                    if (consent_cookie_3 === 1) {
                        let lbp_cookie_visit = lbp_getCookieValue('lbp_cookie_visit');
                        lbp_console_log('lbp_cookie_visit', lbp_cookie_visit);
                        if (typeof lbp_cookie_visit === 'undefined' || lbp_cookie_visit === null || lbp_cookie_visit === "") {
                            lbp_setCookie("lbp_cookie_visit", "yes");
                        } else {
                            lbp_console_log('visited another page, cookies accepted');
                            is_lbp_cookie_accepted = true;
                            lbp_setCookie("lbp_cookie_accepted", "yes");
                            lbp_removeCookieBanner();
                        }
                    }

                    if (consent_cookie_4 === 1) {
                        $('button.lbp_cookie_banner_button_close').on('click touchstart', function (event) {
                            event.preventDefault();
                            event.stopPropagation();
                            lbp_console_log('button close, cookies accepted');
                            is_lbp_cookie_accepted = true;
                            lbp_setCookie("lbp_cookie_accepted", "yes");
                            lbp_removeCookieBanner();
                        });
                    }
                }

            } else {
                lbp_console_log('Cookie banner is disabled.');
            }
        }
    });
})(jQuery);