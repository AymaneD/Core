<?php

namespace UWDOEM\Framework\Writer;

use JsonSchema\Constraints\String;
use Twig_SimpleFilter;

use UWDOEM\Framework\Etc\SafeString;
use UWDOEM\Framework\Field\FieldInterface;
use UWDOEM\Framework\Form\FormInterface;
use UWDOEM\Framework\Form\FormAction\FormActionInterface;
use UWDOEM\Framework\Section\SectionInterface;
use UWDOEM\Framework\Visitor\Visitor;
use UWDOEM\Framework\Page\PageInterface;
use UWDOEM\Framework\Etc\Settings;
use UWDOEM\Framework\Etc\StringUtils;
use UWDOEM\Framework\Field\Field;


class Writer extends Visitor {

    protected $_environment;

    protected function getTemplatesDirectories() {
        return array_merge(Settings::getTemplateDirectories(), [dirname(__FILE__) . '/templates/base']);
    }

    protected function write(WritableInterface $host) {
        return $host->accept($this);
    }

    protected function getEnvironment() {
        if (!isset($this->_environment)) {
            $loader = new \Twig_Loader_Filesystem($this->getTemplatesDirectories());
            $this->_environment = new \Twig_Environment($loader);

            $filter = new Twig_SimpleFilter('write', function(WritableInterface $host) { return $host->accept($this); });
            $this->_environment->addFilter($filter);

            $filter = new Twig_SimpleFilter('slugify', function($string) { return StringUtils::slugify($string); });
            $this->_environment->addFilter($filter);

            $filter = new Twig_SimpleFilter(
                'saferaw',
                function($string) {
                    if ( $string instanceof SafeString ) {
                        $string = (string)$string;
                    } else {
                        $string = htmlentities($string);
                    }

                    return $string;
                });
            $this->_environment->addFilter($filter);
        }

        return $this->_environment;
    }

    protected function loadTemplate($subpath) {
        return $this->getEnvironment()->loadTemplate($subpath);
    }

    public function visitSection(SectionInterface $section) {
        $template = 'section/section.twig';

        return $this
            ->loadTemplate($template)
            ->render([
                "label" => $section->getLabel(),
                "content" => $section->getContent(),
                "writables" => $section->getWritables(),
            ]);
    }

    public function visitPage(PageInterface $page) {
        $template = 'page/' . $page->getType() . '.twig';

        $writable = $page->getWritable();

        if ($writable) {
            $content = $writable->accept($this);
        } else {
            $content = "";
        }

        return $this
            ->loadTemplate($template)
            ->render([
                "title" => $page->getTitle(),
                "header" => $page->getHeader(),
                "subHeader" => $page->getSubHeader(),
                "baseHref" => $page->getBaseHref(),
                "breadCrumbs" => $page->getBreadCrumbs(),
                "returnTo" => $page->getReturnTo(),
                "projectCSS" => Settings::getProjectCSS(),
                "projectJS" => Settings::getProjectJS(),
                "content" => $content,
            ]);
    }

    public function visitField(FieldInterface $field) {
        $template = 'field/' . $field->getType() . '.twig';

        if ($field->getType() === Field::FIELD_TYPE_CHOICE || $field->getType() === Field::FIELD_TYPE_MULTIPLE_CHOICE) {
            $choices = array_combine($field->getChoiceSlugs(), $field->getChoices());
        } else {
            $choices = [];
        }

        return $this
            ->loadTemplate($template)
            ->render([
                "slug" => $field->getSlug(),
                "initial" => $field->getInitial(),
                "choices" => $choices,
                "label" => $field->getLabel(),
                "required" => $field->isRequired(),
                "size" => $field->getSize(),
                "errors" => $field->getErrors()
            ]);
    }

    public function visitForm(FormInterface $form) {
        $template = 'form/base.twig';

        return $this
            ->loadTemplate($template)
            ->render([
                "visibleFields" => $form->getFieldBearer()->getVisibleFields(),
                "hiddenFields" => $form->getFieldBearer()->getHiddenFields(),
                "actions" => $form->getActions(),
                "requestURI" => $_SERVER["REQUEST_URI"],
                "errors" => $form->getErrors()
            ]);
    }

    public function visitFormAction(FormActionInterface $formAction) {
        $template = 'form-action/base.twig';

        return $this
            ->loadTemplate($template)
            ->render([
                "label" => $formAction->getLabel(),
                "method" => $formAction->getMethod(),
                "target" => $formAction->getTarget(),
            ]);
    }
}