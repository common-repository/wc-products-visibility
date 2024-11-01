(function (settings) {
	class RulesTable
	{
		/**
		 * constructor
		 *
		 * @param {object} settings
		 */
		constructor(settings)
		{
			this.settings = settings;
		}

		/**
		 * registers all the event handlers
		 *
		 * @return {void}
		 */
		register()
		{
			jQuery(document).ready(this.onDocumentReady.bind(this));
		}

		/**
		 * handles event when document has been completely loaded
		 *
		 * @return {void}
		 */
		onDocumentReady()
		{
			jQuery('[type=checkbox][data-name=enabled]').switchify();

			jQuery(document).on('change', '[data-name=enabled]', this.onEnabledCheckboxChanged.bind(this));
		}

		/**
		 * handles when enable/disable checkbox is clicked
		 *
		 * @param {Event} event
		 * @return {void}
		 */
		onEnabledCheckboxChanged(event)
		{
			const target = jQuery(event.target);

			const data = {};
			data['id'] = [target.val()];
			data['action'] = this.settings.id + '-action-' + (target.prop('checked') ? 'enable' : 'disable');
			data['_wpnonce'] = jQuery('#_wpnonce').val();

			jQuery.post(this.settings.ajaxurl, data, function (response) {
				console.log(response);
			});
		}
	}

	(new RulesTable(settings)).register();
})(rulesTableSettings);