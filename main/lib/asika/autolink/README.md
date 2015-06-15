# PHP Autolink Library

A library to auto convert URLs to links.

## Installation via Composer

Add this to composer.json require block.

``` json
{
    "require": {
        "asika/autolink": "1.*"
    }
}
```

## Getting Started

This is a quick start to convert URL to link:

``` php
use Asika\Autolink\Linker;

$text = Linker::convert($text);
$text = Linker::convertEmail($text);
```

## Use Autolink Object

Create the object:

``` php
use Asika\Autolink\Autolink;

$autolink = new Autolink;
```

Create with options.

``` php
$options = array(
    'strip_scheme' => false,
    'text_limit' => false,
    'auto_title' => false
);

$schemes = array('http', 'https', 'skype', 'itunes');

$autolink = new Autolink($options, $schemes);
```

## Convert Text

This is an example text:

``` html
This is Simple URL:
http://www.google.com.tw

This is SSL URL:
https://www.google.com.tw

This is URL with multi-level query:
http://example.com/?foo[1]=a&foo[2]=b
```

We convert all URLs.

``` php
$text = $autolink->convert($text);
```

Output:

``` html
This is Simple URL:
<a href="http://www.google.com.tw">http://www.google.com.tw</a>

This is SSL URL:
<a href="https://www.google.com.tw">https://www.google.com.tw</a>

This is URL with multi-level query:
<a href="http://example.com/?foo[1]=a&amp;foo[2]=b">http://example.com/?foo[1]=a&amp;foo[2]=b</a>
```

### Add Attributes

``` php
$text = $autolink->convert($text, array('class' => 'center'));
```

All link will add this attributes:

``` php
This is Simple URL:
<a href="http://www.google.com.tw" class="center">http://www.google.com.tw</a>

This is SSL URL:
<a href="https://www.google.com.tw" class="center">https://www.google.com.tw</a>
```

## Options

### `text_limit`

We can set this option by constructor or setter:

``` php
$auitolink->textLimit(50);

$text = $autolink->convert($text);
```

The link text will be:

```
http://campus.asukademy.com/learning/job/84-fin...
```

Use Your own limit handler by set a callback:

``` php
$auitolink->textLimit(function($url)
{
    return substr($url, 0, 50) . '...';
});
```

Or use `\Asika\Autolink\Linker::shorten()` Pretty handler:

``` php
$auitolink->textLimit(function($url)
{
    return \Asika\Autolink\Linker::shorten($url, 15, 6);
});
```

Output:

``` text
http://campus.asukademy.com/....../84-find-interns......
```

### `auto_title`

Use AutoTitle to force add title on anchor element.
 
``` php
$autolink->autoTitle(true);

$text = $autolink->convert($text);
```

Output:

``` html
<a href="http://www.google.com.tw" title="http://www.google.com.tw">http://www.google.com.tw</a>
```

### `strip_scheme`

Strip Scheme on link text:

``` php
$auitolink->stripScheme(true);

$text = $autolink->convert($text);
```

Output

``` html
<a href="http://www.google.com.tw" >www.google.com.tw</a>
```

## Scheme

You can add new scheme to convert URL begin with it, foe example: `vnc://example.com`

``` php
$autolink->addScheme('skype')
    ->addScheme('vnc');
```

Default schemes is `http, https, ftp, ftps`.

## Link Builder

If you don't want to use `<a>` element as your link, you can set a callback to build link HTML.

``` php
$autolink->setLinkBuilder(function($url, $attribs)
{
    $attribs['src'] = htmlspecialchars($url);

    return (string) \Windwalker\Html\HtmlElement('img', null, $attribs);
});
```

See: [Windwalker Html Package](https://github.com/ventoviro/windwalker-html)


