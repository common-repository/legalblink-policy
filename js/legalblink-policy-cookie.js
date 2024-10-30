;(function($) {
    jQuery(document).ready(function ($) {
        function lbp_console_log(...params) {
            console.log('LBP FRONT COOKIE', params);
        }

        function lbp_c_getCookie(cname) {
            var name = cname + "=";
            var decodedCookie = decodeURIComponent(document.cookie);
            var ca = decodedCookie.split(';');
            for (var i = 0; i < ca.length; i++) {
                var c = ca[i];
                while (c.charAt(0) == ' ') {
                    c = c.substring(1);
                }
                if (c.indexOf(name) == 0) {
                    return c.substring(name.length, c.length);
                }
            }
            return "";
        }

        function lbp_c_removeCookie(cname) {
            var d = new Date();
            d.setTime(d.getTime() - 1);
            var expires = "expires=" + d.toUTCString();
            document.cookie = cname + "=; " + expires;
        }

        function lbp_c_listCookies() {
            var theCookies = document.cookie.split(';');
            var aString = '';
            for (var i = 1; i <= theCookies.length; i++) {
                aString += i + ' ' + theCookies[i - 1] + "\n";
            }
            lbp_console_log(aString);
        }

        function lbp_c_checkCookieSet() {

            var cookie = lbp_c_getCookie("lbp_cookie_accepted");
            lbp_console_log(cookie);

            var tr = $("#table_cookie_profiling_first_part tr").length;
            var cookies = tr;

            lbp_console_log(cookies);

            if (cookie === "") {
                for (i = 2; i <= cookies; i++) {
                    $("input[name=cookie_accept_" + i + "][value='false']").attr('checked', 'false');
                }
            } else {
                var cookie_handler = lbp_c_getCookie("cookieHandler");
                lbp_console_log(cookie_handler);
                if (cookie_handler == "") {
                    for (i = 2; i <= cookies; i++) {
                        $("input[name=cookie_accept_" + i + "][value='true']").attr('checked', 'true');
                    }
                } else {
                    lbp_c_checkCookieHandler();
                }
            }
        }

        function lbp_c_checkCookieHandler() {
            var theCookies = document.cookie.split(';');
            var aString = '';
            lbp_console_log(theCookies);
            for (var i = 1; i <= theCookies.length; i++) {
                if (theCookies[i - 1].indexOf('cookieHandler') != -1) {
                    lbp_console_log('TROVATO\n' + theCookies[i - 1]);

                    var singoloCookie = theCookies[i - 1].split('|');
                    for (var z = 1; z <= singoloCookie.length; z++) {
                        lbp_console_log(singoloCookie[z - 1]);
                        var c = singoloCookie[z - 1].split('-');
                        var nomeCookie = c[0];
                        var valoreCookie = c[1];
                        lbp_console_log(nomeCookie + " --> " + valoreCookie);

                        var tr = $("#table_cookie_profiling_first_part tr").length;
                        var cookies = tr;
                        for (i = 2; i <= cookies; i++) {
                            if (nomeCookie.indexOf($("#table_cookie_profiling_first_part tr:nth-child(" + i + ") td:nth-child(1)").text()) != -1) {
                                $("input[name=cookie_accept_" + i + "][value='" + valoreCookie + "']").attr('checked', valoreCookie);
                                $("input[name=cookie_accept_" + i + "][value='" + valoreCookie + "']").prop('checked', valoreCookie);
                            }
                        }
                    }

                }
            }
        }

        valoreCookie = '';

        var tr = $("#table_cookie_profiling_first_part tr").length;

        var tr_add = '<tr style="border:0px !important; padding:0px !important; margin:0px !important"><td colspan="3" style="padding-right:5px !important; margin:0px !important; text-align:left !important; vertical-align:middle !important"><strong>' + lbp_cookie_policy_conf.texts.alert1 + '</strong></td>\n<td align="center" valign="middle" style="padding:5px !important; margin:5px !important; text-align:center !important; vertical-align:middle !important"><span class="button-red-cookie"> \t\t\t\t\t\t\t\t<span> \t\t\t\t\t\t\t\t\t<button class="btn btn-default button button-medium" id="salvaPreferenze"><span style="text-transform: uppercase;">' + lbp_cookie_policy_conf.texts.save + '</span></button> \t\t\t\t\t\t\t\t\t</span> \t\t\t\t\t\t\t</span>                  <!-- FINE CODICE PER ACCETTAZIONE ON-OFF COOKIES DI PRIMA PARTE --></td></tr>';
        $("#table_cookie_profiling_first_part").append(tr_add);

        var cookies = tr;

        for (i = 2; i <= cookies; i++) {
            var field = '<fieldset style="min-width:120px; border: none !important; text-align:left !important; display:block; margin: 0; padding: 0;"><label><input type="radio" name="cookie_accept_' + i + '" value="true">&nbsp;' + lbp_cookie_policy_conf.texts.enable + '</label><br> <label><input type="radio" name="cookie_accept_' + i + '" value="false">&nbsp;' + lbp_cookie_policy_conf.texts.disable + '</label></fieldset>';
            $("#table_cookie_profiling_first_part tr:nth-child(" + i + ")").append('<td>' + field + '</td>')
        }

        $('#salvaPreferenze').click(function (e) {
            e.preventDefault();
            e.stopPropagation();

            for (i = 2; i <= cookies; i++) {
                if ($("#table_cookie_profiling_first_part tr:nth-child(" + i + ") td:nth-child(1)").text() == "false")
                    lbp_c_removeCookie($("#table_cookie_profiling_first_part tr:nth-child(" + i + ") td:nth-child(1)").text());

                valoreCookie += '|' + $("#table_cookie_profiling_first_part tr:nth-child(" + i + ") td:nth-child(1)").text() + '-' + $("input[name=cookie_accept_" + i + "]:checked").val();
            }

            let is_secure = false;
            if (typeof lbp_cookie_banner_conf.is_secure !== 'undefined' && lbp_cookie_banner_conf.is_secure !== null && lbp_cookie_banner_conf.is_secure === true) {
                is_secure = true;
            }

            lbp_console_log('Cookie policy: is_secure?', is_secure);

            let secure = "";
            if (is_secure) {
                secure = ";secure;";
            }

            var exdate = new Date();
            exdate.setDate(exdate.getDate() + 365);
            document.cookie = "cookieHandler" + "=" + valoreCookie + "; expires=" + exdate.toUTCString() + "; path=/;" + secure;
        });

        // lbp_c_listCookies();
        lbp_c_checkCookieSet();
    });
})(jQuery);
