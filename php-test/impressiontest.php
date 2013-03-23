<?php

require_once '../impression.php';

class ImpressionTest extends PHPUnit_Framework_TestCase
{
    /*
     * Turn on output buffering to prevent output during tests
     */
    public function setUp()
    {

        date_default_timezone_set("Europe/London");
        ob_start();
    }

    /**
     * Clean up the output buffer
     * @return void
     */
    public function tearDown()
    {
        ob_end_clean();
    }


    public function test_impression_constructor_sets_template_file()
    {
        $file = "test-data/test_template.php";
        $template = new Impression($file);

        $this->assertEquals($file, $template->get_template_file());
    }

    public function test_impression_constructor_sets_static_path()
    {
        $file = "test-data/test_template.php";
        $static_path = "test-data/test_template.php";
        $template = new Impression($file, $static_path );

        $this->assertEquals($file, $template->static_path);
    }


    /**
     * @expectedException ImpressionException
     * @return void
     */
    public function test_impression_constructor_without_file_parameter_throws_exception()
    {
        $template = new Impression();
    }

    /**
     * @expectedException ImpressionException
     * @return void
     */
    public function test_impression_constructor_throws_exception_if_template_file_does_not_exist_on_file_system()
    {
        $template = new Impression("invalid/file/path");
    }

    public function test_assign_sets_valid_member_property()
    {
        $template = new Impression("test-data/test_template.php");
        $template->assign("some_key", "some_value");

        $this->assertEquals("some_value", $template->some_key);
    }

    public function test_render_outputs_template_file_contents()
    {
        $template = new Impression("test-data/test_template.php");
        ob_start();
        $template_content = file_get_contents("test-data/test_template.php");
        $render_output = $template->render();

        $this->assertEquals($template_content, $render_output);

    }

    public function test_render_outputs_template_and_replaces_assigned_values()
    {
        $template = new Impression("test-data/test_template_assigned_values.php");
        $template->assign("planet", "Earth");

        $render_output = $template->render();
        $this->assertEquals("Hello Earth", $render_output);
    }

    /**
     * @expectedException ImpressionException
     * @return void
     */
    public function test_render_throws_exception_when_template_variable_not_assigned()
    {
        $template = new Impression("test-data/test_template_assigned_values.php");
        $render_output = $template->render();
        $this->assertEquals("Hello Earth", $render_output);
    }
}