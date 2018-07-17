# CSS-Preprocessor
My simple CSS preprocessor that support css path folding and variables.

## Usage

### Example 1
Unfold blocks marked with '[' and ']'
```
<?php include 'Css.php';?>
<html>
<head>
</head>
<body>
<h3>Example</h3>
<pre></code>
<?php ob_start();?>
header [
    h1 {font-size:2em;}
    h2 {font-size:1.5em;}
    a [
        {color: blue;}
        :hover {color: red;}
    ]
]
<?=Css::compile(ob_get_clean())?>
</code></pre>
</body>
</html>
```
Will produce
```
Example

header h1{font-size:2em}header h2{font-size:1.5em}header a{color:blue}header a:hover{color:red}
```
### Example 2
Unfold blocks marked with '[' and ']' and set variables
```
<?php include 'Css.php';?>
<html>
<head>
</head>
<body>
<h3>Example</h3>
<pre></code>
<?php ob_start();?>
header [
    h1 {font-size:2em;}
    h2 {font-size:1.5em;}
    a [
        {color: $hrefColor;}
        :hover {color: $hrefHoverColor;}
    ]
]
<?=Css::compile(ob_get_clean(),[
    'hrefColor' => 'blue',
    'hrefHoverColor' => 'red',
])?>
</code></pre>
</body>
</html>
```
Will produce
```
Example

header h1{font-size:2em}header h2{font-size:1.5em}header a{color:blue}header a:hover{color:red}
```

### Example 3
By default compile method returns minified CSS, to disable minification pass false as third param to compile method
```
<?php include 'Css.php';?>
<html>
<head>
</head>
<body>
<h3>Example</h3>
<pre></code>
<?php ob_start();?>
header [
    h1 {font-size:2em;}
    h2 {font-size:1.5em;}
    a [
        {color: $hrefColor;}
        :hover {color: $hrefHoverColor;}
    ]
]
<?=Css::compile(ob_get_clean(),[
    'hrefColor' => 'blue',
    'hrefHoverColor' => 'red',
], false)?>
</code></pre>
</body>
</html>
```
Will produce
```
Example

header h1 {font-size:2em;}
header h2 {font-size:1.5em;}
header a {color: blue;}
header a:hover {color: red;}
```
