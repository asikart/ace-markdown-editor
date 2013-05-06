

var AKMarkdownClass = new Class({
    
    initialize : function(options){
        this.MIUEditorSetting = [] ;
        this.ace    = [] ;
        this.i      = 0 ;
    }
    ,
    createEditor : function(id, name){
        this.MIUEditorSetting[id] = Object.clone(MIUEditorSettingBasic) ;
        this.MIUEditorSetting[id].nameSpace = id ;
        
        // Init ACE Editor
        var editor = this.ace[id] = ace.edit($$('#'+id+'-wrap')[0]);
        var textInput = editor.textInput.getElement() ;
        
        
        editor.setTheme("ace/theme/twilight");
        editor.getSession().setMode("ace/mode/markdown");
        
        
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