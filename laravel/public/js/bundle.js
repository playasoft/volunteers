/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};

/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {

/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId])
/******/ 			return installedModules[moduleId].exports;

/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			exports: {},
/******/ 			id: moduleId,
/******/ 			loaded: false
/******/ 		};

/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);

/******/ 		// Flag the module as loaded
/******/ 		module.loaded = true;

/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}


/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;

/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;

/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "";

/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(0);
/******/ })
/************************************************************************/
/******/ ([
/* 0 */
/***/ function(module, exports, __webpack_require__) {

	__webpack_require__(1);
	__webpack_require__(3);


/***/ },
/* 1 */
/***/ function(module, exports, __webpack_require__) {

	var $ = __webpack_require__(2);

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


/***/ },
/* 2 */
/***/ function(module, exports) {

	(function()
	{
	    ////////////////////////////////
	    // Wetfish Basic
	    // Core functionality

	    // A constructor for public functions
	    var public = wetfish = function(selector)
	    {
	        if (!(this instanceof public))
	        {
	            return new public(selector)
	        }

	        // If the selector is a string
	        if(typeof selector == "string")
	        {
	            // Try matching some elements on the page
	            this.el = this.elements = document.querySelectorAll(selector);
	        }
	        else
	        {
	            // Assume an element was passed (like the value of this in an event)
	            this.el = this.elements = [selector];
	        }

	        this.length = this.el.length;
	        return this;
	    }

	    // Helper function to loop through elements
	    public.prototype.forEach = function(array, callback)
	    {
	        for(var i = 0, l = array.length; i < l; i++)
	        {
	            callback.call(this, i, array[i]);
	        }
	    }

	    // An object literal for private functions
	    var private = { };

	    // Polyfill from MDN for IE support
	    // See: https://developer.mozilla.org/en-US/docs/Web/API/CustomEvent/CustomEvent
	    private.CustomEvent = function(event, params)
	    {
	        params = params || { bubbles: false, cancelable: false, detail: undefined };
	        var evt = document.createEvent('CustomEvent');
	        evt.initCustomEvent(event, params.bubbles, params.cancelable, params.detail);
	        return evt;
	    }

	    private.CustomEvent.prototype = window.Event.prototype;

	    // Private function to determine element height
	    private.height = function(element, mode)
	    {
	        // Special case for the window
	        if(element == window)
	        {
	            var height =
	            {
	                inner: window.innerHeight,
	                outer: window.outerHeight
	            };
	        }
	        else
	        {
	            // Document should actually reference the documentElement
	            if(element == document)
	            {
	                element = document.documentElement;
	            }

	            // Now get the computed style
	            var style = window.getComputedStyle(element);
	            var height =
	            {
	                inner: element.offsetHeight,
	                outer: element.offsetHeight + parseInt(style.marginTop) + parseInt(style.marginBottom)
	            };
	        }

	        // If a valid mode was passed, return that property
	        if(height[mode] !== undefined)
	            return height[mode];

	        // Otherwise return both
	        return height;
	    }


	    // Polyfill from MDN
	    // See: https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Array/isArray
	    if (!Array.isArray)
	    {
	        Array.isArray = function(arg)
	        {
	            return Object.prototype.toString.call(arg) === '[object Array]';
	        };
	    }

	    // Private function to determine element width
	    private.width = function(element, mode)
	    {
	        // Special case for the window
	        if(element == window)
	        {
	            var width =
	            {
	                inner: window.innerWidth,
	                outer: window.outerWidth
	            };
	        }
	        else
	        {
	            // Document should actually reference the documentElement
	            if(element == document)
	            {
	                element = document.documentElement;
	            }

	            // Now get the computed style
	            var style = window.getComputedStyle(element);
	            var width =
	            {
	                inner: element.offsetWidth,
	                outer: element.offsetWidth + parseInt(style.marginLeft) + parseInt(style.marginRight)
	            };
	        }

	        // If a valid mode was passed, return that property
	        if(width[mode] !== undefined)
	            return width[mode];

	        // Otherwise return both
	        return width;
	    }

	    ////////////////////////////////
	    // Check how basic should be exported

	    // Detect if we're in node or a browser
	    if(typeof module !== 'undefined' && module.exports)
	    {
	        // We're in node or a CommonJS compatable environment
	        module.exports = public;
	    }
	    else
	    {
	        // We're in a browser, so put everything into global variables
	        this.$ = public;
	        this.basic = public;
	    }

	    ////////////////////////////////
	    // addClass() - add a class to all matched nodes
	    // usage - $('.selector').addClass('example');

	    public.prototype.addClass = function(className)
	    {
	        this.forEach(this.elements, function(index, element)
	        {
	            var classes = element.className.split(' ');
	            var index = classes.indexOf(className);

	            // Only add a class if it doesn't exist
	            if(index == -1)
	            {
	                classes.push(className);
	                element.className = classes.join(' ');
	            }
	        });
	                
	        return this;
	    }

	    ////////////////////////////////
	    // append('string') - add a string to the end of all currently matched elements
	    // usage - $('body').append('The end!');

	    public.prototype.append = function(content)
	    {
	        // Loop through current elements
	        this.forEach(this.elements, function(index, element)
	        {
	            if(typeof content == "string")
	            {
	                element.innerHTML = element.innerHTML + content;
	            }
	            else
	            {
	                element.appendChild(content.cloneNode(true));
	            }
	        });

	        return this;
	    }

	    ////////////////////////////////
	    // attr('attribute')            - get the value of an attribute on all matched elements
	    // attr('attribute', 'value')   - set the value of an attribute on all matched elements
	    // usage - $('svg text').attr('stroke', 4);

	    public.prototype.attr = function(key, value)
	    {
	        var output = [];
	        
	        // Loop through current elements
	        this.forEach(this.elements, function(index, element)
	        {
	            // If no value is specified, return the current value of the attribute
	            if(value === undefined)
	            {
	                output.push(element.getAttribute(key))
	            }
	            else
	            {
	                // If the value is true, create the attribute without any value
	                if(value === true)
	                {
	                    element.setAttribute(key, '');
	                }
	                // If the value is false, remove the attribute
	                else if(value === false)
	                {
	                    element.removeAttribute(key);
	                }
	                // Otherwise set the value as is
	                else
	                {
	                    element.setAttribute(key, value);
	                }
	            }
	        });

	        if(output.length)
	        {
	            // If only one element was matched, return that value
	            if(output.length == 1)
	            {
	                return output[0];
	            }

	            // Otherwise return an array of matched values
	            return output;
	        }

	        return this;
	    }

	    ////////////////////////////////
	    // clone(deep) - clone an element
	    // usage - var clone = $('.element').clone();

	    public.prototype.clone = function(deep)
	    {
	        if(deep === undefined)
	        {
	            deep = true;
	        }
	        
	        var output = [];

	        this.forEach(this.elements, function(index, element)
	        {
	            output.push(element.cloneNode(deep));
	        });

	        // If we only one element was cloned
	        if(output.length == 1)
	        {
	            // Return only that element
	            return output[0];
	        }

	        // Otherwise, return an array of clones
	        return output;
	    }

	    ////////////////////////////////
	    // data('attribute')            - get the value of a data attribute on all matched elements
	    // data('attribute', 'value')   - set the value of a data attribute on all matched elements
	    // usage - $('svg text').data('stroke', 4);

	    public.prototype.data = function(key, value)
	    {
	        var output = [];
	        
	        // Loop through current elements
	        this.forEach(this.elements, function(index, element)
	        {
	            // Make sure the dataset is an object (for old versions of IE)
	            if(element.dataset === undefined)
	            {
	                element.dataset = {};
	            }
	            
	            // If no value is specified, return the current value of the data attribute
	            if(value === undefined)
	            {
	                // If this key isn't found
	                if(element.dataset[key] === undefined)
	                {
	                    // Check to see if it exists as an attribute (for old versions of IE)
	                    var attr = element.getAttribute('data-' + key);
	                    
	                    if(attr != null)
	                    {
	                        element.dataset[key] = attr;
	                    }
	                }

	                output.push(element.dataset[key])
	            }
	            else
	            {
	                element.dataset[key] = value;
	            }
	        });

	        if(output.length)
	        {
	            // If only one element was matched, return that value
	            if(output.length == 1)
	            {
	                return output[0];
	            }

	            // Otherwise return an array of matched values
	            return output;
	        }

	        return false;
	    }

	    ////////////////////////////////
	    // each(callback) - loop over the list of currently matched elements, calling the callback for each
	    // usage - $('a').each(function(index, element) { console.log(this) };

	    public.prototype.each = function(callback)
	    {
	        // Loop through current elements
	        this.forEach(this.elements, function(index, element)
	        {
	            callback.call(element, index, element);
	        });

	        return this;
	    }

	    ////////////////////////////////
	    // eq() - select something from the list of currently matched elements
	    // usage - $('a').eq(3).addClass('example');

	    public.prototype.eq = function(index)
	    {
	        // If an existing element was found
	        if(typeof this.elements[index] != "undefined")
	        {
	            this.elements = this.el = [this.elements[index]];
	            return this;
	        }

	        // Otherwise unset all matched elements
	        this.elements = this.el = [];
	        return this;
	    }

	    ////////////////////////////////
	    // find() - find a selector within another element
	    // usage - $('nav').find('a').addClass('example');

	    public.prototype.find = function(selector)
	    {
	        // Clear existing elements array
	        var elements = this.elements;
	        this.elements = [];

	        // Loop through the original elements
	        this.forEach(elements, function(index, element)
	        {
	            var children = element.querySelectorAll(selector);

	            // Loop through any matching children and push them to the list of elements
	            this.forEach(children, function(childIndex, childElement)
	            {
	                this.elements.push(childElement);
	            });
	        });

	        // Update the shorthand elements container
	        this.el = this.elements;
	        return this;
	    }

	    ////////////////////////////////
	    // removeClass() - remove a class from all matched nodes
	    // usage - if($('.selector').hasClass('example')) { console.log('wow!'); }

	    public.prototype.hasClass = function(classes, mode)
	    {
	        var classes = classes.split(' ');
	        var match = false;

	        // TODO: Break loop when match is found?
	        this.forEach(this.elements, function(index, element)
	        {
	            // Reset matches between each loop
	            var matches = {};

	            this.forEach(classes, function(index, className)
	            {
	                var classNames = element.className.split(' ');
	                var index = classNames.indexOf(className);

	                if(index != -1)
	                {
	                    matches[className] = true;
	                }
	            });

	            // If this is an inclusive match
	            if(mode == 'or')
	            {
	                if(Object.keys(matches).length)
	                {
	                    match = true;
	                }
	            }

	            // Otherwise, make sure we matched all of the requested classes
	            else
	            {
	                if(Object.keys(matches).length == classes.length)
	                {
	                    match = true;
	                }
	            }
	        });

	        return match;
	    }

	    // Depends on: ./deps/height.js

	    ////////////////////////////////
	    // height() - get the height of a specific element or all matched elements
	    // usage - var height = $('.single-selector').height(); // Returns an object containing the element's inner and outer height
	    // usage - var height = $('.multi-selector').height(); // Returns an array of objects containing the inner and outer height of all matched elements 

	    public.prototype.height = function(mode)
	    {
	        // Default to inner height
	        if(mode === undefined)
	            mode = 'inner';
	        
	        var output = [];

	        this.forEach(this.elements, function(index, element)
	        {
	            output.push(private.height(element, mode));
	        });

	        // If we were only checking the height of one element
	        if(output.length == 1)
	        {
	            // Return only that element's height
	            return output[0];
	        }

	        // Otherwise, return an array of heights
	        return output;
	    }

	    ////////////////////////////////
	    // html('string') - replace the contents of all currently matched elements
	    // usage - $('p').html('<b>Replaced!</b>');

	    public.prototype.html = function(content)
	    {
	        // Loop through current elements
	        this.forEach(this.elements, function(index, element)
	        {
	            if(typeof content == "string")
	            {
	                element.innerHTML = content;
	            }
	            else
	            {
	                element.innerHTML = content.outerHTML;
	            }
	        });

	        return this;
	    }

	    ////////////////////////////////
	    // index() - find the index an element
	    // usage - var index = $('.element').index();

	    public.prototype.index = function()
	    {
	        var output = [];

	        this.forEach(this.elements, function(index, element)
	        {
	            this.forEach(element.parentNode.children, function(index, child)
	            {
	                if(element == child)
	                {
	                    output.push(index);
	                }
	            });
	        });

	        // If we only one element was matched
	        if(output.length == 1)
	        {
	            // Return only that element's index
	            return output[0];
	        }

	        // Otherwise, return an array of indexes
	        return output;
	    }

	    ////////////////////////////////
	    // noConflict() - returns public class
	    // usage - var customVar = basic.noConflict();

	    public.prototype.noConflict = function()
	    {
	        return public;
	    }

	    // Depends on: ./off.js

	    ////////////////////////////////
	    // off() - remove an event that is only fired when a child selector is matched
	    // usage - $('.selector').off('click', '.selector', callback);

	    private.eventCallbacks = [];
	    private.eventFunctions = [];

	    private.offSelector = function(events, selector, callback)
	    {
	        events = events.split(' ');

	        // Look for the index of this callback
	        var functionIndex = private.eventCallbacks.indexOf(callback);

	        // If the function is found
	        if(functionIndex > -1)
	        {
	            this.forEach(events, function(index, event)
	            {
	                this.forEach(this.elements, function(index, element)
	                {
	                    element.removeEventListener(event, private.eventFunctions[functionIndex]);
	                });
	            });

	            // Remove functions from arrays
	            delete private.eventCallbacks[functionIndex];
	            delete private.eventFunctions[functionIndex];
	        }
	        
	        return this;
	    }

	    ////////////////////////////////
	    // off() - remove an event from all matched elements
	    // usage - $('.selector').off('click', callback);

	    public.prototype.off = function(events, callback)
	    {
	        // If more than two arguments are passed, handle this event using offSelector
	        if(arguments.length > 2 && private.offSelector !== undefined)
	        {
	            return private.offSelector.apply(this, arguments);
	        }
	        
	        events = events.split(' ');

	        this.forEach(events, function(index, event)
	        {
	            this.forEach(this.elements, function(index, element)
	            {
	                element.removeEventListener(event, callback);
	            });
	        });
	        
	        return this;
	    }

	    // Depends on: ./on.js

	    ////////////////////////////////
	    // on() - bind an event that is only fired when a child selector is matched
	    // usage - $('body').on('click', '.selector' function() { console.log('you clicked!'); });

	    private.eventCallbacks = [];
	    private.eventFunctions = [];

	    private.onSelector = function(events, selector, callback)
	    {
	        events = events.split(' ');

	        private.eventCallbacks.push(callback);
	        var functionIndex = private.eventFunctions.push(function(event)
	        {
	            var children = document.querySelectorAll(selector);

	            for(var i = 0, l = children.length; i < l; i++)
	            {
	                var child = children[i];

	                if(event.target == child)
	                {
	                    callback.call(event.target, event);
	                }
	            }
	        });

	        // Subtract 1 because push returns the array length
	        functionIndex--;

	        this.forEach(events, function(index, event)
	        {
	            this.forEach(this.elements, function(index, element)
	            {
	                element.addEventListener(event, private.eventFunctions[functionIndex]);
	            });
	        });

	        return this;
	    }

	    ////////////////////////////////
	    // on() - bind an event to all matched elements
	    // usage - $('.selector').on('click', function() { console.log('you clicked!'); });

	    public.prototype.on = function(events, callback)
	    {
	        // If more than two arguments are passed, handle this event using onSelector
	        if(arguments.length > 2 && private.onSelector !== undefined)
	        {
	            return private.onSelector.apply(this, arguments);
	        }
	        
	        events = events.split(' ');

	        this.forEach(events, function(index, event)
	        {
	            this.forEach(this.elements, function(index, element)
	            {
	                element.addEventListener(event, callback);
	            });
	        });

	        return this;
	    }

	    ////////////////////////////////
	    // parent() - get the direct parent of all matched elements
	    // usage - $('.selector').parent();

	    public.prototype.parent = function()
	    {
	        var elements = [];

	        this.forEach(this.elements, function(index, element)
	        {
	            elements[index] = element.parentNode;
	        });

	        this.elements = this.el = elements;
	        return this;
	    }

	    ////////////////////////////////
	    // parents() - loop through all parents until a matching selector is found
	    // usage - $('.selector').parents('.container');

	    private.parent = function(element, parent)
	    {
	        if(!element)
	        {
	            return false;
	        }

	        if(element.parentNode == parent)
	        {
	            return parent;
	        }
	        else
	        {
	            return private.parent(element.parentNode, parent);
	        }
	    }

	    public.prototype.parents = function(selector)
	    {
	        var elements = [];
	        var parents = document.querySelectorAll(selector);

	        this.forEach(this.elements, function(index, element)
	        {
	            this.forEach(parents, function(index, parent)
	            {
	                var matched = private.parent(element, parent);

	                if(matched)
	                {
	                    elements.push(parent);
	                }
	            });
	        });

	        this.elements = this.el = elements;
	        return this;
	    }

	    ////////////////////////////////
	    // position(relative)   - get the position of a specific element or all matched elements
	    //                      - optionally specify if the position should be relative to the 'viewport' or the 'page' (default) 
	    // usage - var position = $('.single-selector').position(); // Returns an object containing the element's inner and outer position
	    // usage - var position = $('.multi-selector').position(); // Returns an array of objects containing the inner and outer position of all matched elements 

	    public.prototype.position = function(relative)
	    {
	        var output = [];
	        relative = relative || 'page';

	        this.forEach(this.elements, function(index, element)
	        {
	            var rect = element.getBoundingClientRect();

	            // DOM Rects are immutable, so we have to copy the data
	            var position =
	            {
	                top: rect.top,
	                left: rect.left,
	                right: rect.right,
	                bottom: rect.bottom
	            };

	            if(relative == "page")
	            {
	                // Add the scroll position to top / left
	                position.left += window.scrollX;
	                position.top += window.scrollY;
	            }
	            
	            output.push(position);
	        });

	        // If we were only checking the position of one element
	        if(output.length == 1)
	        {
	            // Return only that element's position
	            return output[0];
	        }

	        // Otherwise, return an array of positions
	        return output;
	    }

	    ////////////////////////////////
	    // prepend('string') - add a string to the beginning of all currently matched elements
	    // usage - $('body').prepend('Dear user,');

	    public.prototype.prepend = function(content)
	    {
	        // Loop through current elements
	        this.forEach(this.elements, function(index, element)
	        {
	            if(typeof content == "string")
	            {
	                element.innerHTML = content + element.innerHTML;
	            }
	            else
	            {
	                element.insertBefore(content.cloneNode(true), element.firstChild);
	            }
	        });

	        return this;
	    }

	    ////////////////////////////////
	    // prop('property')         - get a property of all matched elements
	    // prop('key', 'value')     - set the property of all matched elements
	    // usage - $('.checkbox').prop('checked', true);

	    public.prototype.prop = function(key, value)
	    {
	        var output = [];
	        
	        // Loop through current elements
	        this.forEach(this.elements, function(index, element)
	        {
	            // If no value is specified, return the current property of the element
	            if(value === undefined)
	            {
	                output.push(element[key]);
	            }
	            // Otherwise, set the property 
	            else
	            {
	                element[key] = value;
	            }
	        });

	        if(output.length)
	        {
	            // If only one element was matched, return that value
	            if(output.length == 1)
	            {
	                return output[0];
	            }

	            // Otherwise return an array of matched values
	            return output;
	        }

	        return this;
	    }

	    // Depends on: ./deps/customEvent.js

	    ////////////////////////////////
	    // ready() - wait for the page to load before firing callback
	    // usage - $(document).ready, function() { console.log('Page ready!'); });

	    public.prototype.ready = function(callback)
	    {
	        this.forEach(this.elements, function(index, element)
	        {
	            element.addEventListener('ready', callback);
	        });
	    }

	    // Native dom loaded event
	    document.addEventListener('DOMContentLoaded', function()
	    {
	        // Create a custom event with a nicer name
	        var ready = new private.CustomEvent('ready');

	        // Trigger it!
	        document.dispatchEvent(ready);
	    })

	    ////////////////////////////////
	    // remove() - remove all currently matched elements
	    // usage - $('p').remove();

	    public.prototype.remove = function()
	    {
	        // Loop through current elements
	        this.forEach(this.elements, function(index, element)
	        {
	            element.parentNode.removeChild(element);
	        });

	        return this;
	    }

	    ////////////////////////////////
	    // removeClass() - remove a class from all matched nodes
	    // usage - $('.selector').removeClass('example');

	    public.prototype.removeClass = function(className)
	    {
	        this.forEach(this.elements, function(index, element)
	        {
	            var classes = element.className.split(' ');
	            var index = classes.indexOf(className);

	            // Only remove a class if it exists
	            if(index != -1)
	            {
	                classes.splice(index, 1);
	                element.className = classes.join(' ');
	            }
	        });

	        return this;
	    }

	    ////////////////////////////////
	    // replace('string') - replace all currently matched elements with the string
	    // usage - $('.start').replace("<div class='stop'>Replaced!</div>');

	    public.prototype.replace = function(content)
	    {
	        // Loop through current elements
	        this.forEach(this.elements, function(index, element)
	        {
	            if(typeof content == "string")
	            {
	                element.outerHTML = content;
	            }
	            else
	            {
	                element.outerHTML = content.outerHTML;
	            }
	        });

	        return this;
	    }

	    ////////////////////////////////
	    // scroll() - get the scroll position of a specific element or all matched elements
	    // usage - var scroll = $('.single-selector').scroll(); // Returns an object containing the element's scrollTop and scrollLeft
	    // usage - var scroll = $('.multi-selector').scroll(); // Returns an array of objects containing the scrollTop and scrollLeft of all matched elements 

	    public.prototype.scroll = function()
	    {
	        var output = [];

	        this.forEach(this.elements, function(index, element)
	        {
	            // The window is a special case that doesn't have scrollTop / scrollLeft properties
	            if(element == window)
	            {
	                var scroll =
	                {
	                    top: element.pageYOffset,
	                    left: element.pageXOffset
	                };
	            }
	            else
	            {
	                var scroll =
	                {
	                    top: element.scrollTop,
	                    left: element.scrollLeft
	                };
	            }
	            
	            output.push(scroll);
	        });

	        // If we were only checking the position of one element
	        if(output.length == 1)
	        {
	            // Return only that element's position
	            return output[0];
	        }

	        // Otherwise, return an array of positions
	        return output;
	    }

	    // Depends on: ./deps/width.js, ./deps/height.js

	    ////////////////////////////////
	    // size() - get the size of a specific element or all matched elements
	    // usage - var size = $('.single-selector').size(); // Returns an object containing the element's height and width
	    // usage - var size = $('.multi-selector').size(); // Returns an array of objects containing the height and width of all matched elements 

	    public.prototype.size = function(mode)
	    {
	        // Default to inner size
	        if(mode === undefined)
	            mode = 'inner';
	        
	        var output = [];

	        this.forEach(this.elements, function(index, element)
	        {
	            var size =
	            {
	                height: private.height(element, mode),
	                width: private.width(element, mode)
	            };
	            
	            output.push(size);
	        });

	        // If we were only checking the size of one element
	        if(output.length == 1)
	        {
	            // Return only that element's size
	            return output[0];
	        }

	        // Otherwise, return an array of sizes
	        return output;
	    }

	    ////////////////////////////////
	    // style() - get or set styles for matched elements
	    // usage - $('a').style({'color': 'red', 'font-size': '30pt'}); // Set the style for all matched elements to 30pt and red
	    // usage - $('.unique-selector').style('color');                // Get the color attribute for a single element
	    // usage - $('a').style('color');                               // Get an array of the color attributes for all matched elements

	    public.prototype.style = function(style)
	    {
	        // If we're setting an object of styles
	        if(typeof style == "object")
	        {
	            var keys = Object.keys(style);

	            this.forEach(keys, function(key, property)
	            {
	                this.forEach(this.elements, function(index, element)
	                {
	                    element.style[property] = style[property];
	                });
	            });

	            return this;
	        }

	        // Otherwise, we must be getting the value of a property
	        else if(typeof style == "string")
	        {
	            var output = [];

	            this.forEach(this.elements, function(index, element)
	            {
	                var current = window.getComputedStyle(element);
	                output.push(current[style]);
	            });

	            // If we were only checking the style of one element
	            if(output.length == 1)
	            {
	                // Return only that element's style
	                return output[0];
	            }

	            // Otherwise, return an array of styles
	            return output;
	        }
	    }

	    ////////////////////////////////
	    // text('string') - replace the contents of all currently matched elements with text
	    //                - this differs from .html() because .text() prevents html from being added to the page
	    // usage - $('p').text('<b>Replaced!</b>');

	    public.prototype.text = function(content)
	    {
	        // Loop through current elements
	        this.forEach(this.elements, function(index, element)
	        {
	            element.innerHTML = content.replace(/</g, '&lt;').replace(/>/g, '&gt;');
	        });

	        return this;
	    }

	    // Depends on: ./deps/isArray.js

	    ////////////////////////////////
	    // transform() - convenience function for handling CSS transforms
	    // usage - $('.selector').transform('rotate', '20deg');
	    // usage - $('.selector').transform({rotate: '20deg', scaleX: '2'});

	    private.transform =
	    {
	        // Private function for saving new transform properties
	        save: function(element, options)
	        {
	            // Make sure the transform property is an object
	            if(typeof element.transform != "object")
	            {
	                element.transform = {};
	            }

	            // If we have an object of options
	            if(typeof options[0] == "object")
	            {
	                // Loop through object properties and save their values
	                var keys = Object.keys(options[0]);

	                keys.forEach(function(property)
	                {
	                    element.transform[property] = options[0][property];
	                });
	            }
	            
	            // Otherwise, loop through all of the options to find values
	            else
	            {
	                var property = options[0];
	                var args = [];

	                for(var i = 1, l = options.length; i < l; i++)
	                {
	                    args.push(options[i]);
	                }

	                element.transform[property] = args;
	            }
	        },

	        // Private function for updating an element on the page
	        update: function(element)
	        {
	            // Loop through all saved transform functions to generate the style text
	            var funcs = Object.keys(element.transform);
	            var style = [];

	            funcs.forEach(function(func)
	            {
	                var args = element.transform[func];

	                // If we have an array of arguments, join them with commas
	                if(Array.isArray(args))
	                {
	                    args = args.join(', ');
	                }
	                
	                style.push(func + "("+args+") ");
	            });

	            // Update the element
	            element.style['transform'] = style.join(" ");
	            element.style['-webkit-transform'] = style.join(" ");
	        }
	    }

	    public.prototype.transform = function()
	    {
	        var options = arguments;
	        
	        this.forEach(this.elements, function(index, element)
	        {
	            // Add the new transform data
	            private.transform.save(element, options);

	            // Update the element on the page
	            private.transform.update(element);
	        });

	        return this;
	    }

	    // Depends on: ./deps/customEvent.js

	    ////////////////////////////////
	    // trigger() - trigger an event on matched elements
	    // usage - $('.some-button').trigger('click');                      // Trigger the click event on .some-button
	    // usage - $('.some-button').trigger('click', {custom: 'data'});    // Trigger click with control over bubbling and arbitrary data

	    public.prototype.trigger = function(event, data)
	    {
	        if(data !== undefined)
	        {
	            var params = {};

	            // Check if any special options exist
	            if(data.bubbles)
	            {
	                params.bubbles = data.bubbles;
	                delete data.bubbles;
	            }

	            if(data.cancelable)
	            {
	                params.cancelable = data.cancelable;
	                delete data.cancelable;
	            }

	            // Put remaining data into the event details
	            if(Object.keys(data).length)
	            {
	                params.detail = data;
	            }
	        }
	        
	        // Create the event
	        var event = new private.CustomEvent(event, params);

	        // Loop through matched elements
	        this.forEach(this.elements, function(index, element)
	        {
	            // Dispatch it!
	            element.dispatchEvent(event);
	        });
	    }

	    ////////////////////////////////
	    // value()                  - get the value of an input on all matched elements
	    // value('something')       - set the value of an input on all matched elements
	    // usage - $('textarea').value('this is some text!');

	    public.prototype.value = function(value)
	    {
	        var output = [];
	        
	        // Loop through current elements
	        this.forEach(this.elements, function(index, element)
	        {
	            // If no value is specified, return the current value of the input
	            if(value === undefined)
	            {
	                output.push(element.value);
	            }
	            // Otherwise, set the value 
	            else
	            {
	                element.value = value;
	            }
	        });

	        if(output.length)
	        {
	            // If only one element was matched, return that value
	            if(output.length == 1)
	            {
	                return output[0];
	            }

	            // Otherwise return an array of matched values
	            return output;
	        }

	        return this;
	    }

	    // Depends on: ./deps/width.js

	    ////////////////////////////////
	    // width() - get the width of a specific element or all matched elements
	    // usage - var width = $('.single-selector').width(); // Returns an object containing the element's inner and outer width
	    // usage - var width = $('.multi-selector').width(); // Returns an array of objects containing the inner and outer width of all matched elements 

	    public.prototype.width = function(mode)
	    {
	        // Default to inner width
	        if(mode === undefined)
	            mode = 'inner';

	        var output = [];

	        this.forEach(this.elements, function(index, element)
	        {
	            output.push(private.width(element, mode));
	        });

	        // If we were only checking the width of one element
	        if(output.length == 1)
	        {
	            // Return only that element's width
	            return output[0];
	        }

	        // Otherwise, return an array of widths
	        return output;
	    }
	})();

/***/ },
/* 3 */
/***/ function(module, exports, __webpack_require__) {

	var $ = __webpack_require__(2);

	$(document).ready(function()
	{
	    $('.shift-type').on('change', function()
	    {
	        if($(this).value() == 'all')
	        {
	            $('.shift.start-date, .shift.end-date').addClass('hidden');
	        }
	        else if($(this).value() == 'some')
	        {
	            $('.shift.start-date, .shift.end-date').removeClass('hidden');
	        }
	        else if($(this).value() == 'one')
	        {
	            $('.shift.start-date').removeClass('hidden');
	            $('.shift.end-date').addClass('hidden');
	        }
	    });
	});


/***/ }
/******/ ]);