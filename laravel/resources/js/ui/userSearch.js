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
var concurrentSlotUsers = [];

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
        concurrentSlotUsers = [];

        var data =
        {
            search: $('.user-search input').value(),
            _token: $('.csrf-token').value(),
            concurrentSlotCheck: $('.slot-number').value(), //optional
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

                    if(user.slot_conflict) {
                      concurrentSlotUsers.push(user.id);
                    }
                }

                // show a warning if a user assigned to a concurrent slot is picked
                $("input[name='user']").on('click', function(event)
                {
                  let user_id = parseInt($(this).value());
                  if(concurrentSlotUsers.indexOf(user_id) !== -1)
                  {
                    $('.warning-message').innerHTML('This user has a slot taken that overlaps with this one!');
                    $('.alert-warning').removeClass('hidden');
                  } else {
                    $('.alert-warning').addClass('hidden');
                    $('.warning-message').innerHTML('False Alarm!');
                  }
                });
            });
        });
    });
});
