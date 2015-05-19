<?php
namespace Hope\RestBundle\Tests\Controller;

use Hope\RestBundle\Tests\RestTestCase;
use MyProject\Proxies\__CG__\OtherProject\Proxies\__CG__\stdClass;
use Symfony\Component\HttpFoundation\Response;

class HomeControllerTest extends RestTestCase
{
    /**
     * API V1
     * /home.json
     *
     * @large
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

        return $data;
    }

    /**
     * API V1
     *
     * @code
     * {
     *   banners: {
     *     [
     *       {
     *         image: "{url}",
     *         url: "{url}"
     *       }
     *     ]
     *   }
     * }
     *
     * @large
     * @depends testRequest
     */
    public function testBanners($data)
    {
        $this->assertObjectHasAttribute('banners', $data);
        $banners = $data->banners;

        $this->assertInternalType('array', $banners);
        $banner = $banners[0];

        $original = [
            'id'    => 1,
            'image' => 'http://hope.ua/test.jpg',
            'url'   => 'http://hope.ua',
        ];
        $original = json_decode(json_encode($original));
        $this->assertEquals($original, $banner);
    }

    /**
     * API V1
     *
     * @code
     * {
     *   live: {
     *     {
     *       stream: "{url}"
     *     }
     *   }
     * }
     *
     * @large
     * @depends testRequest
     */
    public function testLive($data)
    {
        $this->assertObjectHasAttribute('live', $data);
        $live = $data->live;

        $this->assertInstanceOf('stdClass', $live);
        $this->assertObjectHasAttribute('stream', $live);
        $this->assertNotEmpty($live->stream);
    }

    /**
     * API V1
     *
     * @code
     * {
     *   categories: {
     *     [
     *       {
     *          "id": {int},
     *          "title": "{string}",
     *          "programs": []
     *       }
     *     ]
     *   }
     * }
     *
     * @large
     * @depends testRequest
     */
    public function testCategories($data)
    {
        $this->assertObjectHasAttribute('categories', $data);
        $categories = $data->categories;

        $this->assertInternalType('array', $categories);
        $this->assertGreaterThan(0, count($categories));

        $cat = $categories[0];

        $this->assertObjectHasAttribute('id', $cat);
        $this->assertInternalType('integer', $cat->id);
        $this->assertEquals(1, $cat->id);

        $this->assertObjectHasAttribute('title', $cat);
        $this->assertEquals('молодежные', $cat->title);

        return $cat->programs;
    }

    /**
     * API V1
     *
     * @code
     * {
     *   programs: {
     *     [
     *       {
     *          "code": "{string}",
     *          "title": "{string}",
     *          "desc_short": "{string}",
     *          "desc_full": "{string}"
     *       }
     *     ]
     *   }
     * }
     *
     * @large
     * @depends testCategories
     */
    public function testPrograms($programs)
    {
        $this->assertInternalType('array', $programs);

        $program = $programs[0];
        $this->assertInstanceOf('stdClass', $program);

        $original = [
            'id'          => 7,
            'code'        => 'CYCU',
            'title'       => 'Поспілкуймося',
            'desc_short'  => 'Молодіжне ток-шоу на теми, які найбільше хвилюють молодь. Це відкритий діалог молодих людей та досвідчених духовних лідерів.',
            'desc_full'   => ''
        ];
        $original = json_decode(json_encode($original));
        $this->assertEquals($original, $program);
    }

    /**
     * API V1
     *
     * @code
     * {
     *   top_videos: {
     *     [
     *       {
     *          "code": "{string}",
     *          "title": "{string}",
     *          "desc": "{string}",
     *          "author": "{string}",
     *          "program": "{string}",
     *          "duration": {int},
     *          "publish_time": "{datetime}",
     *          "hd": {bool},
     *          "image": "{url}",
     *          "link": {
     *            "download": "{url}",
     *            "watch": "{url}"
     *          }
     *       }
     *     ]
     *   }
     * }
     *
     * @large
     * @depends testRequest
     */
    public function testVideos($data)
    {
        $this->objectHasAttribute('top_videos', $data);
        $videos = $data->top_videos;

        $this->assertInternalType('array', $videos);
        $this->assertEquals(6, count($videos));

        $this->assertEquals($this->getEpisode('HDVU01612'), $videos[0]);
        $this->assertEquals($this->getEpisode('FBNU00512'), $videos[1]);
        $this->assertEquals($this->getEpisode('CYCU00112'), $videos[4]);
    }

    /**
     * API V1
     *
     * @code
     * {
     *   about: {
     *     [
     *       {
     *          "section": "{string}",
     *          "title": "{string}",
     *          "text": "{string}",
     *       }
     *     ]
     *   }
     * }
     *
     * @large
     * @depends testRequest
     */
    public function testAbout($data)
    {
        $this->objectHasAttribute('about', $data);
        $about = $data->about;

        $this->assertInternalType('array', $about);
        $this->assertEquals(3, count($about));

        $page = $about[0];

        $original = [
            'id'      => 1,
            'section' => 'first',
            'title'   => 'first',
            'text'    => 'first',
        ];
        $original = json_decode(json_encode($original));
        $this->assertEquals($original, $page);
    }
}
