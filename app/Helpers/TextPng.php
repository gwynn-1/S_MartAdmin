<?php
namespace App\Helpers;
Header("Content-type: image/png");
class TextPng
{
    public $font = ''; //default font. directory relative to script directory.
    public $msg = "no text"; // default text to display.
    public $size = 24; // default font size.
    public $width = 600; // default width size.
    public $height = 360; // default height size.
    public $rot = 0; // rotation in degrees.
    public $pad = 8; // padding.
    public $transparent = 1; // transparency set to on.
    public $white = 0; // black text...
    public $red = 0; // black text...
    public $grn = 0;
    public $blu = 0;
    public $bg_red = 255; // on white background.
    public $bg_grn = 255;
    public $bg_blu = 255;
    public $pathImg = false;
    public $nameImg = false;

    public function __construct()
    {

        $vendorDir = dirname(dirname(__FILE__));
        $baseDir = dirname($vendorDir);
        $font = realpath('fonts/font.ttf');
        $this->font = $font;
    }

    public function draw()
    {
        $width = 0;
        $height = 0;
        $offset_x = 0;
        $offset_y = 0;
        $bounds = array();
        $image = "";

        // get the font height.
        $bounds = ImageTTFBBox($this->size, $this->rot, $this->font, "W");
        if ($this->rot < 0)
        {
            $font_height = abs($bounds[7]-$bounds[1]);
        }
        else if ($this->rot > 0)
        {
            $font_height = abs($bounds[1]-$bounds[7]);
        }
        else
        {
            $font_height = abs($bounds[7]-$bounds[1]);
        }
        // determine bounding box.
        $bounds = ImageTTFBBox($this->size, $this->rot, $this->font, $this->msg);

        if ($this->rot < 0)
        {
            $width = abs($bounds[4]-$bounds[0]);
            $height = abs($bounds[3]-$bounds[7]);
            $offset_y = $font_height;
            $offset_x = 0;
        }
        else if ($this->rot > 0)
        {
            $width = abs($bounds[2]-$bounds[6]);
            $height = abs($bounds[1]-$bounds[5]);
            $offset_y = abs($bounds[7]-$bounds[5])+$font_height;
            $offset_x = abs($bounds[0]-$bounds[6]);
        }
        else
        {
            $width = abs($bounds[4]-$bounds[6]);
            $height = abs($bounds[7]-$bounds[1]);
            $offset_y = $font_height;
            $offset_x = 0;
        }

        //set width
        if($this->width)
           $width =  $this->width;

        //line break
        $text = wordwrap($this->msg, ($width/10));
        $lines = explode("\n", $text);

        $height = $height * count($lines);

        $image = imagecreate($width+($this->pad*2)+1,$height+($this->pad*2)+1);
        $background = ImageColorAllocate($image, $this->bg_red, $this->bg_grn, $this->bg_blu);
        $foreground = ImageColorAllocate($image, $this->red, $this->grn, $this->blu);

        if ($this->transparent) ImageColorTransparent($image, $background);
        ImageInterlace($image, false);


        // render the image
//        ImageTTFText($image, $this->size, $this->rot, $offset_x+$this->pad, $offset_y+$this->pad, $foreground, $this->font, $this->msg);
        $i = 0;
        foreach ($lines as $text)
        {
            ImageTTFText($image, $this->size, $this->rot, $offset_x+$this->pad, $offset_y +$i + $this->pad, $foreground, $this->font, $text);
            $i = $i + $offset_y + $this->pad + 10;
        }
        // output PNG object.
        if($this->pathImg)
        {
            imagePNG($image,$this->pathImg.'/'.$this->nameImg);
        }
        else
        {
            imagePNG($image);
        }

    }
}