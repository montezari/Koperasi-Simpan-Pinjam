<?php

/******************************************************************
 * TBS Plus! TinyButStrong Plugin for TinyButStrong 3.2
 * By Ho Yiu YEUNG, aka Sheepy [ your.sheepy@gmail.com ]
 ******************************************************************
 * TinyButStrong by skrol29@freesurf.fr at www.tinybutstrong.com
 ******************************************************************/

/**
define('TBS_PLUS_SINGULAR_ZERO',true); // Use singular for zero in ope 'plural'

define('TBS_PLUS_NON_OPE', true); // Enable 1.0 non-ope syntax
define('TBS_PLUS_NO_OPE', true); // Disable 1.1 ope syntax
**/

if (!defined('NAN')) define('NAN', acos(1.01)); // Used in division by zero

define('TBS_TRIM_HTML','trim-html'); // Trim HTML
define('TBS_TRIM_OUTPUT','trim-output'); // Trim on output not on command
define('TBS_COMPRESS_OUTPUT','compress-output'); // Compress on output

//define('TBS_TRIM_NO_COMPRESS', 1); // Do not invoke ob_gzhandler
define('TBS_TRIM_EXTREME', 2); // Perform additonal trimming
define('TBS_TRIM_KEEP_LINE', 4); // Keep line break
define('TBS_TRIM_KEEP_COMMENT', 8); // Keep comment (may force keep line)


// Plugin stuff
define('TBS_PLUS', 'clsTbsPlugInPlus');
$GLOBALS['_TBS_AutoInstallPlugIns'][] = TBS_PLUS; // Auto-install

class clsTbsPlugInPlus {
  var $command = array();

  function OnInstall() {
    $this->Version = '1.1.0';
    $result = array (
      'OnCommand','BeforeShow'
    );
    if (!defined('TBS_PLUS_NO_OPE') || !TBS_PLUS_NO_OPE)
      array_unshift($result, 'OnOperation');
    if (defined('TBS_PLUS_NON_OPE') && TBS_PLUS_NON_OPE)
      array_unshift($result, 'OnFormat');
    return $result;
  }
  
  /** Call operation from php side
   * @param mixed $Value (Array of) Value to go through operation
   * @param mixed $ope (Array of) Operation to perform
   * @param mixed $param (Array of) Parameter of operation
   * @return mixed (Array of) Value after operation 
   */
  function Operation($Value, $ope, $param = null) {
    if (is_array($ope)) {
      foreach ($ope as $k => $o)
        $Value = $this->Operation($Value, $ope[$k], empty($param) ? null : $param[$k]);
      return $Value;
    } else {
      if (!ctype_alnum($ope)) $ope = rtrim(base64_encode($ope), '=');
      if (method_exists($this, $ope = "ope_$ope")) {
        $PrmLst = array('ope'=>"$ope=$param");
        $this->$ope('.php',$Value,$param,$PrmLst);
        return $Value;
      } else {
        trigger_error(htmlspecialchars("Operation $ope undefined"));
        return false; 
      }
    }
  }

  /** Provides ope syntax */
  function OnOperation($FieldName,&$Value,&$PrmLst,&$Txt,$PosBeg,$PosEnd,&$Loc) {
    static $last_ope = null; // Last operator
    $ope = trim($PrmLst['ope']);
    if (strpos($ope, '=') !== false) {
      list($ope, $param) = preg_split('~\s*=\s*~', $ope, 2);
    } else if ($last_ope) {
/*
      // Ope with no param ?
      if (in_array($ope, array(
        '-','abs','random','sqrt','floor','round','%','.%','-%',
        'lowercase','uppercase','titlecase','sizeof'
      ))) {
        $param = $ope;
        $ope = $last_ope;
      } else
      // Repeat / continue last operation ?
      if (is_numeric($ope) && !in_array($last_ope, array('+','-','*','/','-%','+%','^'))) {
        $param = $ope;
        $ope = $last_ope;
      } else {
        return;
      }
*/
      // Repeat operator only works with ope with numberic input.  So far.
      if (is_numeric($ope)) {
        $param = $ope;
        $ope = $last_ope;
      } else {
        $param = true;
      }
    } else {
      $param = true;
    }
    $real_ope = $ope;

    // Find and run operation.  Number of operations is getting a bit too large for switch..case
    if (!ctype_alnum($ope)) $ope = rtrim(base64_encode($ope), '=');
    if (method_exists($this, $ope = "ope_$ope")) {
      // One of our ope.
      $this->$ope($FieldName,$Value,$param,$PrmLst);
      $last_ope = $real_ope;
    } else {
      // Not our ope; cleanup.
      $last_ope = '';
    }
  } // function OnOperation


  /** Provides field param syntax */
  function OnFormat($FieldName, & $Value, & $PrmLst, & $TBS) {
    foreach ($PrmLst as $key => $param) {
      switch ($key = strtolower($key)) {
        case 'add': $key = '+'; break;
        case 'dec': $key = '-'; break;
        case 'multi': $key = '*'; break;
        case 'div': $key = '/'; break;
        case 'mod': $key = '%'; break;
        case 'power': $key = '^'; break;
        case 'neg': $key = '-'; break; // Negation
        case 'prepostfix' : $key = 'prepost'; break;
        case 'sizeof': $key = 'size'; break;
        case 'reversemagnet': $key = '!magnet'; break;
        case 'nonzeromagnet': $key = '!zeromagnet'; break;
        case 'nonemptymagnet': $key = '!emptmagnet'; break;
        case 'unequalmagnet': $key = '!equalmagnet'; break;
        case 'not': $key = '!'; break;
        case '2space': $key = '_'; break;
      } // switch ($key)

      $ope = $key;
      if (!ctype_alnum($key)) $ope = rtrim(base64_encode($ope), '=');
      if (method_exists($this, $ope = "ope_$ope"))
        $this->$ope($FieldName,$Value,$param,$PrmLst);
      continue;
    } // foreach ($PrmLst as $key => $param)
  } // function OnFormat


/*********************************************************/
/********************** Operations ***********************/
/*********************************************************/


  function ope_val($FieldName,&$Value,$param,&$PrmLst) {
    $Value = $param;  // Assignment
  }
  //           +
  function ope_Kw($FieldName,&$Value,$param,&$PrmLst) {
    if (!defined('TBS_PLUS_NON_OPE') || is_numeric($param)) //
      $Value += $param;  // Addition
    else
      foreach (explode(',',$param) as $val)
        $Value += $val;  // Multiple addition, possible with non-ope syntax
  }
  //           -
  function ope_LQ($FieldName,&$Value,$param,&$PrmLst) {
    if ($param===true)
      $Value = -$Value;  // Reversal if no param
    else if (!defined('TBS_PLUS_NON_OPE') || is_numeric($param))
      $Value -= $param;  // Decreasement
    else
      foreach (explode(',',$param) as $val)
        $Value -= $val;  // Multiple decreasement, possible with non-ope syntax
  }
  //           *
  function ope_Kg($FieldName,&$Value,$param,&$PrmLst) {
    $Value *= $param;  // Multiplication
  }
  //           /
  function ope_Lw($FieldName,&$Value,$param,&$PrmLst) {
    $Value = ($param) ? ($Value/$param) : NAN;  // Division
  }
  function ope_inv($FieldName,&$Value,$param,&$PrmLst) {
    $Value = ($Value) ? ($param/$Value) : NAN;  // Inversion
  }
  //           %
  function ope_JQ($FieldName,&$Value,$param,&$PrmLst) {
    if ($param===true) $Value *= 100; else $Value = $Value % $param;
  }
  //           .%
  function ope_LiU($FieldName,&$Value,$param,&$PrmLst) {
    $Value /= 100;
  }
  //           -%
  function ope_LSU($FieldName,&$Value,$param,&$PrmLst) {
    if ($param === true) { // Reverse percent
      if ($Value <= 1) $Value = 1-$Value;  // No parameter, reverse value
    } else {
      // Has parameter, apply reverse percent
      if ($param >= -1 && $param <= 1) $Value = $Value * ( 1-$param );
      else $Value = $Value * ( 100-$param )/100;
    }
  }
  //           +%
  function ope_KyU($FieldName,&$Value,$param,&$PrmLst) { // Add percent
    if ($param >= -1 && $param <= 1) $Value = $Value * ( 1+$param );
    else $Value = $Value * ( 100+$param )/100;

  }
  //           ^
  function ope_Xg($FieldName,&$Value,$param,&$PrmLst) {
    // Power to value
    if (!is_numeric($param)) return;
    if ($param == 0) $Value = NAN;
    else if ($param < 0) $Value = 1/pow($Value, -$param);
    else $Value = pow($Value, $param);
  }
  function ope_random($FieldName,&$Value,$param,&$PrmLst) {   // Random number of 1..(int)value
    if ($param === true) { // No parameter
      $Value = (int)$Value;
      $Value = mt_rand(min(1,$Value),max(1,$Value));
    } else {
      if (!is_numeric($param)) {
        $param = explode(':',$param);
        if (sizeof($param) == 2) $Value = mt_rand(min($param[0],$param[1]),max($param[0],$param[1]));
      } else {
        $param = (int)$param;
        if (is_int($Value)) { // Parameter + Value
          $Value = (int)$Value;
          $Value = mt_rand(min($Value,$param), max($Value,$param));
        } else  // Parameter with no value
          $Value = mt_rand(min(1,$param),max(1,$param));
      }
    }
  }
  function ope_sqrt($FieldName,&$Value,$param,&$PrmLst) {
    if ($Value >= 0) {
      if ($param === true)
        $Value = sqrt($Value);  // Square root
      else if ($param == 0) {
        $Value = NAN;
      } else {
        $param = 1/$param; // nth root
        $this->ope_Xg($FieldName, $Value, $param, $PrmLst);
      }
    } else if ($param != 0){
      $Value = -$Value;
      $this->ope_sqrt($FieldName, $Value, $param, $PrmLst);
      $Value .= 'i';
    } else {
      $Value = NAN;
    }
  }
  function ope_floor($FieldName,&$Value,$param,&$PrmLst) {
    $Value = floor($Value);  // Floor number
  }
  function ope_round($FieldName,&$Value,$param,&$PrmLst) {
    $Value = round($Value);  // Floor number
  }
  function ope_abs($FieldName,&$Value,$param,&$PrmLst) {
    if (is_numeric($Value)) $Value = abs($Value);
  }
  function ope_min($FieldName,&$Value,$param,&$PrmLst) {
    if ($Value < $param) $Value = $param;  // min($value, $param)
  }
  function ope_max($FieldName,&$Value,$param,&$PrmLst) {
    if ($Value > $param) $Value = $param;  // max($value, $param)
  }
  function ope_range($FieldName,&$Value,$param,&$PrmLst) {
    // Make sure $value is withing $param = (min,max)
    $param = explode(':',$param, 2);
    if (sizeof($param) == 2)
      $Value = min(max($param[0], $param[1]), max(min($param[0], $param[1]), $Value));
  }

  // Shortened if
  function ope_ifzero($FieldName,&$Value,$param,&$PrmLst) {
    if (is_numeric($Value) && !$Value) $Value = $param;  // If numeric and zero, set to param
  }
  function ope_ifpositive($FieldName,&$Value,$param,&$PrmLst) {
    if (is_numeric($Value) && $Value > 0) $Value = $param;  // If numeric and zero, set to param
  }
  function ope_ifnegative($FieldName,&$Value,$param,&$PrmLst) {
    if (is_numeric($Value) && $Value < 0) $Value = $param;  // If numeric and zero, set to param
  }
  function ope_ifempty($FieldName,&$Value,$param,&$PrmLst) {
    if (empty($Value)) $Value = $param;  // If empty, set to param
  }
  //           if!zero
  function ope_aWYhemVybw($FieldName,&$Value,$param,&$PrmLst) {
    if (is_numeric($Value) && $Value) $Value = $param;  // If numeric and not zero, set to param
  }
  //           if!empty
  function ope_aWYhZW1wdHk($FieldName,&$Value,$param,&$PrmLst) {
    if (!empty($Value)) $Value = $param;  // If not empty, set to param
  }

  // String operations
  function ope_substr($FieldName,&$Value,$param,&$PrmLst) {
    // Substring
    $param = explode(':', $param, 2);
    if (sizeof($param) == 1)
      $Value = substr($Value, $param[0]);
    else //if (sizeof($param) == 2)
      $Value = substr($Value, $param[0], $param[1]);
  }
  function ope_lowercase($FieldName,&$Value,$param,&$PrmLst) {
    $Value = strtolower($Value);
  }
  function ope_uppercase($FieldName,&$Value,$param,&$PrmLst) {
    $Value = strtoupper($Value);
  }
  function ope_titlecase($FieldName,&$Value,$param,&$PrmLst) {
    $Value = ucwords(strtolower($Value));
  }
  function ope_replace($FieldName,&$Value,$param,&$PrmLst) {
    // Substring
    $param = explode(':', $param, 2);
    if (sizeof($param) == 1)
      $Value = str_replace($param[0], '', $Value);
    else
      $Value = str_replace($param[0], $param[1], $Value);
  }
  function ope_repeat($FieldName,&$Value,$param,&$PrmLst) {
    if (is_string($Value)) $Value = str_repeat($Value, $param);  // str_repeat, count as param
  }
  function ope_repeatstr($FieldName,&$Value,$param,&$PrmLst) {
    if (is_string($param)) $Value = str_repeat($param, $Value);   // str_repeat, str as param
  }
  function ope_prefix($FieldName,&$Value,$param,&$PrmLst) {
     $Value = $param . $Value;  // Prefix
  }
  function ope_postfix($FieldName,&$Value,$param,&$PrmLst) {
     $Value = $Value . $param;  // Postfix
  }
  function ope_prepost($FieldName,&$Value,$param,&$PrmLst) {
     // pre + postfix
    $param = explode('|', $param, 2);
    if (sizeof($param) == 1)
      $Value = $param[0] . $Value . $param[0];
    else
      $Value = $param[0] . $Value . $param[1];
  }
  function ope_plural($FieldName,&$Value,$param,&$PrmLst) {
    if ($param === true)
      $param = array('s');
    else
      $param = explode('|',$param);
    switch (sizeof($param)) {
      case 0: $param[0] = 's'; // No param, use 's';  set param to s
      case 1: $param = array('', $param[0]); // One param, plural;  set singlar and plural
      case 2: // Two param, singlar and plural;  push nullar into front
        array_unshift($param, (defined('TBS_PLUS_SINGULAR_ZERO') && TBS_PLUS_SINGULAR_ZERO) ? $param[0] : $param[1]);
      case 3: array_push($param, $param[2]); // Three param: Nullar, Singlar, and Plural;  push dual in
      case 4: //  Nullar, Singlar, Dual, Plural
        $nullar = $param[0];
        $single = $param[1];
        $dual   = $param[2];
        $plural = $param[3];
        break;
      default:
        trigger_error('[TinyButStrong Plus Error] ope plural has invalid parameter '.htmlspecialchars(implode('|',$param)), E_USER_WARNING);
        return; // Something is wrong.
    }
    if (!$Value) $Value = $nullar;
    else if ($Value == 2) $Value = $dual;
    else if ($Value == 1) $Value = $single;
    else $Value = $plural;
  }

  // Magnet functions
  function ope_magnet($FieldName,&$Value,$param,&$PrmLst) {
    if ($param !== true) $PrmLst['magnet']=$param;
  }
  //           !magnet
  function ope_IW1hZ25ldA($FieldName,&$Value,$param,&$PrmLst) {
    $Value = ($Value === '') ? ' ' : '';  // Change '' to space and everything else to ''
    if ($param !== true && !$Value) $PrmLst['magnet']=$param;
  }
  function ope_zeromagnet($FieldName,&$Value,$param,&$PrmLst) {
    if (is_numeric($Value) && !(float)$Value) { $Value='';  // Change to '' if zero
    if ($param !== true) $PrmLst['magnet']=$param; }
  }
  //           !zeromagnet
  function ope_IXplcm9tYWduZXQ($FieldName,&$Value,$param,&$PrmLst) {
    if (is_numeric($Value) && (float)$Value) { $Value='';  // Change to '' if number but not zero
    if ($param !== true && !$Value) $PrmLst['magnet']=$param; }
  }
  function ope_emptymagnet($FieldName,&$Value,$param,&$PrmLst) {
    if (empty ($Value))  { $Value = '';  // Change to '' if empty
    if ($param !== true) $PrmLst['magnet']=$param; }
  }
  //           !emptymagnet
  function ope_IWVtcHR5bWFnbmV0($FieldName,&$Value,$param,&$PrmLst) {
    if (!empty($Value)) $Value = '';  // Change to '' if something (non-empty)
    if ($param !== true) $PrmLst['magnet']=$param;
  }
  function ope_boolmagnet($FieldName,&$Value,$param,&$PrmLst) {
    $Value = (!$Value ? '' : ' ');  // Change to '' if false, ' ' otherwise
    if ($param !== true && !$Value) $PrmLst['magnet']=$param;
  }
  //           !boolmagnet
  function ope_IWJvb2xtYWduZXQ($FieldName,&$Value,$param,&$PrmLst) {
    $Value = ($Value ? '' : ' ');  // Change to '' if true, ' ' otherwise
    if ($param !== true && !$Value) $PrmLst['magnet']=$param;
  }
  function ope_equalmagnet($FieldName,&$Value,$param,&$PrmLst) {
    if ($Value == $param) $Value = '';  // Change to '' if equal to param
  }
  //           !equalmagnet
  function ope_IWVxdWFsbWFnbmV0($FieldName,&$Value,$param,&$PrmLst) {
    if ($Value != $param) $Value = '';  // Change to '' if equal to param
  }
  function ope_inarraymagnet($FieldName,&$Value,$param,&$PrmLst) {
    if (is_array($Value) && in_array($param, $Value)) $Value = '';  // Change to '' if equal to param
  }
  //           !inarraymagnet
  function ope_IWluYXJyYXltYWduZXQ($FieldName,&$Value,$param,&$PrmLst) {
    if (is_array($Value) && !in_array($param, $Value)) $Value = '';  // Change to '' if equal to param
  }
  function ope_filemagnet($FieldName,&$Value,$param,&$PrmLst) {
    if (!file_exists($Value)) { $Value = '';   // Change to '' if $value does not exist as a file
    if ($param !== true) $PrmLst['magnet']=$param; }
  }
  //           !filemagnet
  function ope_IWZpbGVtYWduZXQ($FieldName,&$Value,$param,&$PrmLst) {
    if (file_exists($Value)) { $Value = '';  // Change to '' if $value exist as a file
    if ($param !== true) $PrmLst['magnet']=$param; }
  }
  function ope_negativemagnet($FieldName,&$Value,$param,&$PrmLst) {
    if (is_numeric($Value) && $Value < 0) { $Value = '';  // Change to '' if $value is numeric and negative
    if ($param !== true) $PrmLst['magnet']=$param; }
  }
  //           !negativemagnet
  function ope_IW5lZ2F0aXZlbWFnbmV0($FieldName,&$Value,$param,&$PrmLst) {
    if (is_numeric($Value) && $Value >= 0) { $Value = '';  // Change to '' if $value is numeric and not negative
    if ($param !== true) $PrmLst['magnet']=$param; }
  }
  function ope_positivemagnet($FieldName,&$Value,$param,&$PrmLst) {
    if (is_numeric($Value) && $Value > 0) { $Value = '';  // Change to '' if $value is numeric and positive
    if ($param !== true) $PrmLst['magnet']=$param; }
  }
  //           !positivemagnet
  function ope_IXBvc2l0aXZlbWFnbmV0($FieldName,&$Value,$param,&$PrmLst) {
    if (is_numeric($Value) && $Value <= 0) { $Value = '';  // Change to '' if $value is numeric and not positive
    if ($param !== true) $PrmLst['magnet']=$param; }
  }

  // Array functions
  function ope_explode($FieldName,&$Value,$param,&$PrmLst) {
    if (is_string($Value)) $Value = explode(($param!==true)?$param:',', $Value);  // Explode value using given separator
  }
  function ope_implode($FieldName,&$Value,$param,&$PrmLst) {
    if (is_array($Value)) $Value = implode(($param!==true)?$param:',', $Value);  // Implode value using given separator
  }
  function ope_size($FieldName,&$Value,$param,&$PrmLst) {
    if (is_array($Value)) $Value = sizeof($Value); else if (is_string($Value)) $Value = strlen($Value);   // Size of array or string
  }

  // Conversion functions
  //           !
  function ope_IQ($FieldName,&$Value,$param,&$PrmLst) {
     $Value = !$Value;
  }
  function ope_2bool($FieldName,&$Value,$param,&$PrmLst) {
    // False if zero, empty, null, 'No', 'N', 'False', 'F', true otherwise
    $Value = (!$Value || in_array(strtolower($Value), array('n','no','f','false'))) ? false : true;
  }
  //           _
  function ope_Xw($FieldName,&$Value,$param,&$PrmLst) {
    if ($Value != '') $Value = ' ';  // Space if non-blank
  }
  function ope_empty2zero($FieldName,&$Value,$param,&$PrmLst) {
    if (empty ($Value)) $Value = 0;  // Make value zero if it's empty
  }
  function ope_empty2blank($FieldName,&$Value,$param,&$PrmLst) {
    if (empty ($Value)) $Value = '';  // Make value '' if it's empty
  }
  function ope_empty2space($FieldName,&$Value,$param,&$PrmLst) {
    if ($Value !== '') $Value = ' ';  // Change to space if not ''
  }

  // Other helper functions
  function ope_func($FieldName,&$Value,$param,&$PrmLst) {
    $Value = $param($Value);  // Call function
  }
  function ope_between($FieldName,&$Value,$param,&$PrmLst) {
    list($left,$right)=explode(':',$param,2);
    $min = min($left,$right); $max = max($left,$right);
    $Value = $Value <= $max && $Value >= $min;
  }

  function ope_randomfile($FieldName,&$Value,$param,&$PrmLst) {
    // Using value as file pattern, randomly pick a file, or return '' if no file found
    if ($param === true) $param = $Value;
    $list = glob($param, GLOB_NOESCAPE + GLOB_BRACE + GLOB_NOSORT);
    $Value = empty ($list) ? '' : $param= str_replace('\\', '/', $list[mt_rand(0, sizeof($list) - 1)]);
  }
  function ope_missingfile($FieldName,&$Value,$param,&$PrmLst) {
    if (!file_exists($Value)) $Value = $param;  // If value as a file does not exist, replace with parameter
  }
  function ope_htmlchecked($FieldName,&$Value,$param,&$PrmLst) {
    $Value = $Value ? 'checked=\'checked\'' : $Value = '';  // If not empty, change value to 'checked'
  }
  function ope_zebra($FieldName,&$Value,$param,&$PrmLst) {  // Merge alternatively with parameters
    static $zebra = array ();
    $hash = sha1($FieldName.$param);
    if (!isset ($zebra[$hash])) {
      $zebra[$hash] = array (
        'values' => explode('|', $param),
        'index' => 0,
        );
      $z = & $zebra[$hash];
      $index = 0;
    } else {
      $z = & $zebra[$hash];
      $index = $z['index'] = ++$z['index'] % sizeof($z['values']);
    }
    $Value = $z['values'][$index];
  }



/*********************************************************/
/************************ Command ************************/
/*********************************************************/


  function OnCommand($cmd,$param=null) {
    switch($cmd) {
      case TBS_TRIM_HTML: // Trim html now or on show
        if (isset($this->command[TBS_TRIM_OUTPUT])) {
          $this->command[TBS_TRIM_OUTPUT] = 'html';
          $this->command[TBS_TRIM_HTML] = $param;
        } else
          $this->_trim($param);
        break;
      case TBS_TRIM_OUTPUT: // Set output mode on/off
        if ($param || $param === null)
          $this->command[TBS_TRIM_OUTPUT] = 'html';
        else
          unset($this->command[TBS_TRIM_OUTPUT]);
        break;
      case TBS_COMPRESS_OUTPUT:
        if ($param)
          $this->command[TBS_COMPRESS_OUTPUT] = $param;
        else          unset($this->command[TBS_COMPRESS_OUTPUT]);
    }
  }

  function BeforeShow(&$Render) {
    if (isset($this->command[TBS_COMPRESS_OUTPUT]))
      $this->_compress_output();
    if (!isset($this->command[TBS_TRIM_OUTPUT])) return true;
    // Trim on output is true, do it now before output
    if (isset($this->command[TBS_TRIM_HTML]))
      $this->_trim($this->command[TBS_TRIM_HTML]);
    else
      $this->_trim();
    return true;
  }

  function _compress_output($param=null) {
    // Enable compression if...
    // 1. It is not too late, already done, or unavailable, and...
    if (!headers_sent() && @ini_get('zlib.output_compression') != '1' && @ini_get('output_handler') != 'ob_gzhandler' && @version_compare(PHP_VERSION, '4.2.0') != -1)
      // 2. It's not unpatched IE version
      if (!strpos(@$_SERVER['HTTP_USER_AGENT'], ' (compatible; MSIE 6.') || strpos(@$_SERVER['HTTP_USER_AGENT'], '; SV1'))
        ob_start('ob_gzhandler');
  }


/*********************************************************/
/************************* Trim **************************/
/*********************************************************/


  function _trim($param=null) {
    $tbs = & $this->TBS;
    if ($tbs->Source)
      $tbs->Source = $this->trimHTML($tbs->Source, $param);
  }

  function _trim_space($src, $keep_linebreak) {
    if (!$keep_linebreak)
      return preg_replace('~\s{2,}|\n~',' ', $src);
    else
      return preg_replace('~[ \t]{2,}~',' ',preg_replace('~\s*[\r\n]+(\s*[\r\n])*\s*~',"\n", $src));
  }

  function _trim_xml($src, $keep_linebreak, $extreme) {
    $src = $this->_trim_space($src, $keep_linebreak);
    if ($extreme) $src = str_replace('> <', '><', $src);
    return $src;
  }

  function _trim_script($src, $keep_linebreak, $keep_comment, $extreme) {
    if (!$keep_comment) {
      // Remove single line comment
      $src = preg_replace('~(?<!:)/'.'/\s*[^\r\n<]*[\r\n]~',' ', $src);
      $src = preg_replace('~\s*/\*.*?\*/\s*~s',"\n", $src); // Remove block comment
    } else {
      if ($keep_linebreak) // Convert line comment to block comment
        $src = preg_replace('~(?<!:)/'.'/(\s*[^\r\n<]*)[\r\n]~','/*$1*/', $src);
    }
    $src = $this->_trim_space($src, $keep_linebreak);
    if ($extreme)
      $src = preg_replace('~ *([;{}\(\)\[\]=]) *~', '$1', $src);
    return $src;
  }

  function _trim_style($src, $keep_linebreak, $keep_comment, $extreme) {
    if (!$keep_comment)
      $src = preg_replace('~\s*/\*.*?\*/\s*~s',"\n", $src); // Remove block comment
    $src = $this->_trim_space($src, $keep_linebreak);
    if ($extreme)
      $src = preg_replace('~ *([:;{}\(\)]) *~', '$1', $src);
    return $src;
  }

  /** Trim comments and whitespace from HTML */
  function trimHTML($src, $option = null) {
    // Normalise & parse options
    if ($option === true) $option = null;
    $keep_linebreak = $option & TBS_TRIM_KEEP_LINE;
    $keep_comment = $option & TBS_TRIM_KEEP_COMMENT;
    $extreme = $option & TBS_TRIM_EXTREME;
    $found_script = false; // <!--Javascript--> needs special restoration

    // Initialise
    $matches = null;
    $left = 0;
    $result = '';

    // Remove anything not conditional comment or script
    if (!$keep_comment) $src = preg_replace('~\s*<!--[^[](?>.*?-->\s*)(?!</style|</scrip)~s', '', $src);

    // Trim section by section
    $length = strlen($src);
    while ($left < $length) {
      // Special section?
      if (preg_match("~<(pre|script|style)\W~i", $src, $matches, PREG_OFFSET_CAPTURE, $left)) {

        // Process whatever comes before escape tags
        $right = $matches[0][1];
        $tag = $matches[1][0];
        $result .= $this->_trim_xml(substr($src, $left, $right-$left), $keep_linebreak, $extreme);
        $left = $right;

        if (preg_match("~</$tag\W~i", $src, $matches, PREG_OFFSET_CAPTURE, $right)) {
          // Close tag found. Process whatever between open tag and close tag
          $right = $matches[0][1];
          if ($tag == 'style') {
            $result .= $this->_trim_style(substr($src, $left, $right-$left), $keep_linebreak, $keep_comment, $extreme);
          } else if ($tag == 'script') {
            $result .= $this->_trim_script(substr($src, $left, $right-$left), $keep_linebreak, $keep_comment, $extreme);
            $found_script = true;
          } else // pre
            $result .= substr($src, $left, $right-$left);
          $left = $right;
        } else {
          // Closing tag not found, just copy whatever remains
          $result .= substr($src, $left);
          break;
        }

      } else {
        // Nothing special, sweet~
        $result .= $this->_trim_xml(substr($src, $left), $keep_linebreak, $extreme);
        break;
      }
    }

    // Restore javascript surrounding comment
    if ($found_script) {
      $result = preg_replace('~<!--[^[\n]~', "<!--\n", $result);
      $result = preg_replace('~(?<!/'.'/)\s*-->\s*</script>~', '/'.'/--></script>', $result);
    }

    return $result;
  }
}

?>
