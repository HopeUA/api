<?php
namespace Hope\RestBundle\Tests\Controller;

use Hope\RestBundle\Tests\RestTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Client;

class VideoControllerTest extends RestTestCase
{
    private $url = '/v1/video.json';

    private $notFoundAttrs = [
        'error' => [
            'required' => true,
            'type'     => 'boolean',
        ],
        'message' => [
            'required' => true,
        ],
    ];

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
            $this->assertEquals($this->getEpisode($code), $data);
        } else {
            $this->assertEquals(
                Response::HTTP_NOT_FOUND,
                $client->getResponse()->getStatusCode()
            );
            $response = $client->getResponse()->getContent();

            $data = json_decode($response);
            $this->assertEquals($this->getErrorResult(), $data);
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
        $expected = $exists ? 3 : 0;
        $episode  = $this->checkEpisode($params, $expected, $client);

        if ($episode) {
            $this->assertEquals($code, $episode->program);
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
        $expected = $exists ? 3 : 0;
        $this->checkEpisode($params, $expected, $client);
    }

    protected function checkEpisode($params, $expected, Client $client)
    {
        $episode = null;

        $client->request('GET', $this->url, $params);
        if ($expected) {
            $this->assertEquals(
                Response::HTTP_OK,
                $client->getResponse()->getStatusCode()
            );

            $response = $client->getResponse()->getContent();

            $data = json_decode($response);
            $this->assertInternalType('array', $data);
            $this->assertEquals($expected, count($data));

            $episode = $data[0];
            $this->checkAttributes($episode, $this->episodeAttrs);

            // Link obj
            $this->assertObjectHasAttribute('link', $episode);
            $link = $episode->link;
            $this->assertInstanceOf('stdClass', $link);
            $this->assertObjectHasAttribute('download', $link);
            $this->assertAttributeNotEmpty('download', $link);
            $this->assertObjectHasAttribute('watch', $link);
            $this->assertAttributeNotEmpty('watch', $link);

        } else {
            $this->assertEquals(
                Response::HTTP_NOT_FOUND,
                $client->getResponse()->getStatusCode()
            );

            $response = $client->getResponse()->getContent();

            $data = json_decode($response);
            $this->assertInstanceOf('stdClass', $data);
            $this->checkAttributes($data, $this->notFoundAttrs);
        }

        return $episode;
    }
}