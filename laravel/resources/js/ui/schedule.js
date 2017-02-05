var $ = require('wetfish-basic');

$(document).ready(function()
{
    // This event handler dynamically populates the available shifts within departments
    $('.department-dropdown').on('change', function()
    {
        var department = $(this).value();

        // Remove any dynamically created shift options
        $('.shift-dropdown .dynamic').remove();

        if(department)
        {
            var shifts = JSON.parse($('.available-shifts').value());

            if(shifts[department] && shifts[department].length)
            {
                $('.shift-warning').addClass('hidden');

                shifts[department].forEach(function(shift)
                {
                    var option = document.createElement('option');
                    $(option).text(shift.name);
                    $(option).value(shift.id);
                    $(option).addClass('dynamic');

                    $('.shift-dropdown').append(option);
                });
            }
            else
            {
                $('.shift-warning').removeClass('hidden');
            }
        }
    });
});
