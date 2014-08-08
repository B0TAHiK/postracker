<?php

/* base.tpl */
class __TwigTemplate_bbb70b02da4af4909c16418433de9ef6c16fe3a4389901e24e393c5727764be4 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
            'head' => array($this, 'block_head'),
            'title' => array($this, 'block_title'),
            'header' => array($this, 'block_header'),
            'pageName' => array($this, 'block_pageName'),
            'content' => array($this, 'block_content'),
            'footer' => array($this, 'block_footer'),
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        echo "<!DOCTYPE html>
<html>
    <head>
        ";
        // line 4
        $this->displayBlock('head', $context, $blocks);
        // line 17
        echo "    </head>
    <body>
        ";
        // line 19
        $this->displayBlock('header', $context, $blocks);
        // line 20
        echo "        <div class=\"container\">
        <div class=\"page-header\">
        <h1>";
        // line 22
        $this->displayBlock('pageName', $context, $blocks);
        echo "</h1>
        </div>
            ";
        // line 24
        $this->displayBlock('content', $context, $blocks);
        // line 26
        echo "        </div>
        ";
        // line 27
        $this->displayBlock('footer', $context, $blocks);
        // line 39
        echo "    </body>
</html>";
    }

    // line 4
    public function block_head($context, array $blocks = array())
    {
        // line 5
        echo "        <meta http-equiv=\"Content-Type\" content=\"text/html; charset=windows-1251\">
        <link rel=\"stylesheet\" type=\"text/css\" href=\"css/bootstrap.min.css\">
        <link rel=\"stylesheet\" type=\"text/css\" href=\"css/bootstrap-theme.min.css\">
        <script type=\"text/javascript\" src=\"http://code.jquery.com/jquery.min.js\"></script>
        <script type=\"text/javascript\" src=\"js/bootstrap.min.js\"></script>
        <style>
            .table td {
                text-align: center;   
            }
        </style>
            <title>";
        // line 15
        $this->displayBlock('title', $context, $blocks);
        echo " - POS Monitor</title>
        ";
    }

    public function block_title($context, array $blocks = array())
    {
    }

    // line 19
    public function block_header($context, array $blocks = array())
    {
        $this->env->loadTemplate("header.tpl")->display($context);
    }

    // line 22
    public function block_pageName($context, array $blocks = array())
    {
    }

    // line 24
    public function block_content($context, array $blocks = array())
    {
        // line 25
        echo "            ";
    }

    // line 27
    public function block_footer($context, array $blocks = array())
    {
        // line 28
        echo "            <hr>
            <div class=\"row\">
                <div class=\"col-xs-12\">
                    <footer>
                        <div class=\"container\">
                        Made by greg2010 & atap
                        </div>
                    </footer>
                </div>
            </div>
        ";
    }

    public function getTemplateName()
    {
        return "base.tpl";
    }

    public function getDebugInfo()
    {
        return array (  105 => 28,  98 => 25,  95 => 24,  90 => 22,  62 => 5,  59 => 4,  54 => 39,  52 => 27,  47 => 24,  38 => 20,  32 => 17,  25 => 1,  232 => 79,  222 => 74,  216 => 73,  211 => 70,  207 => 68,  197 => 65,  188 => 63,  182 => 62,  178 => 61,  173 => 60,  170 => 59,  167 => 58,  163 => 56,  153 => 54,  146 => 52,  143 => 51,  134 => 49,  129 => 48,  124 => 47,  119 => 46,  114 => 45,  111 => 44,  107 => 43,  102 => 27,  84 => 19,  79 => 25,  74 => 15,  71 => 23,  67 => 21,  63 => 19,  60 => 18,  49 => 26,  45 => 8,  42 => 22,  36 => 19,  30 => 4,);
    }
}
