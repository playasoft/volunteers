var $ = require('wetfish-basic');

$(document).ready(function()
{
    // Show report options when the report type is changed 
    $('.report-type').on('change', function()
    {
        var type = $(this).value();
        $('.report-options').addClass('hidden');
        $('.report-options[data-type="'+type+'"]').removeClass('hidden');
    });

    // Trigger change on page load
    $('.report-type').trigger('change');
});
