<?php

namespace UWDOEM\Framework\PickA;

use UWDOEM\Framework\Etc\AbstractBuilder;
use UWDOEM\Framework\Form\FormAction\FormAction;

class PickAFormBuilder extends AbstractBuilder
{
    /** @var string */
    protected $type = "base";

    /** @var string */
    protected $method = "post";

    /** @var string */
    protected $target = "_self";

    protected $manifest = [];

    protected $actions = [];

    /**
     * @param string $method
     * @return PickAFormBuilder
     */
    public function setMethod($method)
    {
        $this->method = $method;
        return $this;
    }

    /**
     * @param string $target
     * @return PickAFormBuilder
     */
    public function setTarget($target)
    {
        $this->target = $target;
        return $this;
    }

    /**
     * @param string $label
     * @return PickAFormBuilder
     */
    public function addLabel($label)
    {
        $this->manifest[$label] = null;
        return $this;
    }

    /**
     * @param \UWDOEM\Framework\Form\FormInterface[] $forms
     * @return PickAFormBuilder
     */
    public function addForms(array $forms)
    {
        $this->manifest = array_merge($this->manifest, $forms);
        return $this;
    }

    /**
     * @param FormAction[] $actions
     * @return PickAFormBuilder
     */
    public function setActions($actions)
    {
        $this->actions = $actions;
        return $this;
    }

    /**
     * @param string $type
     * @return PickAFormBuilder
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return PickAFormInterface
     */
    public function build()
    {
        $this->validateId();

        return new PickAForm($this->id, $this->type, $this->method, $this->target, $this->manifest, $this->actions);
    }
}
