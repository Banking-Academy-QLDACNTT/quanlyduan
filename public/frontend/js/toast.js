var jqToast = (function($) {
    // create the container if it doesn't exist
    var $toastContainer = $('.toast-container');

    if ($toastContainer.length === 0) {
        $('.body').append($('<div>').addClass('toast-container'));
    }

    var getOptions = function(opts) {
        return $.extend({}, {
                displayTime: 3000,
                fadeTime: 1750,
                text: ''
            },
            opts);
    };

    var closeToast = function($toast, fadeTime) {
        // Add the fading class and remove the 'click' binding
        $toast
            .addClass('fading')
            .off('click');

        // Fade the toast message out and remove it
        $toast.fadeOut(fadeTime, function() {
            //$toast.remove();
        });
    }

    var addToast = function(type, options) {
        var alerClass = '';
        // Determine icon type
        switch (type) {
            case 'success':
                alerClass = 'alert-success';
                break;
            case 'warning':
                alerClass = 'alert-warning';
                break
            case 'error':
                alerClass = 'alert-danger';
                break;
        }
        // Build the icon element
        var $button = $('<button data-bs-dismiss="alert" aria-label="Close">').addClass('btn-close')
        // Build the toast message and add it to the container
        var $toast = $('<div>')
            .addClass('toast ' + type)
            .append($('<div>').addClass('alert alert-dismissible ' + alerClass)
                .append($button)
                .append($('<div>').addClass('text')
                    .append(options.text)
                )
            );
        $('.toast-container').append($toast);

        // Close the toast message
        $toast.on('click', function() {
            closeToast($toast, options.fadeTime);
        });

        // Automatic fade
        setTimeout(function() {
            closeToast($toast, options.fadeTime);
        }, options.displayTime);
    }

    var successToast = function(opts) {
        var options = getOptions(opts);
        addToast('success', options);
    }

    var warningToast = function(opts) {
        var options = getOptions(opts);
        addToast('warning', options);
    }

    var errorToast = function(opts) {
        var options = getOptions(opts);
        addToast('error', options);
    }

    return {
        success: function(opts) {
            successToast(opts);
        },
        warning: function(opts) {
            warningToast(opts);
        },
        error: function(opts) {
            errorToast(opts);
        }
    }
})(jQuery);