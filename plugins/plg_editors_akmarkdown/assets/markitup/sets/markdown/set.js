// -------------------------------------------------------------------
// markItUp!
// -------------------------------------------------------------------
// Copyright (C) 2008 Jay Salvat
// http://markitup.jaysalvat.com/
// -------------------------------------------------------------------
// MarkDown tags example
// http://en.wikipedia.org/wiki/Markdown
// http://daringfireball.net/projects/markdown/
// -------------------------------------------------------------------
// Feel free to add more tags
// -------------------------------------------------------------------
var MIUEditorSettingBasic = {
    targetArea:         '.ace_text-input',
    previewParserPath:  'index.php?akmarkdown=preview&sets=markdown',
    onShiftEnter:       {keepDefault:false, openWith:'\\n\\n'},
    markupSet: [
        {name:'First Level Heading', key:"1", placeHolder:'Title', closeWith:function(markItUp) { return miu.markdownTitle(markItUp, '=') } },
        {name:'Second Level Heading', key:"2", placeHolder:'Title', closeWith:function(markItUp) { return miu.markdownTitle(markItUp, '-') } },
        {name:'Heading 3', key:"3", openWith:'### ', placeHolder:'Title' },
        {name:'Heading 4', key:"4", openWith:'#### ', placeHolder:'Title' },
        {name:'Heading 5', key:"5", openWith:'##### ', placeHolder:'Title' },
        {name:'Heading 6', key:"6", openWith:'###### ', placeHolder:'Title' },
        {separator:'---------------' },        
        {name:'Bold', key:"B", openWith:'**', closeWith:'**'},
        {name:'Italic', key:"I", openWith:'_', closeWith:'_'},
        {separator:'---------------' },
        {name:'Bulleted List', openWith:'- ' , multiline: true},
        {name:'Numeric List', openWith: function(markItUp) {
            return markItUp.line+'. ';
        }, multiline: true},
        {separator:'---------------' },
        {name:'Picture', key:"P", replaceWith:'![[![Alternative text]!]]([![Url:!:http://]!] "[![Title]!]")'},
        {name:'Link', key:"L", openWith:'[', closeWith:']([![Url:!:http://]!])', placeHolder:'Click here to link...' },
        {separator:'---------------'},    
        {name:'Quotes', openWith:'> ', multiline: true},
        {name:'Code Block / Code', openWith:'(!(\t|!|`)!)', closeWith:'(!(`)!)', multiline: true},
        {separator:'---------------'},
        {name:'Preview', call:'preview', className:"preview"}
    ]
}

// mIu nameSpace to avoid conflict.
miu = {
    markdownTitle: function(markItUp, char) {
        heading = '';
        n = jQuery.trim(markItUp.selection||markItUp.placeHolder).length;
        for(i = 0; i < n; i++) {
            heading += char;
        }
        return '\n'+heading+'\n';
    }
}