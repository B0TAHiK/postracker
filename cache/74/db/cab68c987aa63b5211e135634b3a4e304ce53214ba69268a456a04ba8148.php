<?php

/* header.tpl */
class __TwigTemplate_74dbcab68c987aa63b5211e135634b3a4e304ce53214ba69268a456a04ba8148 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        if (isset($context["loggedIN"])) { $_loggedIN_ = $context["loggedIN"]; } else { $_loggedIN_ = null; }
        if (($_loggedIN_ > 0)) {
            // line 2
            echo "<nav class=\"navbar navbar-inverse navbar-static-top\" role=\"navigation\">
    <div class=\"container\">
    <!-- Brand and toggle get grouped for better mobile display -->
        <div class=\"navbar-header\">
            <button type=\"button\" class=\"navbar-toggle\" data-toggle=\"collapse\" data-target=\"#bs-example-navbar-collapse-1\">
                <span class=\"sr-only\">Toggle navigation</span>
                <span class=\"icon-bar\"></span>
                <span class=\"icon-bar\"></span>
                <span class=\"icon-bar\"></span>
            </button>
            <a class=\"navbar-brand\" href=\"#\"><img src=img/logo.png style=\"margin-top: -3px;\"></a>
        </div>
        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class=\"collapse navbar-collapse\" id=\"bs-example-navbar-collapse-1\">
            <ul class=\"nav navbar-nav\">
                <li";
            // line 17
            if (isset($context["isIndex"])) { $_isIndex_ = $context["isIndex"]; } else { $_isIndex_ = null; }
            echo twig_escape_filter($this->env, $_isIndex_, "html", null, true);
            echo "><a href=\"index.php\">POS Monitor</a></li>
                ";
            // line 18
            if (isset($context["groupID"])) { $_groupID_ = $context["groupID"]; } else { $_groupID_ = null; }
            if (($_groupID_ > 1)) {
                echo "<li";
                if (isset($context["isSupers"])) { $_isSupers_ = $context["isSupers"]; } else { $_isSupers_ = null; }
                echo twig_escape_filter($this->env, $_isSupers_, "html", null, true);
                echo "><a href=\"superCapitalMonitoring.php\">Supercapitals</a></li>";
            }
            // line 19
            echo "                ";
            if (isset($context["groupID"])) { $_groupID_ = $context["groupID"]; } else { $_groupID_ = null; }
            if (($_groupID_ > 2)) {
                echo "<li";
                if (isset($context["isAdmin"])) { $_isAdmin_ = $context["isAdmin"]; } else { $_isAdmin_ = null; }
                echo twig_escape_filter($this->env, $_isAdmin_, "html", null, true);
                echo "><a href=\"admin.php\">Admin</a></li>";
            }
            // line 20
            echo "                <li><a href=\"https://redalliance.pw\">Forum</a></li>
            </ul>
            <ul class=\"nav navbar-nav navbar-right\">
                <li class=\"dropdown\">
                    <a href=\"#\" class=\"dropdown-toggle\" data-toggle=\"dropdown\">Logged as: <b>";
            // line 24
            if (isset($context["charName"])) { $_charName_ = $context["charName"]; } else { $_charName_ = null; }
            echo twig_escape_filter($this->env, $_charName_, "html", null, true);
            echo "</b> <span class=\"caret\"></span></a>
                    <ul class=\"dropdown-menu\" role=\"menu\">
                        <li";
            // line 26
            if (isset($context["isSettings"])) { $_isSettings_ = $context["isSettings"]; } else { $_isSettings_ = null; }
            echo twig_escape_filter($this->env, $_isSettings_, "html", null, true);
            echo "><a href=\"settings.php\">Settings</a></li>
                        <li><a href=\"logout.php\">Logout</a></li>
                    </ul>
                </li>
            </ul>
        </div><!-- /.navbar-collapse -->
    </div><!-- /.container -->
</nav>
";
        } else {
            // line 35
            echo "<nav class=\"navbar navbar-inverse navbar-static-top\" role=\"navigation\">
    <div class=\"container\">
    <!-- Brand and toggle get grouped for better mobile display -->
        <div class=\"navbar-header\">
            <button type=\"button\" class=\"navbar-toggle\" data-toggle=\"collapse\" data-target=\"#bs-example-navbar-collapse-1\">
                <span class=\"sr-only\">Toggle navigation</span>
                <span class=\"icon-bar\"></span>
                <span class=\"icon-bar\"></span>
                <span class=\"icon-bar\"></span>
            </button>
            <a class=\"navbar-brand\" href=\"#\"><img src=img/logo.png style=\"margin-top: -3px;\"></a>
        </div>
        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class=\"collapse navbar-collapse\" id=\"bs-example-navbar-collapse-1\">
            <ul class=\"nav navbar-nav\">
                <li";
            // line 50
            if (isset($context["isIndex"])) { $_isIndex_ = $context["isIndex"]; } else { $_isIndex_ = null; }
            echo twig_escape_filter($this->env, $_isIndex_, "html", null, true);
            echo "><a href=\"../index.php\">POS Monitor</a></li>
                <li";
            // line 51
            if (isset($context["isLogin"])) { $_isLogin_ = $context["isLogin"]; } else { $_isLogin_ = null; }
            echo twig_escape_filter($this->env, $_isLogin_, "html", null, true);
            echo "><a href=\"../login.php\">Login</a></li>
                <li";
            // line 52
            if (isset($context["isReg"])) { $_isReg_ = $context["isReg"]; } else { $_isReg_ = null; }
            echo twig_escape_filter($this->env, $_isReg_, "html", null, true);
            echo "><a href=\"../reg.php\">Register</a></li>
            </ul>
        </div><!-- /.navbar-collapse -->
    </div><!-- /.container -->
</nav>
";
        }
    }

    public function getTemplateName()
    {
        return "header.tpl";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  113 => 52,  108 => 51,  103 => 50,  86 => 35,  73 => 26,  61 => 20,  44 => 18,  39 => 17,  22 => 2,  19 => 1,  105 => 28,  98 => 25,  95 => 24,  90 => 22,  62 => 5,  59 => 4,  54 => 39,  52 => 19,  47 => 24,  38 => 20,  32 => 17,  25 => 1,  232 => 79,  222 => 74,  216 => 73,  211 => 70,  207 => 68,  197 => 65,  188 => 63,  182 => 62,  178 => 61,  173 => 60,  170 => 59,  167 => 58,  163 => 56,  153 => 54,  146 => 52,  143 => 51,  134 => 49,  129 => 48,  124 => 47,  119 => 46,  114 => 45,  111 => 44,  107 => 43,  102 => 27,  84 => 19,  79 => 25,  74 => 15,  71 => 23,  67 => 24,  63 => 19,  60 => 18,  49 => 26,  45 => 8,  42 => 22,  36 => 19,  30 => 4,);
    }
}
