<?php

namespace Athens\Core\Writer;

use Athens\Core\Filter\DummyFilter;
use Athens\Core\Filter\SelectFilter;

use Twig_SimpleFilter;

use Athens\Core\WritableBearer\WritableBearerInterface;
use Athens\Core\Email\EmailInterface;
use Athens\Core\Etc\SafeString;
use Athens\Core\Field\FieldInterface;
use Athens\Core\Filter\PaginationFilter;
use Athens\Core\Filter\SortFilter;
use Athens\Core\Form\FormInterface;
use Athens\Core\FormAction\FormActionInterface;
use Athens\Core\Section\SectionInterface;
use Athens\Core\Page\PageInterface;
use Athens\Core\Row\RowInterface;
use Athens\Core\Table\TableInterface;
use Athens\Core\Settings\Settings;
use Athens\Core\Etc\StringUtils;
use Athens\Core\Link\LinkInterface;
use Athens\Core\Filter\FilterInterface;
use Athens\Core\Filter\SearchFilter;
use Athens\Core\Writable\WritableInterface;

/**
 * Class EmailWriter is a visitor which writes emails.
 *
 * @package Athens\Core\Writer
 */
class EmailWriter extends TwigTemplateWriter
{

    /**
     * Render $writableBearer into html.
     *
     * This method is generally called via double-dispatch, as provided by Visitor\VisitableTrait.
     *
     * @param WritableBearerInterface $writableBearer
     * @return string
     */
    public function visitWritableBearer(WritableBearerInterface $writableBearer)
    {

        $template = 'writable-bearer/' . $writableBearer->getType() . '.twig';

        return $this
            ->loadTemplate($template)
            ->render(
                [
                    "id" => $writableBearer->getId(),
                    "classes" => $writableBearer->getClasses(),
                    "data" => $writableBearer->getData(),
                    "writables" => $writableBearer->getWritables(),
                ]
            );
    }

    /**
     * Render $section into html.
     *
     * This method is generally called via double-dispatch, as provided by Visitor\VisitableTrait.
     *
     * @param SectionInterface $section
     * @return string
     */
    public function visitSection(SectionInterface $section)
    {

        $template = 'section/' . $section->getType() . '.twig';

        return $this
            ->loadTemplate($template)
            ->render(
                [
                    "id" => $section->getId(),
                    "classes" => $section->getClasses(),
                    "data" => $section->getData(),
                    "writables" => $section->getWritables(),
                ]
            );
    }

    /**
     * Render $link into html.
     *
     * This method is generally called via double-dispatch, as provided by Visitor\VisitableTrait.
     *
     * @param LinkInterface $link
     * @return string
     */
    public function visitLink(LinkInterface $link)
    {

        $template = 'link/' . $link->getType() . '.twig';

        return $this
            ->loadTemplate($template)
            ->render(
                [
                    "id" => $link->getId(),
                    "classes" => $link->getClasses(),
                    "data" => $link->getData(),
                    "uri" => $link->getURI(),
                    "text" => $link->getText(),
                ]
            );
    }

    /**
     * Render $page into html.
     *
     * This method is generally called via double-dispatch, as provided by Visitor\VisitableTrait.
     *
     * @param PageInterface $page
     * @return string
     */
    public function visitPage(PageInterface $page)
    {
        $template = 'page/' . $page->getType() . '.twig';

        return $this
            ->loadTemplate($template)
            ->render(
                [
                    "id" => $page->getId(),
                    "classes" => $page->getClasses(),
                    "data" => $page->getData(),
                    "title" => $page->getTitle(),
                    "baseHref" => $page->getBaseHref(),
                    "writables" => $page->getWritables(),
                    "projectCSS" => Settings::getInstance()->getProjectCSS(),
                    "projectJS" => Settings::getInstance()->getProjectJS(),
                ]
            );
    }

    /**
     * Render $field into html.
     *
     * This method is generally called via double-dispatch, as provided by Visitor\VisitableTrait.
     *
     * @param FieldInterface $field
     * @return string
     */
    public function visitField(FieldInterface $field)
    {
        $template = 'field/' . $field->getType() . '.twig';

        return $this
            ->loadTemplate($template)
            ->render(
                [
                    "id" => $field->getId(),
                    "classes" => $field->getClasses(),
                    "data" => $field->getData(),
                    "slug" => $field->getSlug(),
                    "initial" => $field->getInitial(),
                    "choices" => $field->getChoices(),
                    "label" => $field->getLabel(),
                    "required" => $field->isRequired(),
                    "size" => $field->getSize(),
                    "errors" => $field->getErrors(),
                    "helptext" => $field->getHelptext(),
                    "placeholder" => $field->getPlaceholder()
                ]
            );
    }

    /**
     * Render $row into html.
     *
     * This method is generally called via double-dispatch, as provided by Visitor\VisitableTrait.
     *
     * @param RowInterface $row
     * @return string
     */
    public function visitRow(RowInterface $row)
    {
        $template = 'row/base.twig';

        return $this
            ->loadTemplate($template)
            ->render(
                [
                    "hash" => $row->getId(),
                    "classes" => $row->getClasses(),
                    "data" => $row->getData(),
                    "writables" => $row->getWritableBearer()->getWritables(),
                    "labels" => $row->getLabels(),
                    "highlightable" => $row->isHighlightable(),
                    "onClick" => $row->getOnClick(),
                ]
            );
    }

    /**
     * Render $table into html.
     *
     * This method is generally called via double-dispatch, as provided by Visitor\VisitableTrait.
     *
     * @param TableInterface $table
     * @return string
     */
    public function visitTable(TableInterface $table)
    {
        $template = 'table/base.twig';

        $filters = [];
        for ($thisFilter = $table->getFilter(); $thisFilter !== null; $thisFilter = $thisFilter->getNextFilter()) {
            $filters[] = $thisFilter;
        }

        return $this
            ->loadTemplate($template)
            ->render(
                [
                    "id" => $table->getId(),
                    "classes" => $table->getClasses(),
                    "data" => $table->getData(),
                    "rows" => $table->getRows(),
                    "filters" => $filters,
                ]
            );
    }

    /**
     * Render FormInterface into html.
     *
     * This method is generally called via double-dispatch, as provided by Visitor\VisitableTrait.
     *
     * @param FormInterface $form
     * @return string
     */
    public function visitForm(FormInterface $form)
    {
        $template = "form/{$form->getType()}.twig";

        return $this
            ->loadTemplate($template)
            ->render(
                [
                    "id" => $form->getId(),
                    "classes" => $form->getClasses(),
                    "data" => $form->getData(),
                    "method" => $form->getMethod(),
                    "target" => $form->getTarget(),
                    "writables" => $form->getWritableBearer()->getWritables(),
                    "actions" => $form->getActions(),
                    "errors" => $form->getErrors(),
                ]
            );
    }

    /**
     * Render FormActionInterface into html.
     *
     * This method is generally called via double-dispatch, as provided by Visitor\VisitableTrait.
     *
     * @param FormActionInterface $formAction
     * @return string
     */
    public function visitFormAction(FormActionInterface $formAction)
    {
        $template = "form-action/{$formAction->getType()}.twig";

        return $this
            ->loadTemplate($template)
            ->render(
                [
                    "label" => $formAction->getLabel(),
                    "target" => $formAction->getTarget(),
                ]
            );
    }

    /**
     * Helper method to render a FilterInterface to html.
     *
     * @param FilterInterface $filter
     * @return string
     */
    public function visitFilter(FilterInterface $filter)
    {
        if ($filter instanceof DummyFilter) {
            $type = 'dummy';
        } elseif ($filter instanceof SelectFilter) {
            $type = 'select';
        } elseif ($filter instanceof SearchFilter) {
            $type = 'search';
        } elseif ($filter instanceof SortFilter) {
            $type = 'sort';
        } elseif ($filter instanceof PaginationFilter) {
            $type = "{$filter->getType()}-pagination";
        } else {
            $type = 'dummy';
        }
        $template = "filter/$type.twig";

        return $this
            ->loadTemplate($template)
            ->render(
                [
                    "id" => $filter->getId(),
                    "options" => $filter->getOptions(),
                ]
            );
    }

    /**
     * Render an email message into an email body, given its template.
     *
     * @param EmailInterface $email
     * @return string
     */
    public function visitEmail(EmailInterface $email)
    {
        $template = "email/{$email->getType()}.twig";

        return $this
            ->loadTemplate($template)
            ->render(
                [
                    "id" => $email->getId(),
                    "classes" => $email->getClasses(),
                    "data" => $email->getData(),
                    "message" => $email->getMessage(),
                ]
            );
    }
}
