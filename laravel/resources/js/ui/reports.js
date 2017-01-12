var $ = require('wetfish-basic');
var ajaxOptions =
{
    method: 'post',
    credentials: 'include',
    headers:
    {
        'Content-Type': 'application/json'
    }
};

$(document).ready(function()
{
    // Make sure we're on the reports page
    if(!$('.report-generator').el.length)
    {
        return;
    }

    // Only show report options when an event is selected
    $('.report-event').on('change', function()
    {
        if(parseInt($(this).value()))
        {
            $('.report-types').removeClass('hidden');
        }
        else
        {
            $('.report-types, .report-options').addClass('hidden');
        }
    });

    // Show additional report options when the report type is changed
    $('.report-type').on('change', function()
    {
        var type = $(this).value();
        $('.report-options').addClass('hidden');
        $('.report-options[data-type="'+type+'"]').removeClass('hidden');
    });

    $('.user-options').on('change', function()
    {
        if($(this).value() == 'specific')
        {
            $('.user-search').removeClass('hidden');
        }
        else
        {
            $('.user-search').addClass('hidden');
        }
    });

    $('.user-search form').on('submit', function(event)
    {
        event.preventDefault();

        var data =
        {
            search: $('.user-search input').value(),
            _token: $('.csrf-token').value()
        };

        ajaxOptions.body = JSON.stringify(data);

        // Submit data
        fetch('/report/users', ajaxOptions).then(function(response)
        {
            console.log(response);
        });
    });

    $('.department-options').on('change', function()
    {
        if($(this).value() == 'specific')
        {
            $('.departments-wrap .loading').removeClass('hidden');

            // Fetch list of departments based on the currently selected event
            var data =
            {
                event: parseInt($('.report-event').value()),
                _token: $('.csrf-token').value()
            };

            ajaxOptions.body = JSON.stringify(data);

            // Submit data
            fetch('/report/departments', ajaxOptions).then(function(response)
            {
                console.log(response);
            });
        }
        else
        {
            $('.departments-wrap .loading').addClass('hidden');
        }
    });

    $('.day-options').on('change', function()
    {
        if($(this).value() == 'specific')
        {
            $('.days-wrap .loading').removeClass('hidden');

            // Fetch list of event days based on the currently selected event
            var data =
            {
                event: parseInt($('.report-event').value()),
                _token: $('.csrf-token').value()
            };

            ajaxOptions.body = JSON.stringify(data);

            // Submit data
            fetch('/report/days', ajaxOptions).then(function(response)
            {
                console.log(response);
            });
        }
        else
        {
            $('.days-wrap .loading').addClass('hidden');
        }
    });

    // Make sure all dropdowns are empty on page load
    $('.report-generator select').each(function()
    {
        $(this).find('option').el[0].selected = true;
        $(this).trigger('change');
    });
});
