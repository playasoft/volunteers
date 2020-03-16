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
            $('.report-options select').trigger('change');
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
            $('.user-search, .users').addClass('hidden');
        }
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
            fetch('/report/departments', ajaxOptions).then(function(request)
            {
                request.json().then(function(response)
                {
                    $('.departments-wrap .loading').addClass('hidden');
                    $('.departments').removeClass('hidden');
                    $('.departments table tbody tr').remove();

                    for(var key in response)
                    {
                        var department = response[key];
                        var template = $('.departments table .template').clone();
                        $(template).removeClass('template hidden');

                        template.innerHTML = template.innerHTML.replace(/{department_id}/g, department.id);
                        template.innerHTML = template.innerHTML.replace(/{department_name}/g, department.name);

                        $('.departments table tbody').append(template);
                    }
                });
            });
        }
        else
        {
            $('.departments-wrap .loading, .departments').addClass('hidden');
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
            fetch('/report/days', ajaxOptions).then(function(request)
            {
                request.json().then(function(response)
                {
                    $('.days-wrap .loading').addClass('hidden');
                    $('.days').removeClass('hidden');
                    $('.days table tbody tr').remove();

                    for(var key in response)
                    {
                        var day = response[key];
                        var template = $('.days table .template').clone();
                        $(template).removeClass('template hidden');

                        template.innerHTML = template.innerHTML.replace(/{date}/g, day.date);
                        template.innerHTML = template.innerHTML.replace(/{day}/g, day.name);

                        $('.days table tbody').append(template);
                    }
                });
            });
        }
        else
        {
            $('.days-wrap .loading, .days').addClass('hidden');
        }
    });

    // Make sure all dropdowns are empty on page load
    $('.report-generator select').each(function()
    {
        $(this).find('option').el[0].selected = true;
        $(this).trigger('change');
    });
});
