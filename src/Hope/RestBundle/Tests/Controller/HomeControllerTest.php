<?php
namespace Hope\RestBundle\Tests\Controller;

use Hope\RestBundle\Tests\RestTestCase;
use Symfony\Component\HttpFoundation\Response;

class HomeControllerTest extends RestTestCase
{
    /**
     * @medium
     */
    public function testRequest()
    {
        $client = static::createClient();

        $client->request('GET', '/v1/home.json');

        $this->assertEquals(
            Response::HTTP_OK,
            $client->getResponse()->getStatusCode()
        );
        $response = $client->getResponse()->getContent();

        $data = json_decode($response);
        $this->assertInstanceOf('stdClass', $data);

        // Test Banners
        $this->assertObjectHasAttribute('banners', $data);
        $this->checkBanners($data->banners);

        // Test Live
        $this->assertObjectHasAttribute('live', $data);
        $this->checkLive($data->live);

        // Test Categories
        $this->assertObjectHasAttribute('categories', $data);
        $this->checkCategories($data->categories);

        // Test Top Videos
        $this->objectHasAttribute('top_videos', $data);
        $this->checkVideos($data->top_videos);

        // Test Static Pages
        $this->objectHasAttribute('about', $data);
        $this->checkAbout($data->about);
    }

    private function checkBanners($banners)
    {
        $this->assertInternalType('array', $banners);

        if (count($banners)) {
            $banner = $banners[array_rand($banners)];

            $this->assertInstanceOf('stdClass', $banner);
            $this->assertObjectHasAttribute('image', $banner);
            $this->assertNotEmpty($banner->image);
            $this->assertObjectHasAttribute('url', $banner);
            $this->assertNotEmpty($banner->url);
        }
    }

    private function checkLive($live)
    {
        $this->assertInstanceOf('stdClass', $live);
        $this->assertObjectHasAttribute('stream', $live);
        $this->assertNotEmpty($live->stream);
    }

    private function checkCategories($categories)
    {
        $this->assertInternalType('array', $categories);
        $this->assertGreaterThan(0, count($categories));

        $cat = $categories[array_rand($categories)];

        $this->assertObjectHasAttribute('id', $cat);
        $this->assertInternalType('integer', $cat->id);

        $this->assertObjectHasAttribute('title', $cat);
        $this->assertNotEmpty($cat->title);

        $this->assertObjectHasAttribute('programs', $cat);
        $this->checkPrograms($cat->programs);
    }

    private function checkPrograms($programs)
    {
        $this->assertInternalType('array', $programs);
        if (count($programs)) {
            $program = $programs[array_rand($programs)];
            $this->assertInstanceOf('stdClass', $program);

            $attrs = [
                'code' => [
                    'required' => true,
                    'regex'    => '^[A-Z]{4}$',
                ],
                'title' => [
                    'required' => true,
                ],
                'desc_short' => [],
                'desc_full'  => [],
            ];
            $this->checkAttributes($program, $attrs);
        }
    }

    private function checkVideos($videos)
    {
        $this->assertInternalType('array', $videos);
        $this->assertGreaterThan(0, count($videos));

        $video = $videos[array_rand($videos)];
        $this->assertInstanceOf('stdClass', $video);

        $attrs = [
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
        $this->checkAttributes($video, $attrs);

        $this->assertObjectHasAttribute('link', $video);

        $link = $video->link;
        $this->assertInstanceOf('stdClass', $link);
        $this->assertObjectHasAttribute('download', $link);
        $this->assertAttributeNotEmpty('download', $link);
        $this->assertObjectHasAttribute('watch', $link);
        $this->assertAttributeNotEmpty('watch', $link);
    }

    private function checkAbout($about)
    {
        $this->assertInternalType('array', $about);
        $this->assertEquals(3, count($about));

        foreach ($about as $page) {
            $this->assertInstanceOf('stdClass', $page);

            $attrs = [
                'section' => [
                    'required' => true,
                    'regex'    => '^a-z+$',
                ],
                'title'   => [
                    'required' => true,
                ],
                'text'    => [
                    'required' => true,
                ],
            ];
            $this->checkAttributes($page, $attrs);
        }
    }
}
