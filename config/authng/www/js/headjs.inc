<?php

function getHeadJS() {
  global $_SERVER, $HTTP_SERVER_VARS, $g, $use_loader_tab_gif;

  if(!$use_loader_tab_gif)
    $loader_gif = "/themes/{$g['theme']}/images/misc/loader.gif";
  else
    $loader_gif = "/themes/{$g['theme']}/images/misc/loader_tab.gif";

  $headjs = "
    var input_errors = '';
    Event.observe(window, 'load', init, false);
  ";

  $_SESSION['NO_AJAX'] == "True" ? $noajax = "var noAjaxOnSubmit = true;" : $noajax = "var noAjaxOnSubmit = false;";

  $headjs .= "
    {$noajax}

    function init() {
      if($('submit') && ! noAjaxOnSubmit) {
        // debugging helper
        //alert('adding observe event for submit button');
        
        Event.observe(\"submit\", \"click\", submit_form, false);
        $('submit').onclick = function() {return false;};
        var to_insert = \"<div style='visibility:hidden' id='loading' name='loading'><img src='{$loader_gif}' \/><\/div>\";
        new Insertion.Before('submit', to_insert);
      }
    }
    
    function submit_form(e){
      // debugging helper
      //alert(Form.serialize($('iform')));
      
      if($('inputerrors'))
        $('inputerrors').innerHTML = '';
        
      /* dsh: Introduced because pkg_edit tries to set some hidden fields
       *      if executing submit's onclick event. Tho click gets deleted
       *      by Ajax. Hence using onkeydown instead.
       */
      if($('submit') && $('submit').onkeydown)
        $('submit').onkeydown();
      if($('submit'))
        $('submit').style.visibility = 'hidden';
      if($('cancelbutton'))
        $('cancelbutton').style.visibility = 'hidden';
      $('loading').style.visibility = 'visible';
      // submit the form using Ajax
  ";
  
  
  isset($HTTP_SERVER_VARS['AUTH_USER']) ? $scriptName = split("/", $_SERVER["SCRIPT_FILENAME"]) : $scriptName = split("/", "/index.php");
  isset($HTTP_SERVER_VARS['AUTH_USER']) ? $loggedin = "var isLoggedIn = true;" : $loggedin = "var isLoggedIn = false;";
  $scriptElms = count($scriptName);
  $scriptName = $scriptName[$scriptElms-1];
  $realScriptName = $_SERVER["SCRIPT_NAME"];

  $headjs .= "
       {$loggedin}

      if (! isLoggedIn) {
        var newInput = document.createElement('input');
        newInput.setAttribute('id', 'scriptname');
        newInput.setAttribute('name', 'scriptname');
        newInput.setAttribute('value', '$realScriptName');
        newInput.setAttribute('type', 'hidden');

        $('iform').appendChild(newInput);
      }

      new Ajax.Request('{$scriptName}', {
                method     : 'post',
                parameters : Form.serialize($('iform')),
                onSuccess  : formSubmitted,
                onFailure  : formFailure
      });
    }
   
    function formSubmitted(resp) {
      var responseText = resp.responseText;
      
      // debugging helper
      //alert(responseText);
      
      if(responseText.indexOf('html') > 0) {
        /* somehow we have been fed an html page! */
        //alert('Somehow we have been fed an html page! Forwarding to /.');
        document.location.href = '/';
      }
      
      eval(responseText);
    }
    
    /* this function will be called if an HTTP error will be triggered */
    function formFailure(resp) {
      alert('An error occured while saving the data ' + resp.responseText);
    }
    
    function showajaxmessage(message) {
      var message_html;

      if (message == '') {
        NiftyCheck();
        Rounded(\"div#redbox\",\"all\",\"#FFF\",\"#990000\",\"smooth\");
        Rounded(\"td#blackbox\",\"all\",\"#FFF\",\"#000000\",\"smooth\");

        if($('submit'))
          $('submit').style.visibility = 'visible';
        if($('cancelbutton'))
          $('cancelbutton').style.visibility = 'visible';
        if($('loading'))
          $('loading').style.visibility = 'hidden';

        return;
      }

      message_html = '<table height=\"32\" width=\"100%\"><tr><td>';
      message_html += '<div style=\"background-color:#990000\" id=\"redbox\">';
      message_html += '<table width=\"100%\"><tr><td width=\"8%\">';
      message_html += '&nbsp;&nbsp;&nbsp;';
      message_html += '<img style=\"vertical-align:middle\" src=\"/themes/{$g['theme']}/images/icons/icon_exclam.gif\" width=\"28\" height=\"32\" \/>';
      message_html += '<\/td><td width=\"70%\"><font color=\"white\">';
      message_html += '<b>' + message + '<\/b><\/font><\/td>';

      if(message.indexOf('apply') > 0) {
        message_html += '<td>';
        message_html += '<input name=\"apply\" type=\"submit\" class=\"formbtn\" id=\"apply\" value=\"" . gettext("Apply changes") . "\" \/>';
        message_html += '<\/td>';
      }

      message_html += '<\/tr><\/table><\/div><\/td><\/table><br \/>';
      $('inputerrors').innerHTML = message_html;

      NiftyCheck();
      Rounded(\"div#redbox\",\"all\",\"#FFF\",\"#990000\",\"smooth\");
      Rounded(\"td#blackbox\",\"all\",\"#FFF\",\"#000000\",\"smooth\");

      if($('submit'))
        $('submit').style.visibility = 'visible';
      if($('cancelbutton'))
        $('cancelbutton').style.visibility = 'visible';
      if($('loading'))
        $('loading').style.visibility = 'hidden';
      if($('inputerrors'))
        window.scrollTo(0, 0);
      if($('inputerrors'))
        new Effect.Shake($('inputerrors'));
    }
  ";

  return $headjs;
}

?>
