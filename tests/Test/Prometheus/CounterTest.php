<?php


namespace Test\Prometheus;
use PHPUnit_Framework_TestCase;
use Prometheus\Counter;

/**
 * See https://prometheus.io/docs/instrumenting/exposition_formats/
 */
class CounterTest extends PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function itShouldIncreaseWithLabels()
    {
        $gauge = new Counter('test', 'some_metric', 'this is for testing', array('foo', 'bar'));
        $gauge->increase(array('foo' => 'lalal', 'bar' => 'lululu'));
        $gauge->increase(array('foo' => 'lalal', 'bar' => 'lululu'));
        $gauge->increase(array('foo' => 'lalal', 'bar' => 'lululu'));
        $this->assertThat(
            $gauge->getSamples(),
            $this->equalTo(
                array(
                    array(
                        'name' => 'test_some_metric',
                        'labelNames' => array('foo', 'bar'),
                        'labelValues' => array('lalal', 'lululu'),
                        'value' => 3,
                    )
                )
            )
        );
    }

    /**
     * @test
     */
    public function itShouldIncreaseWithoutLabelWhenNoLabelsAreDefined()
    {
        $gauge = new Counter('test', 'some_metric', 'this is for testing');
        $gauge->increase();
        $this->assertThat(
            $gauge->getSamples(),
            $this->equalTo(
                array(
                    array(
                        'name' => 'test_some_metric',
                        'labelNames' => array(),
                        'labelValues' => array(),
                        'value' => 1,
                    )
                )
            )
        );
    }

    /**
     * @test
     */
    public function itShouldIncreaseTheCounterByAnArbitraryInteger()
    {
        $gauge = new Counter('test', 'some_metric', 'this is for testing', array('foo', 'bar'));
        $gauge->increase(array('foo' => 'lalal', 'bar' => 'lululu'));
        $gauge->increaseBy(123, array('foo' => 'lalal', 'bar' => 'lululu'));
        $this->assertThat(
            $gauge->getSamples(),
            $this->equalTo(
                array(
                    array(
                        'name' => 'test_some_metric',
                        'labelNames' => array('foo', 'bar'),
                        'labelValues' => array('lalal', 'lululu'),
                        'value' => 124,
                    )
                )
            )
        );
    }
}