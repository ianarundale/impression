<?php
/**
 * @package Impression
 */
/**
 * The Impression Templating Class
 *
 * Extremely lightweight PHP templating engine designed to encorage the MVC design pattern.
 *
 * @copyright Copyright (c) 2013 Ian Arundale
 * @author Ian Arundale <ian.arundale@gmail.com>
 *
 * @license Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * All rights reserved
 */

class Impression
{
    protected $template_file;
    protected $values = array();
    public $static_path = './';


    /**
     * The class constructor.
     *
     * Sets the template file and static path.
     * @param String $template The directory path to the antie config directory.
     * @param String $static_path The path to static assets
     */
    public function __construct($template = null, $static_path = null)
    {
        if ($template == null) {
            throw new ImpressionException("Template file not provided");
        }

        if (!file_exists($template)) {
            throw new ImpressionException("Template file could not found");
        }

        $this->template_file = $template;

        if ($static_path != null) {
            $this->static_path = $static_path;
        }
    }

    public function get_template_file()
    {
        return $this->template_file;
    }

    /**
     * @param String $key
     * @param $value
     * @return void
     */
    public function assign($key, $value)
    {
        $this->$key = $value;
    }

    /**
     * @throws ImpressionException
     * @return void
     */
    public function render()
    {
        // Capture the view output
        ob_start();

        try {
            include($this->template_file);
        } catch (Exception $e) {
            throw new ImpressionException($e->getMessage());
        }

        // The template has now been fully built, flush the output buffer
        // ob_get_flush is used over ob_flush for testing purposes
        return ob_get_flush();
    }
}

/**
 * Exception to be thrown
 */
class ImpressionException extends Exception
{

}
