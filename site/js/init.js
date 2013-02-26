jQuery.noConflict ();

jQuery (
	function ($)
	{
		var $iframe			= $('<iframe src="/" width="1" height="1"></iframe>');
		var $form			= $('#booking-form-form');
		
		$form.validate ({
				submitHandler: function () {
					$.post ('/', $form.serialize (),
						function (data) {
							var $data	= $(data);
							var m		= $data.find ('table.contentpaneopen td').html ();
							
							$('<div>').append ($data).appendTo ('body').remove ();
							
							$iframe.appendTo ('body');
							
							$steps.filter ('.success').html (m);
							
							
							
							
						}
					);
				}
			}
		);
		
		var optCancel		= { 'Annuleren': function () { $dialog.dialog ('close'); } };
		var optStep2		= { 'Volgende stap': function () {
				if ($form.valid ()) {
					$steps.filter (':visible').hide ().next ().show ();
					$dialog.dialog ('option', 'buttons', $.extend ({}, optCancel, optPrevious1, optStep3));
				}
			}
		};
		var optStep3		= { 'Reserveren': function () {
				$steps.filter (':visible').hide ().next ().show ();
				$dialog.dialog ('option', 'buttons', $.extend ({}, optFinish));
				$form.submit ();				
			}
		};
		var optFinish		= { 'Sluiten': function () {
				$steps.filter (':visible').hide ().next ().show ();
				$dialog.dialog ('close');
			}
		};
		var optPrevious1		= { 'Vorige stap': function () {
				$steps.filter (':visible').hide ().prev ().show ();
				
				
				$dialog.dialog ('option', 'buttons', $.extend ({}, optCancel, optStep2));
			}
		};
		var optPrevious2		= { 'Vorige stap': function () {
				$steps.filter (':visible').hide ().prev ().show ();
				
				
				$dialog.dialog ('option', 'buttons', $.extend ({}, optCancel, optPrevious1, optStep3));
			}
		};
		
		
		var bookingModule	= $('#booking-module');
		var $type			= bookingModule.find ('select.type');
		var bookingDate		= bookingModule.find ('.booking-date');
		var $dialog			= bookingModule.find ('.booking-form').dialog ({
				autoOpen: false,
				modal: true,
				title: 'Reserveringen bij de Papillon',
				width: 450,
				buttons: $.extend ({}, optCancel, optStep2)
			}
		);
		var bookingForm		= $dialog;
		
		var $steps			= $dialog.find ('.step');
		
		var $persons		= $dialog.find ('input.persons');
		
		var tmpl			= '<tr class="leeftijd"><td><strong>Persoon {nr}</strong></td><td><input class="required" name="leeftijd[{nr}]" type="text" /></td></tr>'
		
		var $personspointer	= $('tr.leeftijdpointer');
		$persons.bind ('blur',
			function () {
				$dialog.find ('tr.leeftijd').remove ();
				var i	= 1;
				
				var c	= 0;
				
				$persons.map (
					function () {
						var val	= $(this).val ();
						if (val != '' && !isNaN (val)) {
							c	+= parseInt (val, 10);
						}
					}
				);
				
				var html	= [];
				
				for ( ; i <= c; i++) {
					html.push (tmpl.replace (/{nr}/g, i));
				}
				
				$(html.join ('')).insertAfter ($personspointer);
				
			}
		);
		
		
		var bookingResult	= bookingModule.find ('.booking-result').hide ();

		var dates	= bookingModule.find ('input.datepicker').datepicker ({
			minDate: new Date (),
			showOtherMonths: true,
			dateFormat: 'dd-mm-yy',
			selectOtherMonths: true,
			changeMonth: true,
			numberOfMonths: 2,
			onSelect: function (selectedDate)
			{
				var $this		= $(this);
				var option		= $this.hasClass ('from') ? "minDate" : "maxDate";
				var instance	= $this.data("datepicker");
				var date		= $.datepicker.parseDate(instance.settings.dateFormat || $.datepicker._defaults.dateFormat, selectedDate, instance.settings);

				dates.not (this).datepicker ("option", option, date);
			}
		}).last ().val ('');
				
		$('.open-book-form').button ({icon: 'ui-icon-suitcase'}).click (
			function ()
			{
				__bookingAvailability ();
			}
		);
		
		function __bookingAvailability ()
		{
			$form[0].reset ();
			$steps.hide ();
			$steps.first ().show ();
			
			
			var type	= $type;
			var from	= bookingModule.find ('input.datepicker.from');
			var to		= bookingModule.find ('input.datepicker.to');

			if (from.val () === '' || to.val () === '' || type.val () === '') {
				return false;
			}

			var _from	= from.datepicker ('getDate').toString ();
			var _to		= to.datepicker ('getDate').toString ();
			
			bookingForm.dialog ('open');
		
			
			
			// nieuw
				if (type.val () == 'Bungalow') {
				$dialog.find ('table.arrangement').hide ();
				$dialog.find ('table.nobungalow').hide ();
				$dialog.find ('table.bungalow').show ();
			} else {
				$dialog.find ('table.nobungalow').show ();
				$dialog.find ('table.bungalow').hide ();
				if (type.val() == 'Kampeerplaats'){
					$dialog.find ('.kampeerplaats').show ();
				} else {
					$dialog.find ('table.nobungalow').hide ();
					$dialog.find ('table.bungalow').hide ();
					$dialog.find ('table.arrangement').show ();
				}
			}
			// einde nieuw
			
			$('td.aankomstdatum').text (from.val ());
			$('td.vertrekdatum').text (to.val ());
			$('td.staanplaats').text (type.find (':selected').text ());
			$('input.aankomstdatum').val (from.val ());
			$('input.vertrekdatum').val (to.val ());
			$('input.staanplaats').val (type.find (':selected').text ());
			
			return true;
		}
	}
);