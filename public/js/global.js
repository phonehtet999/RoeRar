$(function () {
	/** Delete Warning */
	$('body').on('click', '#deletebtn', function() {
		// prepare letiables
		let id = this.getAttribute('delete_id')
		// show confirm dialog
		bootbox.confirm({
			message: 'This record will be delete! Are you sure?',
			size: 'medium',
			title: 'Delete Record',
			backdrop: 'static',
			closeButton: true,
			centerVertical: true,
			keyboard: false,
			callback: function (result) {
				if (result) {
					$(`#form_destroy_${id}`).submit();
				}
			}
		});
	});
});