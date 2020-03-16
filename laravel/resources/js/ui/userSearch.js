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
                    template.innerHTML = template.innerHTML.replace(/{burner_name}/g, user.burner_name);
                    template.innerHTML = template.innerHTML.replace(/{full_name}/g, user.full_name);
                    template.innerHTML = template.innerHTML.replace(/{email}/g, user.email);

                    $('.users table tbody').append(template);
                }
            });
        });
    });

});
