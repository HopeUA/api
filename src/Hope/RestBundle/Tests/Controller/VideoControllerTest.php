<?php
namespace Hope\RestBundle\Tests\Controller;

use Hope\RestBundle\Tests\RestTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Client;

class VideoControllerTest extends RestTestCase
{
    private $url = '/v1/video.json';

    private $episodeAttrs = [
        'code'         => [
            'required' => true,
            'regex'    => '^[A-Z]{4}\d{5}$',
        ],
        'title'        => [
            'required' => true,
        ],
        'desc'         => [],
        'author'       => [],
        'program'      => [
            'required' => true,
            'regex'    => '^[A-Z]{4}$',
        ],
        'duration'     => [
            'type' => 'integer',
        ],
        'publish_time' => [
            'regex' => '\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}',
        ],
        'hd'           => [
            'type' => 'boolean'
        ],
        'image'        => [
            'required' => true
        ],
    ];

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
                'code'   => 'WUCU00311',
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
        $params   = ['code' => $code];
        $expected = $exists ? 1 : 0;
        $this->checkEpisode($params, $expected, $client);

    }

    public function programCodeProvider()
    {
        return [
            [
                'code'   => 'WUCU',
                'exists' => true,
            ],
            [
                'code'   => 'SS',
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
        $this->checkEpisode($params, $expected, $client);
    }

    protected function checkEpisode($params, $expected, Client $client)
    {
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

            $this->assertObjectHasAttribute('link', $episode);

            // Link obj
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
    }
}