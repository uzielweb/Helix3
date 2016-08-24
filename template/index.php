<?php
/**
 * @package Helix3 Framework
 * Template Name - Shaper Helix3
 * @author JoomShaper http://www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2015 JoomShaper
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
*/
//no direct accees
defined ('_JEXEC') or die ('resticted aceess');

$doc = JFactory::getDocument();
$doc = JFactory::getDocument();
$app = JFactory::getApplication();

// Obtém o Item de Menu atualmente ativo
// Retrieve the current active menu item
$currentMenuItem = $app->getMenu()->getActive();

// Obtém os parâmetros do Item de Menu atualmente ativo
// Retrieve the params of the current active menu item
$params = $currentMenuItem->params;

// Acessa o parâmetro necessário do Item de Menu atualmente ativo
// Retrieve the needed param from current active menu item
$pageclass = $params->get('pageclass_sfx');

JHtml::_('jquery.framework');
JHtml::_('bootstrap.framework'); //Force load Bootstrap
unset($doc->_scripts[$this->baseurl . '/media/jui/js/bootstrap.min.js']); // Remove joomla core bootstrap

//Load Helix
$helix3_path = JPATH_PLUGINS.'/system/helix3/core/helix3.php';

if (file_exists($helix3_path)) {
    require_once($helix3_path);
    $this->helix3 = helix3::getInstance();
} else {
    die('Please install and activate helix plugin');
}

//Coming Soon
if($this->helix3->getParam('comingsoon_mode')) header("Location: ".$this->baseUrl."?tmpl=comingsoon");

//Class Classes
$body_classes = '';

if($this->helix3->getParam('sticky_header')) {
    $body_classes .= ' sticky-header';
}

$body_classes .= ($this->helix3->getParam('boxed_layout', 0)) ? ' layout-boxed' : ' layout-fluid';

//Body Background Image
if($bg_image = $this->helix3->getParam('body_bg_image')) {

    $body_style  = 'background-image: url(' . JURI::base(true ) . '/' . $bg_image . ');';
    $body_style .= 'background-repeat: '. $this->helix3->getParam('body_bg_repeat') .';';
    $body_style .= 'background-size: '. $this->helix3->getParam('body_bg_size') .';';
    $body_style .= 'background-attachment: '. $this->helix3->getParam('body_bg_attachment') .';';
    $body_style .= 'background-position: '. $this->helix3->getParam('body_bg_position') .';';
    $body_style  = 'body.site {' . $body_style . '}'; 

    $doc->addStyledeclaration( $body_style );
}

//Body Font
$webfonts = array();

if( $this->params->get('enable_body_font') ) {
    $webfonts['body'] = $this->params->get('body_font');
}

//Heading1 Font
if( $this->params->get('enable_h1_font') ) {
    $webfonts['h1'] = $this->params->get('h1_font');
}

//Heading2 Font
if( $this->params->get('enable_h2_font') ) {
    $webfonts['h2'] = $this->params->get('h2_font');
}

//Heading3 Font
if( $this->params->get('enable_h3_font') ) {
    $webfonts['h3'] = $this->params->get('h3_font');
}

//Heading4 Font
if( $this->params->get('enable_h4_font') ) {
    $webfonts['h4'] = $this->params->get('h4_font');
}

//Heading5 Font
if( $this->params->get('enable_h5_font') ) {
    $webfonts['h5'] = $this->params->get('h5_font');
}

//Heading6 Font
if( $this->params->get('enable_h6_font') ) {
    $webfonts['h6'] = $this->params->get('h6_font');
}

//Navigation Font
if( $this->params->get('enable_navigation_font') ) {
    $webfonts['.sp-megamenu-parent'] = $this->params->get('navigation_font');
}

//Custom Font
if( $this->params->get('enable_custom_font') && $this->params->get('custom_font_selectors') ) {
    $webfonts[ $this->params->get('custom_font_selectors') ] = $this->params->get('custom_font');
}

$this->helix3->addGoogleFont($webfonts);

//Custom CSS
if($custom_css = $this->helix3->getParam('custom_css')) {
    $doc->addStyledeclaration( $custom_css );
}

//Custom JS
if($custom_js = $this->helix3->getParam('custom_js')) {
    $doc->addScriptdeclaration( $custom_js );
}

?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php
    if($favicon = $this->helix3->getParam('favicon')) {
        $doc->addFavicon( JURI::base(true) . '/' .  $favicon);
    } else {
        $doc->addFavicon( $this->helix3->getTemplateUri() . '/images/favicon.ico' );
    }
    ?>

    <jdoc:include type="head" />
    
    <script>
        jQuery(document).ready(function() {
            getWidthAndHeight();
        });
    // make sure div stays full width/height on resize
    jQuery(window).resize(function() {
        getWidthAndHeight();
    });
    function getWidthAndHeight (){
        //var winWidth = jQuery(window).width();
        var winHeight = jQuery(window).height();
        jQuery('.slideshowck.camera_wrap').css({'min-height': winHeight,'max-height': winHeight,});
}
    </script>
    <?php

    $this->helix3->addCSS('bootstrap.min.css, font-awesome.min.css') // CSS Files
        ->addJS('bootstrap.min.js, jquery.sticky.js, main.js') // JS Files
        ->lessInit()->setLessVariables(array(
            'preset'=>$this->helix3->Preset(),
            'bg_color'=> $this->helix3->PresetParam('_bg'),
            'text_color'=> $this->helix3->PresetParam('_text'),
            'major_color'=> $this->helix3->PresetParam('_major')
            ))
        ->addLess('legacy/bootstrap', 'legacy')
        ->addLess('master', 'template');

        //RTL
        if($this->direction=='rtl') {
            $this->helix3->addCSS('bootstrap-rtl.min.css')
            ->addLess('rtl', 'rtl');
        }

        $this->helix3->addLess('presets',  'presets/'.$this->helix3->Preset(), array('class'=>'preset'));
        
        //Before Head
        if($before_head = $this->helix3->getParam('before_head')) {
            echo $before_head . "\n";
        }
    ?>
</head>
<body class="<?php echo $this->helix3->bodyClass( $body_classes ).$pageclass; ?>">
    <div class="body-innerwrapper">
        <?php $this->helix3->generatelayout(); ?>

        <div class="offcanvas-menu">
            <a href="#" class="close-offcanvas"><i class="fa fa-remove"></i></a>
            <div class="offcanvas-inner">
                <?php if ($this->helix3->countModules('offcanvas')) { ?>
                    <jdoc:include type="modules" name="offcanvas" style="sp_xhtml" />
                <?php } else { ?>
                    <p class="alert alert-warning"><?php echo JText::_('HELIX_NO_MODULE_OFFCANVAS'); ?></p>
                <?php } ?>
            </div>
        </div>
    </div>
    <?php
    
    if($this->params->get('compress_css')) {
        $this->helix3->compressCSS();
    }

    if($this->params->get('compress_js')) {
        $this->helix3->compressJS( $this->params->get('exclude_js') );
    }

    if($before_body = $this->helix3->getParam('before_body')) {
        echo $before_body . "\n";
    }

    ?>
    <jdoc:include type="modules" name="debug" />
</body>
</html>
