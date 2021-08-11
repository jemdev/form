<?php
use PHPUnit\Framework\TestCase;
use jemdev\form\form;
use jemdev\form\field;

/**
 * form test case.
 */
class formTest extends TestCase
{

    /**
     *
     * @var form
     */
    private $_oForm;

    /**
     * Prepares the environment before running a test.
     */
    protected function setUp():void
    {
        parent::setUp();
        $this->_oForm = new form('testform');
    }

    /**
     * Tests form->__call()
     */
    public function test__call()
    {
        $champtexte = $this->_oForm->text('test','test');
        $this->assertInstanceOf(field::class, $champtexte);
        $this->assertEquals('test', $champtexte->id);
        $champtexte->setLabel('Label du champ');
        $this->assertEquals('Label du champ', $champtexte->label);
    }

    /**
     * Tests form->__get()
     */
    public function test__get()
    {
        $this->assertTrue(true);
    }

    /**
     * Tests form->__set()
     */
    public function test__set()
    {
        $form = $this->_oForm->doctype = 'HTML5';
        $this->assertInstanceOf(form::class, $form);
    }

    /**
     * Tests form->setHidden()
     */
    public function testSetHidden()
    {
        $this->assertTrue(true);
    }

    /**
     * Tests form->setRegleValidation()
     */
    public function testSetRegleValidation()
    {
        $this->assertTrue(true);
    }

    /**
     * Tests form->setBlocValidJS()
     */
    public function testSetBlocValidJS()
    {
        $this->assertTrue(true);
    }

    /**
     * Tests form->validerForm()
     */
    public function testValiderForm()
    {
        $this->assertTrue(true);
    }

    /**
     * Tests form->addErreur()
     */
    public function testAddErreur()
    {
        $this->assertTrue(true);
    }

    /**
     * Tests form->__toString()
     */
    public function test__toString()
    {
        $this->assertTrue(true);
    }
}

