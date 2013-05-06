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
if (this.MooTools.build=='ab8ea8824dc3b24b6666867a2c4ed58ebb762cf0') {
    delete Function.prototype.bind;

    Function.implement({

        /*<!ES5-bind>*/
        bind: function(that){
            var self = this,
                args = arguments.length > 1 ? Array.slice(arguments, 1) : null,
                F = function(){};

            var bound = function(){
                var context = that, length = arguments.length;
                if (this instanceof bound){
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

// Start AKMarkdown
var AKMarkdownClass = new Class({
    
    initialize : function(options){
        this.MIUEditorSetting = [] ;
        this.ace    = [] ;
        this.i      = 0 ;
        this.options= options ;
        
    }
    ,
    createEditor : function(id, name){
        var self = this ;
        
        this.MIUEditorSetting[id] = Object.clone(MIUEditorSettingBasic) ;
        this.MIUEditorSetting[id].nameSpace = id ;
        
        // Init ACE Editor
        var editor = this.ace[id] = ace.edit($$('#'+id+'-wrap')[0]);
        var textInput = editor.textInput.getElement() ;
        
        editor.setTheme("ace/theme/" + this.options.aceTheme);
        editor.getSession().setMode("ace/mode/markdown");
        editor.getSession().setUseWrapMode(true);
        
        textInput.set('id', id) ;
        textInput.set('name', name) ;
        
        // Init MarkItUp Editor
        jQuery('#'+id+'-wrap').markItUp(this.MIUEditorSetting[id]);
        
        
        this.i++ ;
    }
    ,
    overrideSaveAction : function(){
        var submitbuttonTmp = Joomla.submitbutton ;
        var self = this ;
        
        Joomla.submitbutton = function(task, form){
            Object.each(self.ace, function(e, id){
                document.getElementById(id).value = e.getValue() ;
            })
            
            return submitbuttonTmp(task, form);
        };
    }

}) ;