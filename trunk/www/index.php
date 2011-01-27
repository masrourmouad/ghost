<html>
	<head>
		<title>Ghost</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="icon" href="icon.png" />
    <link rel="alternate" type="application/rss+xml" href="rss.php" title="Ghost recent chats" />
    <link rel="alternate" type="application/rss+xml" href="rss-improve.php?lang=sk" title="Recent slovak improvements" />
    <link rel="alternate" type="application/rss+xml" href="rss-improve.php?lang=en" title="Recent english improvements" />
    <link rel="stylesheet" type="text/css" href="index.css" />
	</head>
	<script src="index.js"></script>
  
	<body onload="document.getElementById('input1').focus()">

    <div id="logo">
      <a href="http://code.google.com/p/ghost/">
        <h1>ghost</h1>
        <h2>a tiny chat bot</h2>
      </a>
    </div>
  
    <form action="chat.php" method="get">
      <?php
        require_once "sentence.php";
        // detect language    
        $language = ghostLanguage();
        if ($language == 'en') {
          $en1 = ' checked';
          $sk1 = ' ';
        } else {
          $en1 = ' ';
          $sk1 = ' checked';
        };
        echo '<input type="radio" id="lang_en" name="lang" value="en" '.$en1.'/><label for="lang_en">english</label>';
        echo '<input type="radio" id="lang_sk" name="lang" value="sk" '.$sk1.'/><label for="lang_sk">slovensky</label>';
      ?>
      <br/>
      <input id="input1" type="text" name="question" autofocus />
      <input type="submit" />
    </form>  
    
    <?php
      //  no question, so only show chats
      $chat = explode("\n",file_get_contents('chat.txt'));
      for ($i=0; ($i<2*20+1)&&($i<count($chat)-1); $i++)
        echo $chat[$i]."\n";

      /*
      DONE:
      - added eval for basic math formulas
      - fixed output attributes ($a, not $i)
      - added $time; $date;
      - moved language autodetect to ghostLanguage() in sentence.php
      
      TODO:
      - show this to few beta testers
        - s
        - s170
        - sw
        - nb
      - synchronize new QA from betatesters
      - release it (update google code page + link to live demo)
      
      Later:
      - use eval class from phpclassess once that stupid site start working
      - for performance reasons, I should append new answers to the end of file or something (or maybe just use mysql like other sane people)
      */
    ?>  

	</body>
</html>