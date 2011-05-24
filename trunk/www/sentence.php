<?php
  // sentence normalization

  function ghostParam($AName) {
    // get and filter parameter from url or form
    return substr(trim(str_replace("\n"," ",htmlspecialchars(strip_tags(@$_REQUEST[$AName])))),0,2000);
  }

  function ghostLanguage() {
    // guess which language user want to use

    // language override via cookie or param
    if ( (@$_COOKIE['lang'] == 'en')||(@$_REQUEST['lang'] == 'en')||(@$_REQUEST['lang_en'] == 'on') ) $language = 'en';
    if ( (@$_COOKIE['lang'] == 'sk')||(@$_REQUEST['lang'] == 'sk')||(@$_REQUEST['lang_sk'] == 'on') ) $language = 'sk';

    // quess language
    if (empty($language)) {
      $language = 'en';
      if (strpos(' '.$_SERVER['HTTP_ACCEPT_LANGUAGE'],'sk') > 0) 
        $language = 'sk';
    }
    
    return $language;   
  }

  function ghostSentenceRemoveEmoticons($AQuestion) {
    // remove most common but safe to remove emoticons from sentence
    $e = array(
           ':-)',':)',':o)',':]',':3','=]','8)','=)',':}',':^)', 
           ':-D', ':D', '8D', 'xD', '=D', '=3',
           'O.o', 'o.O', '',
           ':-(', ':(', ':[', ':{',
           ';-)', ';)', ';D',
           ':-P', ':P', ':-p', ':p', '=p',
           ':-O', ':O', 'O_O', 'o_o', '8O', 'O_o', 
           ':-/', ':/', ':\\', '=/', '=\\', ':|',
           'T_T', 'TT_TT', ';_;', '^^', '^_^',
           '>:)', '>;)', '>:-)',
           'B)', 'B-)', '8)', '8-)');
    return trim(str_replace($e,'',$AQuestion));
  }

  function ghostSentenceNormalize($AQuestion) {
    // basic normalization of sentence
    // remove smileys
    $AQuestion = ghostSentenceRemoveEmoticons($AQuestion);

    // lowercase
    $AQuestion = mb_strtolower($AQuestion,'UTF-8');

    // remove diacritics (slovak data has no diacritics anyway)
    $accent1 = array('á','ä','č','ď','é','ě','í','ĺ','ľ','ň','ó','ô','ŕ','ř','š','ť','ú','ů','ý','ž');
    $accent2 = array('a','a','c','d','e','e','i','l','l','n','o','o','r','r','s','t','u','u','y','z');
    $AQuestion = str_replace($accent1,$accent2,$AQuestion);

    // remove trippled chars (sooooo loooonnnnnggg --> soo loonngg)
    $s = $AQuestion;
    $AQuestion = '';
    $old1 = '';
    $old2 = '';
    for ($i=0; $i<mb_strlen($s); $i++) {
      if ( ($s[$i]!=$old1) || ($s[$i]!=$old2) ) {
        //echo "[$i] = '".$s[$i]."'\n";
        $AQuestion .= $s[$i];
      }
      $old2 = $old1;
      $old1 = $s[$i];
    }
    
    return $AQuestion;
  }

  function ghostSentence($AQuestion) {
    // convert sentence into array of words (split by word separators) and clean it up

    // basic filter  
    $AQuestion = ghostSentenceNormalize($AQuestion);

    // split sentence to words
    // FIXME: add [ and ]
    // FIXME: split by ' in nonenglish languages, currently removed
    $sentence = split("[ <>|?=,\".;:(){}/\\`~!@#$%^&*_+-]+", $AQuestion);
    if ($sentence[0]=='')
      $sentence = array_splice($sentence,1,count($sentence));
    if ( (count($sentence) > 0) && ($sentence[count($sentence)-1]=='') )
      $sentence = array_splice($sentence,0,count($sentence)-1);
    
    return $sentence;
  }

  function ghostSentencePart($ASentence,$AFrom,$ATo) {
    // return part of the sentence, e.g. ghostSentencePart(array("hello","world","this","is","something"),0,1) --> "hello","world"
    // range and order checks
    if ($AFrom < 0) 
      $AFrom = 0;
    if ($ATo > count($ASentence)-1) 
      $ATo = count($ASentence)-1;
    if ($ATo < $AFrom) 
      $ATo = $AFrom;
    // splice
    return array_splice($ASentence,$AFrom,$ATo-$AFrom+1);
  }

  function ghostSentencePartString($ASentence,$AFrom,$ATo) {
    // return part of a sentence as a space-separated string: e.g. ghostSentencePart(array("hello","world","this","is","something"),0,1) --> "hello world" 
    return trim(join(' ',ghostSentencePart($ASentence,$AFrom,$ATo)));
  }

?>