<?php

namespace UWDOEM\Framework\Table;


use UWDOEM\Framework\Etc\AbstractBuilder;
use UWDOEM\Framework\Filter\DummyFilter;
use UWDOEM\Framework\Row\RowInterface;
use UWDOEM\Framework\Filter\FilterInterface;


class TableBuilder extends AbstractBuilder {

    /** @var string */
    protected $_id;

    /** @var RowInterface[] */
    protected $_rows = [];

    /** @var FilterInterface */
    protected $_filter;

    /**
     * @param RowInterface[] $rows
     * @return TableBuilder
     */
    public function setRows($rows) {
        $this->_rows = $rows;
        return $this;
    }

    /**
     * @param FilterInterface $filter
     * @return TableBuilder
     */
    public function addFilter(FilterInterface $filter) {
        if (isset($this->_filter)) {
            $filter->combine($this->_filter);
        }
        $this->_filter = $filter;
        return $this;
    }

    /**
     * @return TableBuilder
     */
    public static function begin() {
        return new static();
    }

    public function build() {
        $this->validateId();

        if (!isset($this->_filter)) {
            $this->_filter = new DummyFilter();
        }

        return new Table($this->_id, $this->_rows, $this->_filter);
    }


}