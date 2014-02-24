# Asikart Markdown Editor for Joomla! CMS [![Analytics](https://ga-beacon.appspot.com/UA-XXXXX-X/your-repo/page-name)](https://github.com/igrigorik/ga-beacon)


![Asikart-Markdown-LOGO-300-white.png][1]

**Asikart Markdown Editor** helps you writing text in well-known markdown syntax, and render to HTML in front-end article.

This editor integrate "ACE syntax highlight editor" and "MarkItUp tags insert editor", easily edit plain text. In the front-end, using "PHP Markdown Extra" to render markdown syntax, which provide extended features from origin markdown.

And the syntax highlighter plugin is "highlight.js", perfectly integrate with Markdown Extra.

 

> Extension Info
>
> ![compat 25][2] &nbsp; ![compat 30][3] &nbsp; ![ext plugin][4]

I have been looking for a Joomla! Markdown Editor with syntax highlighter, text indent and word wrap for a long time, but there are no one is that what I want.

So after few days hard working, I integrate ACE with MarkItUp, I can easily edit plain-text document in Joomla!

### Configure AWS S3 to enable uploads.

1. Go to your AWS console and open S3 service. 

2. Open bucket you want upload to and click properties. 

3. In permision part click Edit CORS Configuration and insert there something like this.

   ```
   <CORSConfiguration>
       <CORSRule>
           <AllowedOrigin>*</AllowedOrigin>
           <AllowedMethod>GET</AllowedMethod>
           <MaxAgeSeconds>3000</MaxAgeSeconds>
       </CORSRule>
       <CORSRule>
           <AllowedOrigin>http://***.com</AllowedOrigin>
           <AllowedMethod>POST</AllowedMethod>
           <AllowedMethod>PUT</AllowedMethod>
           <MaxAgeSeconds>3000</MaxAgeSeconds>
           <AllowedHeader>*</AllowedHeader>
       </CORSRule>
   </CORSConfiguration>
   ````
   
   Theses are 2 rules. First allow get picture to everyone and second upload picture only from your domain.  So you have to change *** to your domain. If you have few domains, simply create another `<CORSRule>` block.

4. Edit Akmarkdown editor plugin parameters. Enable AWS and enter you AWS access information.
   
   ![2014-02-07_21-24-39](https://f.cloud.github.com/assets/650741/2111051/46f9bd80-900c-11e3-9a2e-76edf90d3e18.png)

### NEW FEATURES IN 1.0.4

Support GitHub flavored markdown code block. Using ``` to wrap your code.

<pre>
``` php
class Foo
{
    public function bar()
    {
        echo 'yoo';
    }
}
```
</pre>

The result:

``` php
class Foo
{
    public function bar()
    {
        echo 'yoo';
    }
}
```

 

### FEATURES

  * ACE & MarkItUp editor
  * Once upload and install 2 plugins.
  * 29 ACE themes.
  * 16 hightlight.js themes.
  * 4 MarkItUp themes.
  * Some buttons to inset markdown code.
  * 2 buttonsets: HTML & Markdown
  * Automatic convert insert link & image code from Joomla! core editor-xtd buttons as markdown code.
  * You can just using Asikart Markdown as a HTML editor.
  * Because Markdown can't set style on images & links, we add the "ARTICLE PRETTIFY" functions to auto set image alignment, max width, add img & table classes and force link open new window.

 

### REFERENCE

<ul>
<li><a href="http://daringfireball.net/projects/markdown/syntax" target="_blank">Markdown</a></li>
<li><a href="http://michelf.ca/projects/php-markdown/extra/" target="_blank">PHP Markdown Extra</a></li>
<li><a href="http://softwaremaniacs.org/soft/highlight/en/" target="_blank">highlight.js</a></li>
<li><a href="http://markitup.jaysalvat.com/home/" target="_blank">MarkItUp</a></li>
<li><a href="http://ace.ajax.org/" target="_blank">ACE</a></li>
</ul>

 

## Note Worthy on JED

![noteworthy .jpg][6]


## SCREEN SHOTS

![130506-0001.jpg][7]

![130507-0001.jpg][8]

![130507-0002.jpg][9]

![130507-0003.jpg][10]

![130627-0001.jpg][11]


 [Download][5]

## Donate Us

![](https://www.paypalobjects.com/en_GB/i/btn/btn_donate_LG.gif)

[$20](https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=asika32764%40gmail%2ecom&lc=GB&item_name=Asikart%20Studios&amount=20%2e00&currency_code=USD&no_note=0&bn=PP%2dDonationsBF%3abtn_donate_LG%2egif%3aNonHostedGuest) / 
[$30](https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=asika32764%40gmail%2ecom&lc=GB&item_name=Asikart%20Studios&amount=30%2e00&currency_code=USD&no_note=0&bn=PP%2dDonationsBF%3abtn_donate_LG%2egif%3aNonHostedGuest) / 
[$40](https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=asika32764%40gmail%2ecom&lc=GB&item_name=Asikart%20Studios&amount=40%2e00&currency_code=USD&no_note=0&bn=PP%2dDonationsBF%3abtn_donate_LG%2egif%3aNonHostedGuest) / 
[$50](https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=asika32764%40gmail%2ecom&lc=GB&item_name=Asikart%20Studios&amount=50%2e00&currency_code=USD&no_note=0&bn=PP%2dDonationsBF%3abtn_donate_LG%2egif%3aNonHostedGuest) / 
[Enter my amount](https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=asika32764%40gmail%2ecom&lc=GB&item_name=Asikart%20Studios&no_note=0&currency_code=USD&bn=PP%2dDonationsBF%3abtn_donate_LG%2egif%3aNonHostedGuest)

   [1]: http://ext.asikart.com/images/extensions/markdown/Asikart-Markdown-LOGO-300-white.png
   [2]: http://ext.asikart.com/images/global/extension/compat_25.png
   [3]: http://ext.asikart.com/images/global/extension/compat_30.png
   [4]: http://ext.asikart.com/images/global/extension/ext_plugin.png
   [5]: http://ext.asikart.com/downloads/ace-x-markdown-editor.html
   [6]: http://ext.asikart.com/images/extensions/remoteimage/noteworthy%20.jpg
   [7]: http://ext.asikart.com/images/extensions/markdown/130506-0001.jpg
   [8]: http://ext.asikart.com/images/extensions/markdown/130507-0001.jpg
   [9]: http://ext.asikart.com/images/extensions/markdown/130507-0002.jpg
   [10]: http://ext.asikart.com/images/extensions/markdown/130507-0003.jpg
   [11]: http://ext.asikart.com/images/extensions/markdown/130627-0001.jpg
  
