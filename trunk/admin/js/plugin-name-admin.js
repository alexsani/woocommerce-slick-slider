(function($) {
	'use strict';

	/**
	 * All of the code for your Dashboard-specific JavaScript source should
	 * reside in this file.
	 * 
	 * Note that this assume you're going to use jQuery, so it prepares the $
	 * function reference to be used within the scope of this function.
	 * 
	 * From here, you're able to define handlers for when the DOM is ready:
	 * 
	 * $(function() {
	 * 
	 * });
	 * 
	 * Or when the window is loaded:
	 *  $( window ).load(function() {
	 * 
	 * });
	 * 
	 * ...and so on.
	 * 
	 * Remember that ideally, we should not attach any more than a single
	 * DOM-ready or window-load handler for any particular page. Though other
	 * scripts in WordPress core, other plugins, and other themes may be doing
	 * this, we should try to minimize doing that in our own work.
	 */
	$(window).load(function() {

		$('#publish').click(function() {
			var form = $("#wooslickcarousel_meta_box");
			$("#wooslickcarousel_meta_box").validate({
				rules : {
					items : {
						required : false,
						number : true } } });
			return false;
		});

		$("#owlc-admin-tab .nav-tab").on('click', function() {
			$("#owlc-admin-tab .nav-tab").removeClass('nav-tab-active')
			$("#owlc-admin-tab > div").addClass('hidden');
			$(this).addClass('nav-tab-active');
			$($(this).attr('href')).removeClass('hidden');
			$(this).focus();
		});

		$("#owlc-responsive-accordion").accordion({ header: "h3", heightStyle: "content" });
		
		//Add new responsive breakpoint
		$("#owlc-add-breakpoint").on('click', function() {
			var breakpoint = $('#owlc-add-breakpoint-value').val(); //get responsive breakpoint to add
			//Check is a good value
			if (breakpoint === "" || isNaN(breakpoint)) {
				alert("Insert a valid value!");
				return false;
			}
			//Create new id
			var newId = "owlc-responsive-" + breakpoint;
			//Check if already exist
			if ($('#' + newId).length > 0) {
				alert("Already exist this breakpoint!");
				return false;
			}
			
			var $newAccordion = $("#owlc-responsive-base").clone(true).toggle();//Clone the basic accordion and add new id
			$newAccordion.attr("id", newId); //Set id of accordion
			$newAccordion.find('a.owlc-responsive-delete').attr('href', breakpoint);
			$newAccordion.find('a.owlc-responsive-add-attribute').attr('href', breakpoint);
			$newAccordion.find('h3 span').text(breakpoint); //Set the title
			$newAccordion.find('.breakpoint-id').val(breakpoint); //Set the breakpoint
			$("#owlc-responsive-accordion").append($newAccordion);

			$("#owlc-responsive-accordion").accordion("refresh").accordion({
				active : -1 }); //Refresh accordion and open the last added
			
			$('#owlc-add-breakpoint-value').val('') // reset value
			
		});
		
		jQuery('a.owlc-responsive-delete').on("click", function(e){
			e.preventDefault();
			var breakpoint = jQuery(this).attr('href');
			jQuery("#owlc-responsive-" + breakpoint).remove();
			jQuery("#owlc-responsive-accordion").accordion("refresh");
		});
		
		jQuery('a.owlc-responsive-add-attribute').on("click", function(e){
			e.preventDefault();
			var breakpoint = jQuery(this).attr('href');
			var $selectedAttr = jQuery(this).prev('select').find(":selected");
			var $attribute = jQuery('#general tr.' + $selectedAttr.val()).clone()
			$attribute.find('[name="'+$selectedAttr.val()+'"]').attr("name", "responsive[" + breakpoint+"]['"+$selectedAttr.val()+"']");
			$attribute.appendTo(jQuery(this).next());
			jQuery("#owlc-responsive-accordion").accordion("refresh");
			$selectedAttr.remove();
		});

	});


})(jQuery);

