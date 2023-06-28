<?php

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Extension\SandboxExtension;
use Twig\Markup;
use Twig\Sandbox\SecurityError;
use Twig\Sandbox\SecurityNotAllowedTagError;
use Twig\Sandbox\SecurityNotAllowedFilterError;
use Twig\Sandbox\SecurityNotAllowedFunctionError;
use Twig\Source;
use Twig\Template;

/* modules/custom/curso_module/templates/curso-plantilla.html.twig */
class __TwigTemplate_ee7dc89147e0ea8faacb51e38026f495af10c3d66a0bf87f50dde5ed11f644a8 extends \Twig\Template
{
    private $source;
    private $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->parent = false;

        $this->blocks = [
        ];
        $this->sandbox = $this->env->getExtension('\Twig\Extension\SandboxExtension');
        $this->checkSecurity();
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 1
        echo "<div>
    <ul>
        <li>
            <b>Etiqueta: </b>
            ";
        // line 5
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["etiqueta"] ?? null), 5, $this->source), "html", null, true);
        echo "
        </li>
        <li>
            <b>Tipo: </b>
            ";
        // line 9
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["tipo"] ?? null), 9, $this->source), "html", null, true);
        echo "
        </li>
        <li>
            <b>Autor: </b>
            ";
        // line 13
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["autor"] ?? null), 13, $this->source), "html", null, true);
        echo "            
        </li>
        <li>
            <b>Descripción: </b>
            ";
        // line 17
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["descripcion"] ?? null), 17, $this->source), "html", null, true);
        echo "
        </li>
    </ul>
</div>";
    }

    public function getTemplateName()
    {
        return "modules/custom/curso_module/templates/curso-plantilla.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  66 => 17,  59 => 13,  52 => 9,  45 => 5,  39 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("", "modules/custom/curso_module/templates/curso-plantilla.html.twig", "C:\\wamp64\\www\\cursoDrupal\\web\\modules\\custom\\curso_module\\templates\\curso-plantilla.html.twig");
    }
    
    public function checkSecurity()
    {
        static $tags = array();
        static $filters = array("escape" => 5);
        static $functions = array();

        try {
            $this->sandbox->checkSecurity(
                [],
                ['escape'],
                []
            );
        } catch (SecurityError $e) {
            $e->setSourceContext($this->source);

            if ($e instanceof SecurityNotAllowedTagError && isset($tags[$e->getTagName()])) {
                $e->setTemplateLine($tags[$e->getTagName()]);
            } elseif ($e instanceof SecurityNotAllowedFilterError && isset($filters[$e->getFilterName()])) {
                $e->setTemplateLine($filters[$e->getFilterName()]);
            } elseif ($e instanceof SecurityNotAllowedFunctionError && isset($functions[$e->getFunctionName()])) {
                $e->setTemplateLine($functions[$e->getFunctionName()]);
            }

            throw $e;
        }

    }
}
