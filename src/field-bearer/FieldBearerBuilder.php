<?php

namespace UWDOEM\Framework\FieldBearer;

use Propel\Runtime\ActiveRecord\ActiveRecordInterface;

use UWDOEM\Framework\Etc\AbstractBuilder;
use UWDOEM\Framework\Etc\ORMUtils;
use UWDOEM\Framework\Field\FieldInterface;
use UWDOEM\Framework\Field\Field;

/**
 * Class FieldBearerBuilder
 * @package UWDOEM\Framework\FieldBearer
 */
class FieldBearerBuilder extends AbstractBuilder
{
    /**
     * @var FieldBearerInterface[]
     */
    protected $fieldBearers = [];

    /**
     * @var FieldInterface[]
     */
    protected $fields = [];

    /**
     * @var string[]
     */
    protected $visibleFieldNames = [];

    /**
     * @var string[]
     */
    protected $hiddenFieldNames = [];

    /**
     * @var callable
     */
    protected $saveFunction;

    /** @var mixed[] */
    private $initialFieldValues = [];

    /**
     * @param FieldBearerInterface[] $fieldBearers
     * @return FieldBearerBuilder
     */
    public function addFieldBearers(array $fieldBearers)
    {
        $this->fieldBearers = array_merge($this->fieldBearers, $fieldBearers);
        return $this;
    }

    /**
     * @param FieldInterface[] $fields
     * @return FieldBearerBuilder
     */
    public function addFields(array $fields)
    {

        $fieldBearer = new FieldBearer(
            $fields,
            [],
            [],
            [],
            function () {
            }
        );

        $this->addFieldBearers([$fieldBearer]);
        return $this;
    }

    /**
     * @param ActiveRecordInterface $object
     * @return FieldBearerBuilder
     */
    public function addObject(ActiveRecordInterface $object)
    {
        $saveFunction = function (ClassFieldBearer $fieldBearer) use ($object) {

            ORMUtils::fillObjectFromFields($object, $fieldBearer->getFields());
            $object->save();

            $primaryKey = $object->getId();

            foreach ($fieldBearer->getFields() as $field) {
                if ($field->getType() === Field::FIELD_TYPE_PRIMARY_KEY) {
                    $field->setInitial($primaryKey);
                    break;
                }
            }
        };
        $this->addFieldBearers([new ClassFieldBearer($object, $saveFunction)]);
        return $this;
    }

    /**
     * @param string $classTableMapName
     * @return FieldBearerBuilder
     */
    public function addClassTableMapName($classTableMapName)
    {
        $object = ORMUtils::makeNewObjectFromClassTableMapName($classTableMapName);
        return $this->addObject($object);
    }

    /**
     * @param \string[] $visibleFieldNames
     * @return FieldBearerBuilder
     */
    public function setVisibleFieldNames(array $visibleFieldNames)
    {
        $this->visibleFieldNames = $visibleFieldNames;
        return $this;
    }

    /**
     * @param string[] $hiddenFieldNames
     * @return FieldBearerBuilder
     */
    public function setHiddenFieldNames(array $hiddenFieldNames)
    {
        $this->hiddenFieldNames = $hiddenFieldNames;
        return $this;
    }

    /**
     * @param callable $saveFunction
     * @return FieldBearerBuilder
     */
    public function setSaveFunction(callable $saveFunction)
    {
        $this->saveFunction = $saveFunction;
        return $this;
    }

    /**
     * @param string $fieldName
     * @param mixed  $value
     * @return $this
     */
    public function setInitialFieldValue($fieldName, $value)
    {
        $this->initialFieldValues[$fieldName] = $value;

        return $this;
    }

    /**
     * @return FieldBearer
     * @throws \Exception If neither fields nor fieldBearers has been set.
     */
    public function build()
    {
        if (!$this->saveFunction) {
            $this->saveFunction = function (FieldBearerInterface $fieldBearer) {
                foreach ($fieldBearer->getFieldBearers() as $childFieldBearer) {
                    $args = array_merge([$fieldBearer], func_get_args());

                    $func = [$childFieldBearer, "save"];
                    call_user_func_array($func, $args);
                }
            };
        }

        $fieldBearer = new FieldBearer(
            $this->fields,
            $this->fieldBearers,
            $this->visibleFieldNames,
            $this->hiddenFieldNames,
            $this->saveFunction
        );

        foreach ($this->initialFieldValues as $fieldName => $value) {
            $fieldBearer->getFieldByName($fieldName)->setInitial($value);
        }

        return $fieldBearer;
    }
}
