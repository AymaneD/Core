Athens\Core\Table\TableFormBuilder
===============

Class TableFormBuilder




* Class name: TableFormBuilder
* Namespace: Athens\Core\Table
* Parent class: [Athens\Core\Etc\AbstractBuilder](Athens-Core-Etc-AbstractBuilder.md)







Methods
-------


### setRows

    \Athens\Core\Table\TableFormBuilder Athens\Core\Table\TableFormBuilder::setRows(array<mixed,\Athens\Core\Row\RowInterface> $rows)





* Visibility: **public**


#### Arguments
* $rows **array&lt;mixed,\Athens\Core\Row\RowInterface&gt;**



### setRowMakingFunction

    \Athens\Core\Table\TableFormBuilder Athens\Core\Table\TableFormBuilder::setRowMakingFunction(callable $rowMakingFunction)





* Visibility: **public**


#### Arguments
* $rowMakingFunction **callable**



### setCanRemove

    \Athens\Core\Table\TableFormBuilder Athens\Core\Table\TableFormBuilder::setCanRemove(boolean $canRemove)





* Visibility: **public**


#### Arguments
* $canRemove **boolean**



### build

    mixed Athens\Core\Etc\AbstractBuilder::build()

Return the element under construction.

Returns an instance of the object type under construction.

* Visibility: **public**
* This method is **abstract**.
* This method is defined by [Athens\Core\Etc\AbstractBuilder](Athens-Core-Etc-AbstractBuilder.md)




### setType

    \Athens\Core\Table\TableFormBuilder Athens\Core\Table\TableFormBuilder::setType(string $type)





* Visibility: **public**


#### Arguments
* $type **string**



### setActions

    \Athens\Core\Table\TableFormBuilder Athens\Core\Table\TableFormBuilder::setActions(array<mixed,\Athens\Core\Form\FormAction\FormAction> $actions)





* Visibility: **public**


#### Arguments
* $actions **array&lt;mixed,\Athens\Core\Form\FormAction\FormAction&gt;**



### setTarget

    \Athens\Core\Table\TableFormBuilder Athens\Core\Table\TableFormBuilder::setTarget(string $target)





* Visibility: **public**


#### Arguments
* $target **string**



### setMethod

    \Athens\Core\Table\TableFormBuilder Athens\Core\Table\TableFormBuilder::setMethod(string $method)





* Visibility: **public**


#### Arguments
* $method **string**



### setOnValidFunc

    \Athens\Core\Table\TableFormBuilder Athens\Core\Table\TableFormBuilder::setOnValidFunc(callable $onValidFunc)





* Visibility: **public**


#### Arguments
* $onValidFunc **callable**



### setOnInvalidFunc

    \Athens\Core\Table\TableFormBuilder Athens\Core\Table\TableFormBuilder::setOnInvalidFunc(callable $onInvalidFunc)





* Visibility: **public**


#### Arguments
* $onInvalidFunc **callable**



### setOnSuccessUrl

    \Athens\Core\Table\TableFormBuilder Athens\Core\Table\TableFormBuilder::setOnSuccessUrl(string $onSuccessRedirect)





* Visibility: **public**


#### Arguments
* $onSuccessRedirect **string**



### addSubForms

    \Athens\Core\Form\FormBuilder Athens\Core\Table\TableFormBuilder::addSubForms(array<mixed,\Athens\Core\Form\FormInterface> $subForms)





* Visibility: **public**


#### Arguments
* $subForms **array&lt;mixed,\Athens\Core\Form\FormInterface&gt;**



### addValidator

    \Athens\Core\Table\TableFormBuilder Athens\Core\Table\TableFormBuilder::addValidator(string $fieldName, callable $callable)





* Visibility: **public**


#### Arguments
* $fieldName **string**
* $callable **callable**



### begin

    static Athens\Core\Etc\AbstractBuilder::begin()





* Visibility: **public**
* This method is **static**.
* This method is defined by [Athens\Core\Etc\AbstractBuilder](Athens-Core-Etc-AbstractBuilder.md)




### clear

    \Athens\Core\Etc\AbstractBuilder Athens\Core\Etc\AbstractBuilder::clear()





* Visibility: **public**
* This method is defined by [Athens\Core\Etc\AbstractBuilder](Athens-Core-Etc-AbstractBuilder.md)




### setId

    \Athens\Core\Etc\AbstractBuilder Athens\Core\Etc\AbstractBuilder::setId(string $id)

Set the unique identifier for the element to be built.



* Visibility: **public**
* This method is defined by [Athens\Core\Etc\AbstractBuilder](Athens-Core-Etc-AbstractBuilder.md)


#### Arguments
* $id **string**



### addClass

    \Athens\Core\Etc\AbstractBuilder Athens\Core\Etc\AbstractBuilder::addClass(string $class)

Add a display class name to the element to be built.



* Visibility: **public**
* This method is defined by [Athens\Core\Etc\AbstractBuilder](Athens-Core-Etc-AbstractBuilder.md)


#### Arguments
* $class **string**



### addData

    \Athens\Core\Etc\AbstractBuilder Athens\Core\Etc\AbstractBuilder::addData(string $key, string $value)

Add a data field to the element to be built.

For example, when building a field:

FieldBuilder->begin()
    ->addData('owned-by', 'bob')
    ...
    ->build();

When written to HTML, the resulting field will have
an HTML5 data attribute like:

    <... data-owned-by='bob' .../>

* Visibility: **public**
* This method is defined by [Athens\Core\Etc\AbstractBuilder](Athens-Core-Etc-AbstractBuilder.md)


#### Arguments
* $key **string**
* $value **string**


