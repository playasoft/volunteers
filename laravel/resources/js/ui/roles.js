var $ = require('wetfish-basic');

$(document).ready(function()
{
    // Helper code for role checkboxes
    $('.role').on('click', function()
    {
        $('.roles-all, .roles-none').prop('checked', false);
    });

    $('.roles-all').on('click', function()
    {
        if(this.checked)
        {
            $('.roles-none').prop('checked', false);
            $('.role').prop('checked', true);
        }
    });

    $('.roles-none').on('click', function()
    {
        if(this.checked)
        {
            $('.roles-all').prop('checked', false);
            $('.role').prop('checked', false);
        }
    });
});
