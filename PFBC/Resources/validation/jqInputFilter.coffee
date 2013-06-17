# jQuery Input Filter plugin
# Limited keypress input

$ = jQuery

$.fn.extend
	jqInputFilter: (options) ->
		# Default settings
		settings =
			numberClass: 'numeric'
			integerClass: 'integer'
			posNumberClass: 'positive'
			posIntegerClass: 'positive-integer'
			debug: true

		# Merge default settings with options.
		settings = $.extend settings, options

		# Bind event
		bindEvent = ($el) ->
			$el.on(
				"keydown"
				"input.#{settings.posNumberClass}"
				(e) ->
					if not (e.which is 8 or e.which is 9 or e.which is 17 or e.which is 46 or (e.which >= 35 && e.which <= 40) or (e.which >= 48 && e.which <= 57) or (e.which >= 96 && e.which <= 105) or (e.which is 190) or (e.which is 110))
						e.preventDefault()     # Prevent character input

					val = $(this).val();
					if ( (e.which == 190 || e.which == 110) && ( ! val || /[\.]/g.test(val) ))
						e.preventDefault()     # Prevent character input
			)

			$el.on(
				"keydown"
				"input.#{settings.posIntegerClass}"
				(e) ->
					if not (e.which is 8 or e.which is 9 or e.which is 17 or e.which is 46 or (e.which >= 35 && e.which <= 40) or (e.which >= 48 && e.which <= 57) or (e.which >= 96 && e.which <= 105) )
						e.preventDefault()     # Prevent character input

					val = $(this).val();
					if ( (e.which == 190 || e.which == 110) && ( ! val || /[\.]/g.test(val) ))
						e.preventDefault()     # Prevent character input
			)

		# Simple logger.
		log = (msg) ->
			console?.log msg if settings.debug

		# enumerate through the elements
		return @each ()->
			bindEvent $(this)
