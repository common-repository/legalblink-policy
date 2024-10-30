;(function($) {
    function lbp_console_log(...params) {
        console.log('LBP ADMIN', params);
    }

    function lbp_fallbackCopyTextToClipboard(text) {
        var textArea = document.createElement("textarea");
        textArea.value = text;

        // Avoid scrolling to bottom
        textArea.style.top = "0";
        textArea.style.left = "0";
        textArea.style.position = "fixed";

        document.body.appendChild(textArea);
        textArea.focus();
        textArea.select();

        try {
            var successful = document.execCommand('copy');
            var msg = successful ? 'successful' : 'unsuccessful';
            console.log('Fallback: Copying text command was ' + msg);
        } catch (err) {
            console.error('Fallback: Oops, unable to copy', err);
        }

        document.body.removeChild(textArea);
    }

    function lbp_copyTextToClipboard(text) {
        if (!navigator.clipboard) {
            lbp_fallbackCopyTextToClipboard(text);
            return;
        }
        navigator.clipboard.writeText(text).then(function () {
            console.log('Async: Copying to clipboard was successful!');
        }, function (err) {
            console.error('Async: Could not copy text: ', err);
        });
    }

    jQuery(document).ready(function ($) {
        lbp_console_log('READY');

        $('.lbp-text-to-copy').on('click', function (e) {
            lbp_copyTextToClipboard($(this).find('.lbp-text-to-copy-clipboard').text());
        });
    });
})(jQuery);