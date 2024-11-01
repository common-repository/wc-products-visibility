(function () {
	class EditRuleForm
	{
		/**
		 * constructor
		 */
		constructor()
		{
			// nothing for now
		}

		/**
		 * registers all the event handlers
		 */
		register()
		{
			jQuery(document).ready(this.onDocumentReady.bind(this));
		}

		/**
		 * handles event when document has been completely loaded
		 */
		onDocumentReady()
		{
			//const conditionTitles = [];
			//const conditionRows = {};

			//jQuery('[data-condition-title]').each(function(idx, element) {
			//	const target = jQuery(element);
			//	const title = target.data('condition-title');
			//	const row = target.parents('tr');

			//	if (false === (title in conditionRows)) {
			//		conditionTitles.push(title);
			//		conditionRows[title] = [];
			//	}

			//	conditionRows[title].push(row);
			//});

			//console.log(conditionTitles);
		}
	}

	(new EditRuleForm()).register();
})();