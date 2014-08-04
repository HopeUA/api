<?php
namespace Hope\RestBundle\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RestTestCase extends WebTestCase
{
    protected function checkAttributes($data, $attributes)
    {
        foreach ($attributes as $attr => $options) {
            // Init Options Resolver
            $resolver = new OptionsResolver();
            $this->configureOptions($resolver);
            $options = $resolver->resolve($options);

            $this->assertObjectHasAttribute($attr, $data);

            if ($options['required']) {
                $this->assertAttributeNotEmpty($attr, $data);
            }
            $this->assertAttributeInternalType($options['type'], $attr, $data);
            if ($options['regex']) {
                $pattern = '~'.$options['regex'].'~';
                $this->assertRegExp($pattern, $data->{$attr});
            }
        }
    }

    protected function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'type'     => 'string',
                'required' => false,
                'regex'    => false,
            ]
        );
    }

    protected function getEpisode($code, $convertToObject = true)
    {
        $episodes = [];
        $episodes['CYCU00112'] = [
            'code'         => 'CYCU00112',
            'title'        => 'Залежності',
            'desc'         => 'Кожен із нас у своєму житті неодноразово казав «СТОП». Стоп бажанню смачно поїсти о 11-ій вечора, стоп приводу нечесно заробити чималу суму грошей, стоп  перед пешохідним переходом на пустій дорозі але з червоним кольором світлофора, стоп  бажанню отримати насолоду через заборонене. Іноді нам вдається зупинитися, іноді — ні.<br />Сьогодні наша тема — стоп соціально шкідливим залежностям.<br /><br /><i>Гості: Ірина Дніпровська та Андрій Шамрай</i>',
            'author'       => '',
            'program'      => 'CYCU',
            'duration'     => 300,
            'publish_time' => '2014-07-28 04:03:12',
            'hd'           => false,
            'image'        => '',
            'link'         => [
                'download' => '',
                'watch'    => '',
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

        $ep = isset($episodes[$code]) ? $episodes[$code] : [];
        if ($convertToObject) {
            $ep = json_decode(json_encode($ep));
        }

        return $ep;
    }

    protected function getErrorResult($type = '', $convertToObject = true)
    {
        $error = [
            'error'   => true,
            'message' => 'Ошибка',
        ];

        if ($convertToObject) {
            $error = json_decode(json_encode($error));
        }

        return $error;

    }
}