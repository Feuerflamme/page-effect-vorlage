<?php


function postid_to_url($postId = '')
{
  $url = get_permalink($postId);
  $title = get_the_title($postId);
  return "<a href='$url' class='btn btn--empty' target='' data-postid='$postId'>$title</a>";
}
function acf_relative_path($link = "", $buttontype = "empty")
{
  // link exist 
  if (isset($link) && isset($link["url"])) {
    // if internal link
    if (wp_is_internal_link($link["url"])) {
      // only relative path
      $url = esc_url(parse_url($link["url"], PHP_URL_PATH));
    }
    // if external link
    else {
      // full path
      $url = esc_url($link["url"]);
    }


    // this is for style
    if ($buttontype == "fill"  || $buttontype == "empty") {
      $class  = ' class="btn btn--' . $buttontype . '"';
    }
    // if link
    else if ($buttontype == "link") {
      $class  = ' class="link link--underline"';
    }

    $target = ($link['target']) ? 'target="' . $link['target'] . '"' : false;
    $rel    = ($link['target'] === "_blank") ? ' rel="noopener noreferrer"' : false;
    $title  = $link['title'] ? $link['title'] : "No Title";

    return "<a href='$url'$class$target$rel>$title</a>";
  }

  return "";
}
