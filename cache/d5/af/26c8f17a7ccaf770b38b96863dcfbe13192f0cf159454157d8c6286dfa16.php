<?php

/* index.tpl */
class __TwigTemplate_d5af26c8f17a7ccaf770b38b96863dcfbe13192f0cf159454157d8c6286dfa16 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = $this->env->loadTemplate("base.tpl");

        $this->blocks = array(
            'title' => array($this, 'block_title'),
            'pageName' => array($this, 'block_pageName'),
            'content' => array($this, 'block_content'),
        );
    }

    protected function doGetParent(array $context)
    {
        return "base.tpl";
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 3
    public function block_title($context, array $blocks = array())
    {
        echo "Index";
    }

    // line 5
    public function block_pageName($context, array $blocks = array())
    {
        echo "POS Monitor";
    }

    // line 7
    public function block_content($context, array $blocks = array())
    {
        // line 8
        echo "    ";
        if (isset($context["loggedIN"])) { $_loggedIN_ = $context["loggedIN"]; } else { $_loggedIN_ = null; }
        if (($_loggedIN_ > 0)) {
            // line 9
            echo "    <script type=\"text/javascript\">
    \$(document).ready(function(){
        \$(\".table a\").popover({
            placement : 'top',
            html : 'true'
        });
    });
    </script>

    ";
            // line 18
            if (isset($context["showAnchored"])) { $_showAnchored_ = $context["showAnchored"]; } else { $_showAnchored_ = null; }
            if (($_showAnchored_ == "old")) {
                // line 19
                echo "        <form action='index.php' method='post' align='right'><button type=submit class=\"btn btn-default\">Hide Anchored POSes</button></form>
    ";
            } else {
                // line 21
                echo "        <form action='index.php' method='post' align='right'><input type=hidden name='anchored' value='old'><button type=submit class=\"btn btn-default\">Show Anchored POSes</button></form></form>
    ";
            }
            // line 23
            echo "    <hr>
    ";
            // line 24
            if (isset($context["data"])) { $_data_ = $context["data"]; } else { $_data_ = null; }
            $context['_parent'] = (array) $context;
            $context['_seq'] = twig_ensure_traversable($_data_);
            foreach ($context['_seq'] as $context["_key"] => $context["corp"]) {
                // line 25
                echo "        <div class=\"panel panel-default\">
            <!-- Default panel contents -->
            <div class=\"panel-heading\">
                <h5>Owner: <b>";
                // line 28
                if (isset($context["corp"])) { $_corp_ = $context["corp"]; } else { $_corp_ = null; }
                echo twig_escape_filter($this->env, $this->getAttribute($_corp_, "corpName", array()), "html", null, true);
                echo "</b></h5>
            </div>
             <!-- Table -->
            <table class=\"table table-striped table-bordered table-hover\">
                <thead><tr>
                    <th width=\"10%\">System:</th>
                    <th width=\"20%\">Type:</th>
                    <th width=\"15%\">Moon:</th>
                    <th width=\"10%\">State:</th>
                    <th width=\"15%\">Fuel left<br>(Days and hours):</th>
                    <th width=\"15%\">Stront time left<br>(reinforce timer):</th>
                    <th width=\"15%\">Silo information</th>
                </tr></thead>
                <tbody>
                    ";
                // line 42
                if (isset($context["corp"])) { $_corp_ = $context["corp"]; } else { $_corp_ = null; }
                $context['_parent'] = (array) $context;
                $context['_seq'] = twig_ensure_traversable($_corp_);
                foreach ($context['_seq'] as $context["_key"] => $context["table"]) {
                    // line 43
                    echo "                    ";
                    if (isset($context["table"])) { $_table_ = $context["table"]; } else { $_table_ = null; }
                    if (twig_test_iterable($_table_)) {
                        // line 44
                        echo "                        <tr>
                            <td>";
                        // line 45
                        if (isset($context["table"])) { $_table_ = $context["table"]; } else { $_table_ = null; }
                        echo twig_escape_filter($this->env, $this->getAttribute($_table_, "locationName", array()), "html", null, true);
                        echo "</td>
                            <td>";
                        // line 46
                        if (isset($context["table"])) { $_table_ = $context["table"]; } else { $_table_ = null; }
                        echo twig_escape_filter($this->env, $this->getAttribute($_table_, "typeName", array()), "html", null, true);
                        echo "</td>
                            <td>";
                        // line 47
                        if (isset($context["table"])) { $_table_ = $context["table"]; } else { $_table_ = null; }
                        echo twig_escape_filter($this->env, $this->getAttribute($_table_, "moonName", array()), "html", null, true);
                        echo "</td>
                            <td>";
                        // line 48
                        if (isset($context["table"])) { $_table_ = $context["table"]; } else { $_table_ = null; }
                        echo twig_escape_filter($this->env, $this->getAttribute($_table_, "state", array()), "html", null, true);
                        echo "</td>
                            <td>";
                        // line 49
                        if (isset($context["table"])) { $_table_ = $context["table"]; } else { $_table_ = null; }
                        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($_table_, "time", array()), "d", array()), "html", null, true);
                        echo "d ";
                        if (isset($context["table"])) { $_table_ = $context["table"]; } else { $_table_ = null; }
                        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($_table_, "time", array()), "h", array()), "html", null, true);
                        echo "h</td>
                            <td>
                                ";
                        // line 51
                        if (isset($context["table"])) { $_table_ = $context["table"]; } else { $_table_ = null; }
                        if (($this->getAttribute($_table_, "stateID", array()) == 3)) {
                            // line 52
                            echo "                                    ";
                            if (isset($context["table"])) { $_table_ = $context["table"]; } else { $_table_ = null; }
                            echo twig_escape_filter($this->env, $this->getAttribute($_table_, "stateTimestamp", array()), "html", null, true);
                            echo "
                                ";
                        } else {
                            // line 54
                            echo "                                    ";
                            if (isset($context["table"])) { $_table_ = $context["table"]; } else { $_table_ = null; }
                            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($_table_, "rftime", array()), "d", array()), "html", null, true);
                            echo "d ";
                            if (isset($context["table"])) { $_table_ = $context["table"]; } else { $_table_ = null; }
                            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($_table_, "rftime", array()), "h", array()), "html", null, true);
                            echo "h
                                ";
                        }
                        // line 56
                        echo "                            </td>
                            <td>
                                ";
                        // line 58
                        if (isset($context["table"])) { $_table_ = $context["table"]; } else { $_table_ = null; }
                        if (($this->getAttribute($_table_, "numSilo", array()) > 1)) {
                            // line 59
                            echo "                                <a class=\"btn btn-info\" data-toggle=\"popover\" title=\"Silos\" data-content=\"
                                        ";
                            // line 60
                            if (isset($context["table"])) { $_table_ = $context["table"]; } else { $_table_ = null; }
                            $context['_parent'] = (array) $context;
                            $context['_seq'] = twig_ensure_traversable($_table_);
                            foreach ($context['_seq'] as $context["_key"] => $context["silo"]) {
                                // line 61
                                echo "                                        ";
                                if (isset($context["silo"])) { $_silo_ = $context["silo"]; } else { $_silo_ = null; }
                                if ($this->getAttribute($_silo_, "mmname", array(), "any", true, true)) {
                                    // line 62
                                    echo "                                        <b>";
                                    if (isset($context["silo"])) { $_silo_ = $context["silo"]; } else { $_silo_ = null; }
                                    echo twig_escape_filter($this->env, $this->getAttribute($_silo_, "mmname", array()), "html", null, true);
                                    echo "</b>:<br>
                                        ";
                                    // line 63
                                    if (isset($context["silo"])) { $_silo_ = $context["silo"]; } else { $_silo_ = null; }
                                    echo twig_escape_filter($this->env, $this->getAttribute($_silo_, "quantity", array()), "html", null, true);
                                    echo "/";
                                    if (isset($context["silo"])) { $_silo_ = $context["silo"]; } else { $_silo_ = null; }
                                    echo twig_escape_filter($this->env, $this->getAttribute($_silo_, "maximum", array()), "html", null, true);
                                    echo "<br>
                                        ";
                                }
                                // line 65
                                echo "                                        ";
                            }
                            $_parent = $context['_parent'];
                            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['silo'], $context['_parent'], $context['loop']);
                            $context = array_intersect_key($context, $_parent) + $_parent;
                            echo "\">Show Silos</a>

                                ";
                        } else {
                            // line 68
                            echo "                                    No Silo.
                                ";
                        }
                        // line 70
                        echo "                            </td>
                        </tr>
                    ";
                    }
                    // line 73
                    echo "                    ";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['table'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 74
                echo "                </tbody>
            </table>
        </div>
    ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['corp'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
        } else {
            // line 79
            echo "    <div class=\"alert alert-danger\" role=\"alert\">Access denied. Autorization required.</div>
";
        }
    }

    public function getTemplateName()
    {
        return "index.tpl";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  232 => 79,  222 => 74,  216 => 73,  211 => 70,  207 => 68,  197 => 65,  188 => 63,  182 => 62,  178 => 61,  173 => 60,  170 => 59,  167 => 58,  163 => 56,  153 => 54,  146 => 52,  143 => 51,  134 => 49,  129 => 48,  124 => 47,  119 => 46,  114 => 45,  111 => 44,  107 => 43,  102 => 42,  84 => 28,  79 => 25,  74 => 24,  71 => 23,  67 => 21,  63 => 19,  60 => 18,  49 => 9,  45 => 8,  42 => 7,  36 => 5,  30 => 3,);
    }
}
