/* Photo picker for Room "Extra photos" */
jQuery(function ($) {
	$('.elroi-gallery').each(function () {
		var box = $(this), input = box.find('input[type=hidden]'), thumbs = box.find('.elroi-gallery__thumbs'), frame;
		box.find('.elroi-gallery__pick').on('click', function (e) {
			e.preventDefault();
			frame = wp.media({ title: 'Choose room photos', button: { text: 'Use these photos' }, multiple: true, library: { type: 'image' } });
			frame.on('open', function () {
				var sel = frame.state().get('selection');
				(input.val() || '').split(',').filter(Boolean).forEach(function (id) { sel.add(wp.media.attachment(id)); });
			});
			frame.on('select', function () {
				var ids = [];
				thumbs.empty();
				frame.state().get('selection').each(function (a) {
					a = a.toJSON(); ids.push(a.id);
					var src = (a.sizes && a.sizes.thumbnail) ? a.sizes.thumbnail.url : a.url;
					thumbs.append('<img src="' + src + '" style="width:90px;height:90px;object-fit:cover">');
				});
				input.val(ids.join(','));
			});
			frame.open();
		});
		box.find('.elroi-gallery__clear').on('click', function () { input.val(''); thumbs.empty(); });
	});
	/* Live icon preview for Amenities */
	$('select[name="elroi_meta[icon]"]').on('change', function () {
		$('.elroi-icon-preview').html('<em style="font-size:12px">(save to preview)</em>');
	});
});
