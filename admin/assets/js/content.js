
var AKMarkdownPretiffy = function(option){
    
    window.addEvent('domready', function(){
        
        // Handle Images
        var placeholder = $$('.akmarkdown-content-placeholder') ;
        var articles    = placeholder.getParent();
        var ps          = articles.getElements('p') ;
        
        if(!placeholder) return;
        
        ps.each( function(p1){
            
            p1.each(function(p){
            
                var e = p.getElement('img') ;
                
                if(p.get('text').length == 0) {
                    
                    // set align Center
                    if(option.Article_ForceImageAlign){
                        p.set('align', option.Article_ForceImageAlign);
                    }
                    
                    // Set Image class
                    if(option.Article_ImageClass){
                        e.addClass(option.Article_ImageClass);
                    }
                    
                    e.addEvent('load', function(e2){
                        
                        if(this.naturalWidth > option.Article_ForceImageMaxWidth && option.Article_ForceImageMaxWidth ) {
                            this.setStyle('width', option.Article_ForceImageMaxWidth) ;
                        }
                    });
                }
                
                
            
            });
            
        });
        
        // Handle Links
        if(option.Article_ForceNewWindow){
            var links = articles.getElements('a') ;
            
            links.each( function(e){
                e.set('target', '_blank');
            });
        }
        
        // Handle Table
        if(option.Article_TableClass){
            var tables = articles.getElements('table') ;
            tables.each( function(e){
                e.addClass(option.Article_TableClass) ;
            });
        }
        
        
        
        /*
        // Handle Define list
        var dls = $$('article.item dl') ;
        dls.addClass('separator') ;
        
        // Handle Table
        var dls = $$('article.item table') ;
        dls.addClass('zebra') ;
        */
        
    }); 
}



