<?php
    $qttHashtag = preg_match_all('/#(\w)*/', $textofoto, $matches);

    foreach ($matches[0] as $tag) {
        $tag2 = $tag;
        $tag2 = str_replace("#", "", $tag);
        $replace = '<a class="linkhashtag"href="./hashtag.php?hashtag=' . $tag2 . '">' . $tag . '</a>';
        $textofoto = str_replace($tag, $replace, $textofoto);
    }

?>