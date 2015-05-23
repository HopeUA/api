<?php

namespace Hope\RestBundle\Tests\Service;

use Hope\RestBundle\Service\Tools;

class ToolsTest extends \PHPUnit_Framework_TestCase
{
    public static function testValidateDate()
    {

        $datetime = new Tools();

        $result = $datetime::validateDate(date("Y-m-d"), "Y-m-d");
        self::assertEquals(true, $result);

        $result = $datetime::validateDate(date("Y-m-d H:i:s"), "Y-m-d H:i:s");
        self::assertEquals(true, $result);

        $result = $datetime::validateDate(date("Y-m-d H:i:s"));
        self::assertEquals(true, $result);
    }
}
