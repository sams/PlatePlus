# Plate Plus Plugin

Currently this plugin contains helpers that can be used to apply 
a range of sweet features to projects baked with BakingPlate.

## The Plate Helper

**That rug really tied the room together, man!**

`var $helpers = array('PlatePlus.Plate')`;

Its a helper that adds a number of features to apps and leaves 
your layouts clean & basic.

It *will*  wrap in support for other cake plugins (Media, Asset Compress).
It currently wraps in support for the HtmlPlus Helper - which is optional 
and if used will output html5 markup (or html 4.5, xhtml5 with minor switches)

## Html5 Boilerplate standards

One of the primary intentions of the plate helper is to equip cake views & helpers with 
methods to make implementing the ideology of *Paul Irish & Divya Manian's* **Html5 Boilerplate** 
the additional plugins are required to achieve this goal.

* Multi Html Conditional comments
* ChromeFrame meta
* Drew Diller's PNG Fix
* JsLib with CDN Fallback (shown below)
* Google Anlaytics script block

eg to output a scritpt source using  jquery from google hosted api (with a local fallback)
`$this->Plate->jsLib()`

* change version
* control minification
* load other js libs including Dojo, MooTool, Protype or SWFObject.
* use other content deployment networks such as Microsoft, jQuery or custom 

todo:
* jquery ui
* additional support for clientside dev/build tool

The plate plugin also includes *Chris Yure's* **Capture Element concept** 
which can be used to create vars for use in *layouts* from elements (or outputted markup) 
from within the *view*.  Also it can be used to construct markup to be passed to method 
calls.


## Using the Plus Helpers

`var $helpers = array('Analogue.Analogue' => array('PlatePlus.HtmlPlus' => 'Html', 'PlatePlus.FormPlus' => 'Form'))`;

### Html Plus

this will output a doctype, html tag & charset
`echo $this->Html->start();`

this will output  a section (falling back to div with 'section' class for non (x)html5 output)
`echo $this->Html->section($section, $headers);`

@todo video, audio, source, mark, time, sectionize

### Form Plus
@todo

### Made for BakingPlate (but can be used with cake apps in general).
