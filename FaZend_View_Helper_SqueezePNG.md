# Usage examples #

Go to google.com and review the source code of the page (when you're logged in). You will see this image at `http://www.google.com/images/nav_logo6.png`:

![http://www.google.com/images/nav_logo6.png](http://www.google.com/images/nav_logo6.png)

When you see the CSS code you will see something like this:

```
background:url(/images/nav_logo6.png) no-repeat;
border:0;
cursor:pointer;
display:inline;
background-position:-104px -42px;
```

You can create it just with one simple helper in your own application. You create a file `button.png` (for example) and store it in `application/views/squeeze/button.png`. Than you do this (in view script):

```
<a href="#" style="<?=$this->squeezePNG('button.png')?>"></a>
```

All the rest is done automatically.

# How it works #