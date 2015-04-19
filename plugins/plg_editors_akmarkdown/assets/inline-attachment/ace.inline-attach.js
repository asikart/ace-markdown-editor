/*jslint newcap: true */
/*global inlineAttach: false, jQuery: false */
/**
 * jQuery plugin for inline attach
 *
 * @param {document} document
 * @param {window} window
 * @param {jQuery} $
 */
(function(document, window, $) {
    "use strict";

    function AceEditor(instance) {

	    var ace = instance;

        return {
            getValue: function() {
                return ace.getValue();
            },
            setValue: function(val) {
				var pos = ace.getCursorPosition();

	            ace.setValue(val);

	            ace.getSelection().clearSelection();
	            ace.getSelection().moveCursorTo(pos.row, pos.column);

	            ace.focus();
            },
	        insert: function(val) {
		        ace.insert(val);

		        ace.focus();
	        }
        };
    }

	AceEditor.prototype = new inlineAttach.Editor();

	window.inlineAttach.attachToAce = function(ace, options) {

        var editor       = new AceEditor(ace),
            inlineattach = new inlineAttach(options, editor);

        function catchAndDoNothing(e)
        {
	        e.stopPropagation();
	        e.preventDefault();
        }
        ace.container.addEventListener("drop", function(e)
        {
	        inlineattach.onDrop(e);
	        e.stopPropagation();
	        e.preventDefault();
        }, true);
        ace.container.addEventListener("dragenter", catchAndDoNothing, false);
        ace.container.addEventListener("dragover", catchAndDoNothing, false);
        ace.container.addEventListener("paste", function(e) {
	        inlineattach.onPaste(e);
        }, true);

        return this;
    };
})(document, window, jQuery);
