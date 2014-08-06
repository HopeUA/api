<?php
namespace Hope\RestBundle\Tests\Controller;

use Hope\RestBundle\Tests\RestTestCase;
use Symfony\Component\HttpFoundation\Response;

class TimeTableControllerTest extends RestTestCase
{
    private $url = '/v1/timetable.json';

    public function testExistingDate()
    {
        $date = '2014-08-01';

        $client = static::createClient();
        $params = ['date' => $date];
        $client->request('GET', $this->url, $params);

        $this->assertEquals(
            Response::HTTP_OK,
            $client->getResponse()->getStatusCode()
        );
        $response = $client->getResponse()->getContent();

        $data = json_decode($response);
        $this->assertInternalType('array', $data);
        $this->assertEquals(5, count($data));
        $this->assertEquals($this->getTimeTable($date . ' 05:50:00'), $data[0]);
        $this->assertEquals($this->getTimeTable($date . ' 06:30:00'), $data[3]);
    }

    public function badDateProvider()
    {
        return [
            ['2014-08-02'],
            ['2014-08-02 12:12:12'],
            ['2014'],
            ['___'],
            ['Invalidate'],
        ];
    }

    /**
     * @dataProvider badDateProvider
     */
    public function testNotFoundDate($date)
    {
        $client = static::createClient();
        $params = ['date' => $date];
        $client->request('GET', $this->url, $params);

        $this->assertEquals(
            Response::HTTP_NOT_FOUND,
            $client->getResponse()->getStatusCode()
        );
        $response = $client->getResponse()->getContent();

        $data = json_decode($response);
        $this->assertEquals($this->getErrorResult('timetable'), $data);
    }
}