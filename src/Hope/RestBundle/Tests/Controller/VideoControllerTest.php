<?php
namespace Hope\RestBundle\Tests\Controller;

use Hope\RestBundle\Tests\RestTestCase;
use Symfony\Component\HttpFoundation\Response;

class VideoControllerTest extends RestTestCase
{
    private $url = '/v1/video.json';

    public function episodeCodeProvider()
    {
        return [
            [
                'code'   => 'FLNU02412',
                'exists' => true,
            ],
            [
                'code'   => '',
                'exists' => false,
            ],
            [
                'code'   => 'CYCU018',
                'exists' => false,
            ],
            [
                'code'   => 'CYCU99999',
                'exists' => false,
            ],
        ];
    }

    /**
     * @dataProvider episodeCodeProvider
     */
    public function testByCode($code, $exists)
    {
        $client = static::createClient();
        $params = ['code' => $code];

        $client->request('GET', $this->url, $params);

        if ($exists) {
            $this->assertEquals(
                Response::HTTP_OK,
                $client->getResponse()->getStatusCode()
            );
            $response = $client->getResponse()->getContent();

            $data = json_decode($response);
            $this->assertEquals(1, count($data));
            $this->assertEquals($this->getEpisode($code), $data[0]);
        } else {
            $this->assertEquals(
                Response::HTTP_NOT_FOUND,
                $client->getResponse()->getStatusCode()
            );
            $response = $client->getResponse()->getContent();

            $data = json_decode($response);
            $this->assertEquals($this->getErrorResult('episode'), $data);
        }
    }

    public function programCodeProvider()
    {
        return [
            [
                'code'   => 'CYCU',
                'exists' => true,
            ],
            [
                'code'   => '',
                'exists' => false,
            ],
            [
                'code'   => 'AAAA',
                'exists' => false,
            ],
            [
                'code'   => '_____',
                'exists' => false,
            ],
        ];
    }

    /**
     * @dataProvider programCodeProvider
     */
    public function testByProgramCode($code, $exists)
    {
        $client = static::createClient();

        $params = [
            'program_code' => $code,
            'limit'        => '0|3',
        ];
        $client->request('GET', $this->url, $params);

        if ($exists) {
            $this->assertEquals(
                Response::HTTP_OK,
                $client->getResponse()->getStatusCode()
            );
            $response = $client->getResponse()->getContent();

            $epCode = 'CYCU00112';
            $data   = json_decode($response);

            $this->assertEquals(2, count($data));
            $this->assertEquals($epCode, $data[0]->code);
            $this->assertEquals($this->getEpisode($epCode), $data[0]);
        } else {
            $this->assertEquals(
                Response::HTTP_NOT_FOUND,
                $client->getResponse()->getStatusCode()
            );
            $response = $client->getResponse()->getContent();

            $data = json_decode($response);
            $this->assertEquals($this->getErrorResult('episode'), $data);
        }
    }

    public function programCategoryProvider()
    {
        return [
            [
                'id'     => 1,
                'exists' => true,
            ],
            [
                'id'   => '',
                'exists' => false,
            ],
            [
                'id'   => 'test',
                'exists' => false,
            ],
        ];
    }
    /**
     * @dataProvider programCategoryProvider
     */
    public function testByProgramCategory($id, $exists)
    {
        $client = static::createClient();

        $params = [
            'program_category' => $id,
            'limit'        => '0|3',
        ];
        $client->request('GET', $this->url, $params);

        if ($exists) {
            $this->assertEquals(
                Response::HTTP_OK,
                $client->getResponse()->getStatusCode()
            );
            $response = $client->getResponse()->getContent();

            $epCode = 'FBNU00512';
            $data   = json_decode($response);

            $this->assertEquals(3, count($data));
            $this->assertEquals($epCode, $data[0]->code);
            $this->assertEquals($this->getEpisode($epCode), $data[0]);
        } else {
            $this->assertEquals(
                Response::HTTP_NOT_FOUND,
                $client->getResponse()->getStatusCode()
            );
            $response = $client->getResponse()->getContent();

            $data = json_decode($response);
            $this->assertEquals($this->getErrorResult('episode'), $data);
        }
    }

    public function textSearchProvider()
    {
        return [
            [
                'text' => 'шалаш',
                'code' => 'SVCU00913',
            ],
            [
                'text' => 'цікаво послухати розповіді',
                'code' => 'HDVU01612',
            ],
            [
                'text' => 'Добрячек',
                'code' => 'HDVU01612',
            ],
            [
                'text' => '___',
                'code' => null,
            ],
            [
                'text' => '%%%',
                'code' => null,
            ],
        ];
    }
    /**
     * @dataProvider textSearchProvider
     */
    public function testByText($text, $code)
    {
        $client = static::createClient();

        $params = [
            'text'  => $text,
            'limit' => '0|1',
        ];
        $client->request('GET', $this->url, $params);

        if (!is_null($code)) {
            $this->assertEquals(
                Response::HTTP_OK,
                $client->getResponse()->getStatusCode()
            );
            $response = $client->getResponse()->getContent();

            $data   = json_decode($response);

            $this->assertEquals(1, count($data));
            $this->assertEquals($code, $data[0]->code);
            $this->assertEquals($this->getEpisode($code), $data[0]);
        } else {
            $this->assertEquals(
                Response::HTTP_NOT_FOUND,
                $client->getResponse()->getStatusCode()
            );
            $response = $client->getResponse()->getContent();

            $data = json_decode($response);
            $this->assertEquals($this->getErrorResult('episode'), $data);
        }
    }

    public function limitProvider()
    {
        return [
            [
                'limit' => '0|1',
                'code'  => 'HDVU01612',
            ],
            [
                'limit' => '1|1',
                'code'  => 'HDVU00412',
            ],
            [
                'limit' => '2|1',
                'code'  => null,
            ],
        ];
    }
    /**
     * @dataProvider limitProvider
     */
    public function testLimit($limit, $code)
    {
        $client = static::createClient();

        $params = [
            'limit'        => $limit,
            'program_code' => 'HDVU',
        ];
        $client->request('GET', $this->url, $params);

        if (!is_null($code)) {
            $this->assertEquals(
                Response::HTTP_OK,
                $client->getResponse()->getStatusCode()
            );
            $response = $client->getResponse()->getContent();

            $data   = json_decode($response);

            $this->assertEquals(1, count($data));
            $this->assertEquals($code, $data[0]->code);
            $this->assertEquals($this->getEpisode($code), $data[0]);
        } else {
            $this->assertEquals(
                Response::HTTP_NOT_FOUND,
                $client->getResponse()->getStatusCode()
            );
            $response = $client->getResponse()->getContent();

            $data = json_decode($response);
            $this->assertEquals($this->getErrorResult('episode'), $data);
        }
    }
}