const $ = require('wetfish-basic');

// Class to highlight the current time based on your mouse position
var Highlight = function(wrap)
{
    this.wrap = wrap;
    this.bind();
}

// Bind events
Highlight.prototype.bind = function()
{
    // Save the current scope
    var current = this;

    $(this.wrap).on('mouseenter', function(event)
    {
        // Save the position of the time grid
        current.position = $(current.wrap).find('.times').position();
        current.margin = parseInt($(current.wrap).find('.times').style('padding-left')) + 2;
        current.width = $(current.wrap).find('.time').eq(0).width();

        // Save the current mouse position of the user
        current.cursor = event.clientX;

        current.clear();
        current.check();
    });

    $(this.wrap).on('mousemove', function(event)
    {
        current.cursor = event.clientX;

        current.clear();
        current.check();
    });

    $(this.wrap).on('mouseleave', function(event)
    {
        current.clear();
    });
}

// Clear any previously active times
Highlight.prototype.clear = function()
{
    $(this.wrap).find('.time').removeClass('active');
}

// Figure out where the current position is based on the size of the time grid
Highlight.prototype.check = function()
{
    // Offset the cursor position based on the grid position
    var cursor = this.cursor - this.position.left - this.margin;

    // The index of the time element
    var index = Math.floor(cursor / this.width);

    $(this.wrap).find('.times .time').eq(index).addClass('active');
    $(this.wrap).find('.background .time').eq(index).addClass('active');
}

module.exports = Highlight;
