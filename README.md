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
<?php Css::begin();?>
header [
    h1 {font-size:2em;}
    h2 {font-size:1.5em;}
    a [
        {color: blue;}
        :hover {color: red;}
    ]
]
<?php Css::end();?>
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
<?php Css::begin();?>
header [
    h1 {font-size:2em;}
    h2 {font-size:1.5em;}
    a [
        {color: $hrefColor;}
        :hover {color: $hrefHoverColor;}
    ]
]
<?php Css::end([
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
By default "end" method return minified CSS, to disable minification pass "false" as SECOND param
```
<?php include 'Css.php';?>
<html>
<head>
</head>
<body>
<h3>Example</h3>
<pre></code>
<?php Css::begin();?>
header [
    h1 {font-size:2em;}
    h2 {font-size:1.5em;}
    a [
        {color: $hrefColor;}
        :hover {color: $hrefHoverColor;}
    ]
]
<?php Css::end([
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

### Example 3
By default "end" method output CSS, to return it pass "false" as THIRD param
```
<?php include 'Css.php';?>
<html>
<head>
</head>
<body>
<h3>Example</h3>
<pre></code>
<?php Css::begin();?>
header [
    h1 {font-size:2em;}
    h2 {font-size:1.5em;}
    a [
        {color: $hrefColor;}
        :hover {color: $hrefHoverColor;}
    ]
]
<?php echo Css::end([
    'hrefColor' => 'blue',
    'hrefHoverColor' => 'red',
], false, false)?>
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

### Example 5
You can also use "compile" method if you get css from some where else
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
<?php 
$css = ob_get_clean();

echo Css::compile($css, [
    'hrefColor' => 'blue',
    'hrefHoverColor' => 'red',
], false)

?>
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
