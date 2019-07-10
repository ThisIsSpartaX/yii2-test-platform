$(function() {
    if($('#registrationform-user_type').val() != 'entity') {
        $('.field-registrationform-company_name').hide();
    }

    $('#registrationform-usertype-toggle').change(function() {
        if($(this).prop('checked')) {
            $('.field-registrationform-company_name').show();
            $('#registrationform-company_name').removeAttr('disabled');
            $('#registrationform-user_type').val('entity');
        } else {
            $('.field-registrationform-company_name').hide();
            $('#registrationform-company_name').attr('disabled', 'disabled');
            $('#registrationform-user_type').val('individual');
        }
    });
});