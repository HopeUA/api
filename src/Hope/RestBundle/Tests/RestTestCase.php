<?php
namespace Hope\RestBundle\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RestTestCase extends WebTestCase
{
    protected function getEpisode($code, $convertToObject = true)
    {
        $episodes = [];
        $episodes['CYCU00112'] = [
            'code'         => 'CYCU00112',
            'title'        => 'Залежності',
            'desc'         => 'Кожен із нас у своєму житті неодноразово казав «СТОП». Стоп бажанню смачно поїсти о 11-ій вечора, стоп приводу нечесно заробити чималу суму грошей, стоп  перед пешохідним переходом на пустій дорозі але з червоним кольором світлофора, стоп  бажанню отримати насолоду через заборонене. Іноді нам вдається зупинитися, іноді — ні.<br />Сьогодні наша тема — стоп соціально шкідливим залежностям.<br /><br /><i>Гості: Ірина Дніпровська та Андрій Шамрай</i>',
            'author'       => 'Ведущая',
            'program'      => 'CYCU',
            'duration'     => 300,
            'publish_time' => '2014-07-28 04:03:12',
            'hd'           => false,
            'image'        => 'http://share.yourhope.tv/CYCU00112.jpg',
            'link'         => [
                'download' => 'http://share.yourhope.tv/CYCU00112.mov',
                'watch'    => 'https://www.youtube.com/watch?v=youtubeLink',
            ],
        ];
        $episodes['HDVU00412'] = [
            'code'         => 'HDVU00412',
            'title'        => 'Мама',
            'desc'         => 'Хто є і в мами, і в тата, і в сестрички, і в брата, і в усіх-усіх на світі? Відповідь на це запитання ви зможете дізнати, якщо переглянете нашу передачу.',
            'author'       => 'Добрячек',
            'program'      => 'HDVU',
            'duration'     => 200,
            'publish_time' => '2014-07-28 11:03:12',
            'hd'           => true,
            'image'        => 'http://share.yourhope.tv/HDVU00412.jpg',
            'link'         => [
                'download' => 'http://share.yourhope.tv/HDVU00412.mov',
                'watch'    => 'https://www.youtube.com/watch?v=youtubeHDLink',
            ],
        ];
        $episodes['HDVU01612'] = [
            'code'         => 'HDVU01612',
            'title'        => 'Най-най-най',
            'desc'         => 'Тим, хто хоче дізнатись щось най-най-най, буде цікаво послухати розповіді Добрячка Даші та Ганнусі.',
            'author'       => 'Добрячек',
            'program'      => 'HDVU',
            'duration'     => 1000,
            'publish_time' => '2014-07-28 22:07:12',
            'hd'           => true,
            'image'        => 'http://share.yourhope.tv/HDVU01612.jpg',
            'link'         => [
                'download' => 'http://share.yourhope.tv/HDVU01612.mov',
                'watch'    => 'https://www.youtube.com/watch?v=youtubeHDLink',
            ],
        ];
        $episodes['FBNU00312'] = [
            'code'         => 'FBNU00312',
            'title'        => 'Греховна ли красота?',
            'desc'         => 'Вы красивы? Не торопитесь отвечать на этот вопрос,потому что стандарты красоты всегда меняются. Но если вас интересует отношение Бога к красоте, давайте откроем Модную Книгу.<br /><br />Гость студии: <i>Владимир Лукин.</i>',
            'author'       => 'Ведущая',
            'program'      => 'FBNU',
            'duration'     => 500,
            'publish_time' => '2014-07-28 04:07:12',
            'hd'           => false,
            'image'        => 'http://share.yourhope.tv/FBNU00312.jpg',
            'link'         => [
                'download' => 'http://share.yourhope.tv/FBNU00312.mov',
                'watch'    => 'https://www.youtube.com/watch?v=youtubeLink',
            ],
        ];
        $episodes['FBNU00512'] = [
            'code'         => 'FBNU00512',
            'title'        => 'Жизнь в азарте',
            'desc'         => 'Азарт присущ практически каждому. Но что делать, если он в избытке?Подскажет Модная Книга.<br /><br /><i>Гость студии: Александр Созинов</i>',
            'author'       => 'Ведущая',
            'program'      => 'FBNU',
            'duration'     => 100,
            'publish_time' => '2014-07-28 18:07:12',
            'hd'           => false,
            'image'        => 'http://share.yourhope.tv/FBNU00512.jpg',
            'link'         => [
                'download' => 'http://share.yourhope.tv/FBNU00512.mov',
                'watch'    => 'https://www.youtube.com/watch?v=youtubeLink',
            ],
        ];
        $episodes['SVCU00913'] = [
            'code'         => 'SVCU00913',
            'title'        => 'Рай в шалаше',
            'desc'         => 'Правдиво ли выражение: «С милым рай и в шалаше»? Как превратить маленькую комнатушку в уютное гнездышко? Юра и Ульяна, не понаслышке знают, что значит  жить в тесноте, да еще и с двумя маленькими детьми. Собственными руками они создали в своем шалаше настоящий рай. Супруги уверены, что счастье, не зависит от простора и достатка.',
            'author'       => 'Виктор Алексеенко',
            'program'      => 'SVCU',
            'duration'     => 21,
            'publish_time' => '2014-07-28 05:10:13',
            'hd'           => false,
            'image'        => 'http://share.yourhope.tv/SVCU00913.jpg',
            'link'         => [
                'download' => 'http://share.yourhope.tv/SVCU00913.mov',
                'watch'    => 'https://www.youtube.com/watch?v=youtubeLink',
            ],
        ];
        $episodes['FLNU02412'] = [
            'code'         => 'FLNU02412',
            'title'        => 'Вместе веселей',
            'desc'         => 'Если мы любим наших друзей, то почему с ними ссоримся? Давайте это выясним в "Дружболандии"!',
            'author'       => 'Ведущая',
            'program'      => 'FLNU',
            'duration'     => 11,
            'publish_time' => '2014-07-28 01:01:13',
            'hd'           => false,
            'image'        => 'http://share.yourhope.tv/FLNU02412.jpg',
            'link'         => [
                'download' => 'http://share.yourhope.tv/FLNU02412.mov',
                'watch'    => 'https://www.youtube.com/watch?v=youtubeLink',
            ],
        ];

        $ep = isset($episodes[$code]) ? $episodes[$code] : [];
        if ($convertToObject) {
            $ep = json_decode(json_encode($ep));
        }

        return $ep;
    }

    protected function getTimeTable($date, $convertToObject = true)
    {
        $timetable = [];

        $timetable['2014-08-01 05:50:00'] = [
            'datetime' => '2014-08-01 05:50:00',
            'program'  => 'Біблія, як вона є',
            'episode'  => 'Дії апостолів 12:1-25',
        ];
        $timetable['2014-08-01 06:30:00'] = [
            'datetime' => '2014-08-01 06:30:00',
            'program'  => 'Так промовляє Біблія',
            'episode'  => 'Славний день відновлення правосуддя',
        ];

        $tt = isset($timetable[$date]) ? $timetable[$date] : [];
        if ($convertToObject) {
            $tt = json_decode(json_encode($tt));
        }
        return $tt;
    }

    protected function getErrorResult($type, $convertToObject = true)
    {
        $error = [
            'error'   => true,
            'message' => 'Ошибка',
        ];

        switch ($type) {
            case 'timetable':
                $error['message'] = 'Не нашлось записей на эту дату';
                break;
        }

        if ($convertToObject) {
            $error = json_decode(json_encode($error));
        }

        return $error;

    }
}