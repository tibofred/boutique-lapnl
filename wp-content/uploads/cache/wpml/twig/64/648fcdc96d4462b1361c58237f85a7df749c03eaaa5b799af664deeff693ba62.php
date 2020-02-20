<?php

/* save-notification.twig */
class __TwigTemplate_fbe3aaf877f52a96a2b7994f20b3648352fc76203bddb9b4fa760a00134fd500 extends Twig_Template
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
        echo "<span class=\"js-wpml-ls-messages wpml-ls-messages\"></span><span class=\"spinner\"></span>
";
    }

    public function getTemplateName()
    {
        return "save-notification.twig";
    }

    public function getDebugInfo()
    {
        return array (  19 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "save-notification.twig", "/home/keepusin/public_html/lapnl/wp-content/plugins/old-sitepress-multilingual-cms/templates/language-switcher-admin-ui/save-notification.twig");
    }
}
