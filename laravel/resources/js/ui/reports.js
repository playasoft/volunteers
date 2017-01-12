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
            $('.user-search').addClass('hidden');
        }
    });

    $('.user-search input').on('keydown', function(event)
    {
        // If enter was pressed
        if(event.which == 13)
        {
            event.preventDefault();
            $('.user-search').trigger('submit');
        }
    });

    $('.user-search button').on('click', function(event)
    {
        event.preventDefault();
        $('.user-search').trigger('submit');
    });

    $('.user-search').on('submit', function(event)
    {
        event.preventDefault();
        $('.user-wrap .loading').removeClass('hidden');

        var data =
        {
            search: $('.user-search input').value(),
            _token: $('.csrf-token').value()
        };

        ajaxOptions.body = JSON.stringify(data);

        // Submit data
        fetch('/report/users', ajaxOptions).then(function(request)
        {
            request.json().then(function(response)
            {
                $('.user-wrap .loading').addClass('hidden');
                $('.users').removeClass('hidden');
                $('.users table tbody tr').remove();

                for(var key in response)
                {
                    var user = response[key];
                    var template = $('.users table .template').clone();
                    $(template).removeClass('template hidden');

                    template.innerHTML = template.innerHTML.replace(/{user_id}/g, user.id);
                    template.innerHTML = template.innerHTML.replace(/{username}/g, user.name);
                    template.innerHTML = template.innerHTML.replace(/{real_name}/g, user.real_name);
                    template.innerHTML = template.innerHTML.replace(/{email}/g, user.email);

                    $('.users table tbody').append(template);
                }
            });
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
