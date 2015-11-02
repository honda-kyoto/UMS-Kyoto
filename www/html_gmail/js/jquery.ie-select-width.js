/*global jQuery jQuery window */
/*jslint white: true, browser: true, onevar: true, undef: true, nomen: true, eqeqeq: true, bitwise: true, regexp: true, newcap: true, immed: true, strict: true */

/**
 * jQuery workaround for the cropped option in set width select.
 *
 * Copyright (c) 2010 Ewen Elder
 * Dual licensed under the MIT and GPL licenses:
 * http://www.opensource.org/licenses/mit-license.php
 * http://www.gnu.org/licenses/gpl.html
 *
 * @author: Ewen Elder <glomainn at yahoo dot co dot uk> <ewen at jainaewen dot com>
 * @version: 0.1.1 beta
**/

'use strict';
jQuery.fn.ieSelectWidth = function (options)
{
	var defaults, selectFix, elements;

	elements = jQuery(this);


	defaults = {
		width : null,
		containerClassName : 'ie-select-width-container',
		overlayClassName : 'ie-select-width-overlay',
		overlayCSS : null,
		containerCSS : null
	};


	// Extend the defaults.
	options = jQuery.extend(defaults, options);


	selectFix = {

		/**
		 * Initiate the class, check if the browser is IE, if not then return false, check if the element has an ID,
		 * if not then apply one, add a new container around the element and set it's CSS, add event listeners.
		**/
		init : function ()
		{
			var self = this;


			// If not IE, return false.
			if (!document.all)
			{
				return false;
			}


			jQuery.each
			(
				elements,
				function ()
				{
					var element = jQuery(this);


					// If the element has multiple set, or has a size higher than 0, continue to the next element.
					if (element.attr('multiple') || element.attr('size') > 0)
					{
						return false;
					}

					// If this element doesn't have an ID, apply one.
					if (!element.attr('id').length)
					{
						element.attr('id', String((new Date()).getTime()).replace(/\D/gi, '').substr(8));
					}


					// If the width option has been passed, then apply and store it,
					// if the width is set via CSS, then store than instead.
					if (options.width !== null)
					{
						element.css('width', options.width + 'px');
					}

					element.data('origWidth', element.outerWidth());


					// Add the container.
					self.addContainer(element);


					// Add the overlay.
					self.addOverlay(element);


					// Remove any positioning attributes from the select element.
					jQuery(element).css
					({
						position : 'relative',
						top : 'auto',
						left : 'auto',
						bottom : 'auto',
						right : 'auto',
						margin : '0'
					});
				}
			);


			// Event listener for opening the select.
			jQuery(elements).bind
			(
				'dblclick mousedown change blur',
				function (event)
				{
					self.openSelect(event);
				}
			);


			// Event listener for setting the various classes depending on the current event.
			jQuery(elements).bind
			(
				'mousedown mouseup mouseover mouseout blur change',
				function (event)
				{
					self.setClass(event);
				}
			);
		},



		addContainer : function (element)
		{
			var browserClass;

			// Which browser??
			if (!window.XMLHttpRequest)
			{
				browserClass = 'ie6';
			}

			else if (window.XMLHttpRequest && !document.documentMode)
			{
				browserClass = 'ie7';
			}

			else if (document.documentMode)
			{
				browserClass = 'ie8';
			}

			// Add a container around the element.
			element.after('<span id="' + element.attr('id') + '-container" class="' + options.containerClassName + ' ' + browserClass + '"></span>');
			element.next().append(element);


			// Set the container's CSS.
			element.parent().css
			({
				position : element.css('position') === 'static' ? 'relative' : element.css('position'),
				display : 'block',
				float : 'left',
				top : element.css('top'),
				right : element.css('right'),
				bottom : element.css('bottom'),
				left : element.css('left'),
				overflow : 'hidden',
				width : element.outerWidth() + 'px',
				margin : (element.css('margin-top') !== 'auto' ? element.css('margin-top') : '0') + ' ' +
						 (element.css('margin-right') !== 'auto' ? element.css('margin-right') : '4') + ' ' +
						 (element.css('margin-bottom') !== 'auto' ? element.css('margin-bottom') : '0') + ' ' +
						 (element.css('margin-left') !== 'auto' ? element.css('margin-left') : '0')
			});


			// Apply container custom CSS.
			if (options.containerCSS !== null)
			{
				element.parent().css(options.containerCSS);
			}
		},



		/**
		 * Add the overlay, apply bgiframe if present, add event listener, set the overlays's CSS,
		**/
		addOverlay : function (element)
		{
			var borderBottom, borderRight, borderTop, childWidth, elementId, height, left, margin, overlay, overlayId, paddingRight, paddingTop, width;

			elementId = element.attr('id');
			overlayId = elementId + '-' + options.overlayClassName;


			// Add the overlay.
			element.after('<a id="' + overlayId + '" class="' + options.overlayClassName + '"><span></span></a>');


			// Get the overlay.
			overlay = jQuery('a#' + overlayId);


			// Check for bgiframe for IE 6.
			if (!window.XMLHttpRequest && (jQuery.fn.bgIframe || jQuery.fn.bgiframe))
			{
				overlay.bgiframe();
			}


			// If the overlay is clicked, then the element will loose focus, set focus back to the element.
			overlay.bind
			(
				'mousedown',
				function ()
				{
					setTimeout
					(
						function ()
						{
							element.focus();
						},
						1
					);
				}
			);


			// Get styles for position and size calculations.
			childWidth = overlay.children('span').width();
			borderTop = element.css('border-top-style') !== 'none' ? +element.css('border-top-width').replace('px', '') : 0;
			borderRight = element.css('border-right-style') !== 'none' ? +element.css('border-right-width').replace('px', '') : 0;
			borderBottom = element.css('border-bottom-style') !== 'none' ? +element.css('border-bottom-width').replace('px', '') : 0;
			paddingTop = +element.css('padding-top').replace('px', '');
			paddingRight = +element.css('padding-right').replace('px', '');
			left = element.outerWidth() - childWidth;
			width = childWidth;
			height = element.outerHeight();


			// CSS for IE8+ to incorporate border padding etc
			// (the overlay container has to be the full inner height of the select element in order to replicate
			//  the border-right, which is pushed to the right when the select element's width is set to auto.)
			if (document.documentMode)
			{
				// Compensate for border width.
				if (borderRight > 0)
				{
					left = left - (borderRight + paddingRight);
					borderRight = borderRight + 'px ' + element.css('border-right-style') + ' ' + element.css('border-right-color');
				}


				// Compensate for border height.
				if (borderTop > 0 || borderBottom > 0)
				{
					height = height - (borderTop + borderBottom);
				}


				// Compensate for padding.
				if (paddingRight > 0)
				{
					width = (childWidth + paddingRight);
				}


				margin = borderTop + 'px 0';


				overlay.children('span').css
				({
					margin : paddingTop + 'px 0'
				});
			}


			// Position the overlay to sit where the select's arrow thingie is.
			overlay.css
			({
				position : 'absolute',
				display : 'none',
				top : element.position().top + 'px',
				left : left + 'px',
				width : width + 'px',
				height : height + 'px',
				margin : margin,
				borderRight : borderRight
			});


			// Apply overlay custom CSS.
			if (options.overlayCSS !== null)
			{
				overlay.css(options.overlayCSS);
			}
		},



		/**
		 * Open the element and display the overlay.
		**/
		openSelect : function (event)
		{
			var element, elementId, offset, overlay, pageX, pageY, type; // element, id, offset, overlay, pageX, pageY, type

			element = jQuery(event.target);
			elementId = element.attr('id');
			offset = element.offset();
			overlay = jQuery('a#' + elementId + '-' + options.overlayClassName);
			pageX = event.pageX;
			pageY = event.pageY;
			type = event.type;


			// Double clicks eventually cause the select box to open without applying the workaround,
			// this causes it to close again, although you can still it open and close.
			if (type === 'dblclick')
			{
				element.css
				({
					width : element.data('origWidth') + 'px'
				});
			}

			// If the event is blur, or change then reset the element back to normal,
			// if it is mousedown, and the element's overlay exists, AND the mousedown event happenned on the currently selected option, then reset the element back to normal,
			// ignore the mousedown event in any other circumstances.
			if (type === 'change' || type === 'blur' || (type === 'mousedown' && overlay.css('display') === 'block' && offset.left < pageX && (offset.left + element.data('origWidth')) > pageX && offset.top < pageY && (offset.top + element.outerHeight()) > pageY))
			{
				return this.closeSelect(event);
			}


			// Show the overlay.
			if (overlay.css('display') === 'none')
			{
				overlay.css('display', 'block');
			}


			// Set the select's width to auto.
			if (!element.data('ignore'))
			{
				element.css('width', 'auto');


				// If the select's auto width is less than it's original width, then set it back to it's original width
				// and add a flag so that there are no future attempts to resize this element.
				if (element.outerWidth() < element.parent().innerWidth())
				{
					element.css('width', element.data('origWidth') + 'px');
					element.data('ignore', true);
				}
			}
		},


		/**
		 * Hide the overlay, and restore the select element's width.
		**/
		closeSelect : function (event)
		{
			var element = jQuery(event.target);

			element.siblings('a.' + options.overlayClassName + '').css('display', 'none');

			if (!element.data('ignore'))
			{
				setTimeout
				(
					function ()
					{
						element.css
						({
							width : element.data('origWidth') + 'px'
						});
					},
					1
				);
			}
		},


		/**
		 * Apply class names to represent the current event state, hover, mousedown etc.
		**/
		setClass : function (event)
		{
			var element, offset, overlay, pageX, pageY, type;

			element = jQuery(event.target);
			offset = element.offset();
			overlay = jQuery('a#' + element.attr('id') + '-' + options.overlayClassName);
			pageX = event.pageX;
			pageY = event.pageY;
			type = event.type;

			if (!overlay.length)
			{
				return false;
			}

			if (type === 'mousedown' && offset.left < pageX && (offset.left + element.data('origWidth')) > pageX && offset.top < pageY && (offset.top + element.outerHeight()) > pageY)
			{
				overlay.removeClass().addClass(options.overlayClassName + ' ' + options.overlayClassName + '-active');
			}

			else if (type === 'mouseover')
			{
				overlay.removeClass().addClass(options.overlayClassName + ' ' + options.overlayClassName + '-hover');
			}

			else if (type === 'mouseup' || type === 'mouseout' || type === 'blur' || type === 'change')
			{
				overlay.removeClass().addClass(options.overlayClassName);
			}
		}
	};

	selectFix.init();
};