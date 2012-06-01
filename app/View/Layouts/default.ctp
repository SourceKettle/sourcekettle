<!DOCTYPE html>
<head> 
    <title>
        DevTrack - <?= $title_for_layout ?>
    </title> 
    <?= $this->Html->charset('UTF-8') . "\n" ?>
    <?= $this->Html->css('bootstrap.min') ?>
    <? if (isset($css_for_layout)): foreach ($css_for_layout as $css): ?>
            <?= $this->Html->css(array($css)) ?>
        <? endforeach;
    endif; ?>
    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
</head>
<body>
    <header>
        <div class="navbar navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container">
          <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </a>
          <a class="brand" href="#">DevTrack</a>
          <div class="nav-collapse">
            <ul class="nav">
              <li class="active"><a href="#">Home</a></li>
              <li><a href="#about">About</a></li>
              <li><a href="#contact">Contact</a></li>
            </ul>
            <form style="float: right"  class="navbar-search pull-left">
                <input type="text" class="search-query" placeholder="Search">
            </form>
          </div>
        </div>
      </div>
    </div>
    </header> 
    <div class="container">
        <div id="content">
            <?= $this->Session->flash() ?>
            <?= $content_for_layout ?>
        </div>
    </div>
    
    <!-- JavaScript! Placed at the end of the file for faster page loading -->
    <?= $this->Html->script('http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js');?>
    <?= $this->Html->script('bootstrap.min') ?>
    
    
</body>
</html>
