jQuery(document).ready(function ($) {
    "use strict";
    window.submitting = false;

    //Contact
    $('form.php-email-form').submit(function () {
        // ignore subsequent clicks when an email is already being sent out.
        if (window.submitting) return false;
        // disable submit button
        $('#form-submit-btn').attr("disabled", true);


        const str = $(this).serialize();
        let f = $(this).find('.form-group'),
            ferror = false,
            emailExp = /^[^\s()<>@,;:\/]+@\w[\w\.-]+\.[a-z]{2,}$/i;

        f.children('input').each(function () {
            let exp;
            // run all inputs

            const i = $(this); // current input
            let rule = i.attr('data-rule');

            if (rule !== undefined) {
                let ierror = false; // error flag for current input
                const pos = rule.indexOf(':', 0);
                if (pos >= 0) {
                    exp = rule.substring(pos + 1, rule.length);
                    rule = rule.substring(0, pos);
                } else {
                    rule = rule.substring(pos + 1, rule.length);
                }

                switch (rule) {
                    case 'required':
                        if (i.val() === '') {
                            ferror = ierror = true;
                        }
                        break;

                    case 'minlen':
                        if (i.val().length < parseInt(exp)) {
                            ferror = ierror = true;
                        }
                        break;

                    case 'email':
                        if (!emailExp.test(i.val())) {
                            ferror = ierror = true;
                        }
                        break;

                    case 'checked':
                        if (!i.attr('checked')) {
                            ferror = ierror = true;
                        }
                        break;

                    case 'regexp':
                        exp = new RegExp(exp);
                        if (!exp.test(i.val())) {
                            ferror = ierror = true;
                        }
                        break;
                }
                i.next('.validation').html((ierror ? (i.attr('data-msg') !== undefined ? i.attr('data-msg') : 'wrong Input') : '')).show('blind');
            }
        });

        f.children('textarea').each(function () {
            let exp ;
            // run all inputs

            const i = $(this); // current input
            let rule = i.attr('data-rule');

            if (rule !== undefined) {
                let ierror = false; // error flag for current input
                const pos = rule.indexOf(':', 0);
                if (pos >= 0) {
                    exp = rule.substring(pos + 1, rule.length)
                    rule = rule.substring(0, pos);
                } else {
                    rule = rule.substring(pos + 1, rule.length);
                }

                switch (rule) {
                    case 'required':
                        if (i.val() === '') {
                            ferror = ierror = true;
                        }
                        break;

                    case 'minlen':
                        if (i.val().length < parseInt(exp)) {
                            ferror = ierror = true;
                        }
                        break;
                }
                i.next('.validation').html((ierror ? (i.attr('data-msg') !== undefined ? i.attr('data-msg') : 'wrong Input') : '')).show('blind');
            }
        });
        if (ferror) return false;
        $.ajax({
            type: "POST",
            url: "contactform/contactform.php",
            data: str,
            success: function (msg) {
                // alert(msg);
                if (msg === 'OK') {
                    $("#sendmessage").addClass("show");
                    $("#errormessage").removeClass("show");
                    $('.php-email-form').find("input, textarea").val("");
                } else {
                    $("#sendmessage").removeClass("show");
                    $("#errormessage").addClass("show");
                    $('#errormessage').html(msg);
                }

                // enable th button
                window.submitting = false;
                // enable submit button
                $('#form-submit-btn').attr("disabled", false);
            }
        });
        return false;
    });

});
