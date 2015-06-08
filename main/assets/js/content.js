/**
 * Prettify article.
 *
 * @param option
 *
 * @constructor
 */
var AKMarkdownPretiffy = function (option)
{
	window.addEvent('domready', function ()
	{
		// Handle Images
		var placeholder = $$('.akmarkdown-content-placeholder');
		var articles    = $$('.akmarkdown-content');
		var ps          = articles.getElements('p');

		if (!articles)
		{
			return;
		}

		// Images
		ps.each(function (p1)
		{
			p1.each(function (p)
			{
				var e = p.getElement('img');

				if (p.get('text').length == 0)
				{
					// Set align Center
					if (option.Article_ForceImageAlign)
					{
						p.set('align', option.Article_ForceImageAlign);
					}

					// Set Image class
					if (option.Article_ImageClass)
					{
						e.addClass(option.Article_ImageClass);
					}

					e.addEvent('load', function (e2)
					{
						if (this.naturalWidth > option.Article_ForceImageMaxWidth && option.Article_ForceImageMaxWidth)
						{
							this.setStyle('width', option.Article_ForceImageMaxWidth);
						}
					});
				}
			});

		});

		// Handle Links
		if (option.Article_ForceNewWindow)
		{
			var links = articles.getElements('a');

			links.each(function (e)
			{
				e.set('target', '_blank');
			});
		}

		// Handle Table
		if (option.Article_TableClass)
		{
			var tables = articles.getElements('table');

			tables.each(function (e)
			{
				e.addClass(option.Article_TableClass);
			});
		}

		// Nav List
		if (option.Article_NavList)
		{

			articles.each(function (article)
			{
				var heading = article.getElements('h2, h3');
				var ul      = new Element('ul.page-nav.level-1');
				var li      = Array();
				var subul   = Array();
				var subli   = Array();
				var i       = 0;
				var k       = 0;

				heading.each(function (e)
				{
					// Set h2
					if (e.tagName == 'H2')
					{
						if (subul[i] && li[i])
						{
							subul[i].inject(li[i], 'bottom');
							subul[i] = null;
						}

						i++;
						li[i] = new Element('li');

						// Set Link
						var a = new Element('a', {href: '#' + encodeURI(e.get('text').trim()), text: e.get('text')});
						a.inject(li[i], 'bottom');

						li[i].inject(ul, 'bottom');
					}

					// Set h3
					if (e.tagName == 'H3')
					{
						if (!subul[i])
						{
							subul[i] = new Element('ul.level-2');
						}

						var subli = new Element('li');

						// Set Link
						var a = new Element('a', {href: '#' + encodeURI(e.get('text').trim()), text: e.get('text')});
						a.inject(subli, 'bottom');

						subli.inject(subul[i], 'bottom');
					}

					k++;

					// Set Back Top
					if (k == 1)
					{
						return;
					}

					var a = new Element('a', {href: '#page-top', text: Joomla.JText._('PLG_SYSTEM_AKMARKDOWN_NAV_LIST_BACK_TO_TOP')});
					var hr = new Element('hr');
					var div = new Element('p', {align: 'right'});

					a.inject(div, 'bottom');
					hr.inject(div, 'bottom');

					var p = e.getPrevious();

					if (p.tagName == 'H2')
					{
						return;
					}

					div.inject(e, 'before');

					// Set Heading anchor
					var an = new Element('div', {'id': encodeURI(e.get('text').trim()), class: 'akmarkdown-page-anchor'});
					an.inject(e, 'before');
				});

				// Last inject
				if (subul[i] && li[i])
				{
					subul[i].inject(li[i], 'bottom');
					subul[i] = null;
				}

				if (li.length == 0)
				{
					return;
				}

				var wrap = new Element('div#page-top');

				wrap.addClass(option.Article_NavList_Class);

				ul.inject(wrap, 'top');

				wrap.inject(article, 'top');

				new Fx.SmoothScroll({ duration: 300}, window);
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
};
