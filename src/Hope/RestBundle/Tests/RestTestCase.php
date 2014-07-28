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
}