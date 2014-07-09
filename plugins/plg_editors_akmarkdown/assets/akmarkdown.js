/*!
 * AKMarkdown JS
 *
 * Copyright 2013 Asikart.com
 * License GNU General Public License version 2 or later; see LICENSE.txt, see LICENSE.php
 *
 * Generator: AKHelper
 * Author: Asika
 */


// Fix a strange bug for mootools
if (this.MooTools.build == 'ab8ea8824dc3b24b6666867a2c4ed58ebb762cf0')
{
	delete Function.prototype.bind;

	Function.implement({

		/*<!ES5-bind>*/
		bind: function (that)
		{
			var self = this,
				args = arguments.length > 1 ? Array.slice(arguments, 1) : null,

				F = function ()
				{
				};

			var bound = function ()
			{
				var context = that, length = arguments.length;

				if (this instanceof bound)
				{
					F.prototype = self.prototype;
					context = new F;
				}

				var result = (!args && !length)
					? self.call(context)
					: self.apply(context, args && length ? args.concat(Array.slice(arguments)) : args || arguments);

				return context == that ? result : context;
			};

			return bound;
		},
		/*</!ES5-bind>*/
	});
}

/**
 * Start AKMarkdown
 *
 * @type {Class}
 */
var AKMarkdownClass = new Class({

	/**
	 * Init.
	 *
	 * @param options
	 */
	initialize: function (options)
	{
		this.MIUEditorSetting = [];
		this.ace = [];
		this.i = 0;
		this.options = options;
	},

	/**
	 * Create editor.
	 *
	 * @param id
	 * @param name
	 */
	createEditor: function (id, name)
	{
		var self = this;

		this.MIUEditorSetting[id] = Object.clone(MIUEditorSettingBasic);

		this.MIUEditorSetting[id].nameSpace = id;

		this.MIUEditorSetting[id].previewParserPath = this.options.root + 'index.php?akmarkdown=preview&sets=markdown';

		// Init ACE Editor
		var editor    = this.ace[id] = ace.edit($$('#' + id + '-wrap')[0]);
		var textInput = editor.textInput.getElement();

		editor.setTheme("ace/theme/" + this.options.aceTheme);

		editor.getSession().setMode("ace/mode/markdown");

		editor.setShowPrintMargin(false);

		editor.getSession().setUseWrapMode(this.options.wrap);

		// Wrap limit
		if (this.options.wrapLimit)
		{
			editor.getSession().setWrapLimitRange(this.options.wrapLimit, this.options.wrapLimit);

			editor.setPrintMarginColumn(this.options.wrapLimit);

			editor.setShowPrintMargin(true);
		}

		textInput.set('id', id);
		textInput.set('name', name);

		// Init MarkItUp Editor
		jQuery('#' + id + '-wrap').markItUp(this.MIUEditorSetting[id]);

		this.i++;
	},

	/**
	 * Override save action.
	 */
	overrideSaveAction: function ()
	{
		var submitbuttonTmp = Joomla.submitbutton;
		var self            = this;

		Joomla.submitbutton = function (task, form)
		{
			Object.each(self.ace, function (e, id)
			{
				document.getElementById(id).value = e.getValue();
			});

			return submitbuttonTmp(task, form);
		};
	}
});

(function($) {
	function upload(options, context) {

		$('#s3-file-' + options.id).on('change', function() {
			start();
		});

		var key = '';
		var bar = $('#s3-upload-bar-' + options.id);
		var button = $('#editor-upload-' + options.id);
		var file = {};

		function start() {
			bar.show();
			button.hide();
			file = document.getElementById('s3-file-' + options.id).files[0];
			var fd = new FormData();
			var date = new Date();
			var exts = options.ext.replace(/\s/g, '').split(',');
			var ext = file.name.split('.').slice(-1)[0].toLowerCase();

			if($.inArray(ext, exts) < 0) {
				bar.hide();
				button.show();
				alert('Unallowed extension');
				return;
			}

			key = options.key + '/' + Math.round(date.getTime() / 1000) + '_' + file.name

			fd.append('key', key);
			fd.append('AWSAccessKeyId', options.apikey);
			fd.append('acl', 'public-read');
			fd.append('policy', options.policy);
			fd.append('signature', options.signature);
			fd.append('Content-type', file.type);
			fd.append('file', file);

			var xhr = GetXmlHttpObject();

			//xhr.upload.addEventListener("progress", uploadProgress, false);
			xhr.addEventListener("load", uploadComplete, false);
			xhr.addEventListener("error", uploadFailed, false);
			xhr.addEventListener("abort", uploadCanceled, false);

			xhr.open('POST', 'https://' + options.bucket + '.s3.amazonaws.com/', true); //MUST BE LAST LINE BEFORE YOU SEND

			xhr.send(fd);
		}

		function uploadComplete(evt) {
			bar.hide();
			button.show();

			var name = file.name.split('.').slice(-2)[0];
			var ext = file.name.split('.').slice(-1)[0].toLowerCase();

			if($.inArray(ext, ['png', 'jpg', 'gif', 'jpeg']) >= 0) {
				jInsertEditorText('\n<img alt="' + name + '" src="https://' + options.bucket + '.s3.amazonaws.com/' + key + '" class="img-polaroid" />', options.id);
			} else {
				jInsertEditorText('<a href="https://' + options.bucket + '.s3.amazonaws.com/' + key + '">' + name + '</a>', options.id);
			}
		}

		function uploadFailed(evt) {
			bar.hide();
			button.show();
			alert("There was an error attempting to upload the file." + evt);
		}

		function uploadCanceled(evt) {
			bar.hide();
			button.show();
			alert("The upload has been canceled by the user or the browser dropped the connection.");
		}

		function GetXmlHttpObject() {
			var xmlHttp = null;
			try {
				xmlHttp = new XMLHttpRequest();
			}
			catch(e) {
				try {
					xmlHttp = new ActiveXObject("Msxml2.XMLHTTP");
				}
				catch(e) {
					xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
				}
			}
			return xmlHttp;
		}
	}

	$.fn.S3 = function(params) {
		return new upload(params, this);
	}
}(jQuery));